<?php

namespace App\Jobs;

use App\Models\Attribute;
use App\Models\AttributeValue;
use App\Models\Inventory;
use App\Models\Packaging;
use App\Models\Product;
use App\Models\User;
use App\Notifications\Inventory\ProcessedCsvImport;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Rap2hpoutre\FastExcel\FastExcel;
use Exception;

class ProcessInventoryCsvBulkUpload implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * The number of times the job may be attempted.
     */
    public $tries = 5;

    /**
     * The number of seconds the job can run before timing out.
     */
    public $timeout = 1200;

    public $user;

    private $csv_data;

    private $failed_list = [];

    private $products = [];

    private $success_counter;

    private $failed_file_path = '';

    /**
     * Delete the job if its models no longer exist.
     *
     * @var bool
     */
    public $deleteWhenMissingModels = true;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(User $user, $csv_data = [])
    {
        $this->user = $user;
        $this->csv_data = $csv_data;
        $this->success_counter = 0;
        $this->failed_list = [];

        ini_set('max_execution_time', 0); // Set unlimited time
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        foreach ($this->csv_data as $row) {
            $data = unserialize($row);

            // Invalid data
            if (!is_array($data)) continue;

            // Ignore if required info is not given
            if (!verifyRequiredDataForBulkUpload($data, 'inventory')) {
                $this->pushIntoFailed($data, trans('help.missing_required_data'));
                continue;
            }

            // If the slug is not given the make it
            if (!$data['slug']) {
                $data['slug'] = convertToSlugString($data['title'], $data['sku']);
            }

            // if (is_numeric($data['gtin'])) {
            //     $data['gtin'] = is_int($data['gtin']) ? intval($data['gtin']) : floatval($data['gtin']);
            // }

            // Ignore if the slug is exist in the database
            $item = Inventory::select('slug')->where('slug', $data['slug'])->first();
            if ($item) {
                $this->pushIntoFailed($data, trans('help.slug_already_exist'));
                continue;
            }

            // First search in the $products to reduce db queries. Usefull when the csv have variants
            $product = collect($this->products)->first(function ($value, $key) use ($data) {
                return isset($value['gtin']) && isset($data['gtin']) &&
                    isset($value['gtin_type']) && isset($data['gtin_type']) &&
                    $value['gtin'] == $data['gtin'] &&
                    $value['gtin_type'] == $data['gtin_type'];
            });

            // If not found in the $products get it from databse
            if (!$product) {
                $product = Product::where('gtin', $data['gtin'])
                    ->where('gtin_type', $data['gtin_type'])->first();

                // Push the product to array so next time can get from there
                array_push($this->products, $product);
            }

            // Finally ignore the row if product not found
            if (!$product) {
                $this->pushIntoFailed($data, trans('help.invalid_catalog_data'));
                continue;
            }

            // Create the inventory and get it, If failed then insert into the ignored list
            $inventory = $this->createInventory($data, $product);

            if (!$inventory) {
                $this->pushIntoFailed($data, trans('help.input_error'));
                continue;
            }

            $this->success_counter++; // Increase the counter for successful import
        }

        $failed_rows = $this->getFailedList();

        // When the job processing on current request cycle
        if (config('queue.default') == 'sync') {
            if (!empty($failed_rows)) {
                return view('admin.inventory.import_failed', compact('failed_rows'));
            }

            return redirect()->route('admin.stock.inventory.index')
                ->with('success', trans('messages.imported', ['model' => trans('app.model.inventory')]));
        }

        // Continue if the job processing on queue
        if (!empty($failed_rows)) {
            $this->failed_file_path = $this->createAttachmentWithFailedRows();
        }

        $this->user->notify(new ProcessedCsvImport($failed_rows, $this->success_counter, $this->failed_file_path));
    }

    /**
     * The job failed to process.
     *
     * @param  Exception  $exception
     * @return void
     */
    public function failed(Exception $exception)
    {
        // Send user notification of failure, etc...
    }

    /**
     * Create Product
     *
     * @param  array $product
     * @return App\Models\Product
     */
    private function createInventory($data, $product)
    {
        $key_features = array_filter($data, function ($key) {
            return strpos($key, 'key_feature_') === 0;
        }, ARRAY_FILTER_USE_KEY);

        if ($data['linked_items']) {
            $temp_arr = explode(',', $data['linked_items']);
            $linked_items = Inventory::select('id')->mine()->whereIn('sku', $temp_arr)->pluck('id')->toArray();
        }

        $inventory = Inventory::create([
            'shop_id' => $this->user->merchantId(),
            'title' => $data['title'],
            'slug' => $data['slug'],
            'sku' => $data['sku'],
            'condition' => $data['condition'],
            'condition_note' => $data['condition_note'],
            'description' => $data['description'],
            'product_id' => $product->id,
            'stock_quantity' => $data['stock_quantity'],
            'min_order_quantity' => $data['min_order_quantity'],
            'key_features' => $key_features,
            'brand' => $data['brand'],
            'user_id' => $this->user->id,
            'sale_price' => $data['price'],
            'offer_price' => $data['offer_price'] ?: null,
            'offer_start' => $data['offer_starts'] ? date('Y-m-d h:i a', strtotime($data['offer_starts'])) : null,
            'offer_end' => $data['offer_ends'] ? date('Y-m-d h:i a', strtotime($data['offer_ends'])) : null,
            'purchase_price' => $data['purchase_price'],
            'linked_items' => isset($linked_items) ? $linked_items : null,
            'meta_title' => $data['meta_title'],
            'meta_description' => $data['meta_description'],
            'free_shipping' => strtoupper($data['free_shipping']) == 'TRUE' ? 1 : 0,
            'shipping_weight' => $data['shipping_weight'],
            'available_from' => date('Y-m-d h:i a', strtotime($data['available_from'])),
            'warehouse_id' => $data['warehouse_id'],
            'supplier_id' => $data['supplier_id'],
            'active' => strtoupper($data['active']) == 'TRUE' ? 1 : 0,
        ]);

        // Set attributes
        $attributes = [];
        $variants = array_filter($data, function ($key) {
            return strpos($key, 'option_name_') === 0;
        }, ARRAY_FILTER_USE_KEY);

        foreach ($variants as $index => $option) {
            $count = explode('_', $index);
            if ($data[$index] && $data['option_value_' . $count[2]]) {
                $att = Attribute::select('id')->where('name', $option)->first();

                $val = AttributeValue::firstOrCreate([
                    'value' => $data['option_value_' . $count[2]],
                    'attribute_id' => $att->id,
                ]);

                if ($att && $val) {
                    $attributes[$att->id] = $val->id;
                }
            }
        }

        if (!empty($attributes)) {
            $this->setAttributes($inventory, $attributes); // Sync the attributes with the inventory
        }

        // Upload images
        if ($data['image_links']) {
            $image_links = explode(',', $data['image_links']);

            foreach ($image_links as $image_link) {
                if (filter_var($image_link, FILTER_VALIDATE_URL)) {
                    $inventory->saveImageFromUrl($image_link);
                }
            }
        }

        // Sync packaging
        if ($data['packaging_ids']) {
            $temp_arr = explode(',', $data['packaging_ids']);
            $packaging_ids = Packaging::select('id')->mine()
                ->whereIn('id', $temp_arr)->pluck('id')->toArray();

            $inventory->packaging()->sync($packaging_ids);
        }

        // Sync tags
        if ($data['tags']) {
            $inventory->syncTags($inventory, explode(',', $data['tags']));
        }

        return $inventory;
    }

    /**
     * Set attribute pivot table for the product variants like color, size and more
     * @param obj $inventory
     * @param array $attributes
     */
    public function setAttributes($inventory, $attributes)
    {
        $attributes = array_filter($attributes ?? []);        // remove empty elements

        $temp = [];
        foreach ($attributes as $attribute_id => $attribute_value_id) {
            $temp[$attribute_id] = ['attribute_value_id' => $attribute_value_id];
        }

        if (!empty($temp)) {
            $inventory->attributes()->sync($temp);
        }

        return true;
    }

    /**
     * Push New value Into Failed List
     *
     * @param  array  $data
     * @param  string $reason
     * @return void
     */
    private function pushIntoFailed(array $data, $reason = null)
    {
        $row = [
            'data' => $data,
            'reason' => $reason,
        ];

        array_push($this->failed_list, $row);
    }

    /**
     *  create attachment with failed data
     *
     * @param  Excel  $excel
     */
    public function createAttachmentWithFailedRows()
    {
        $data = [];

        foreach ($this->getFailedList() as $row) {
            $data[] = $row;
        }

        $path = storage_path('failed_rows.xlsx');

        return (new FastExcel(collect($data)))->export($path);
    }

    /**
     * Return the failed list
     *
     * @return array
     */
    private function getFailedList()
    {
        return $this->failed_list;
    }
}
