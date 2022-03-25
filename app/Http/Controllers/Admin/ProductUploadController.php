<?php

namespace App\Http\Controllers\Admin;

use App\Models\Product;
use App\Models\Category;
use App\Models\Manufacturer;
use App\Http\Controllers\Controller;
use App\Http\Requests\Validations\ExportCategoryRequest;
use App\Http\Requests\Validations\ProductImportRequest;
use App\Http\Requests\Validations\ProductUploadRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\MessageBag;
use Rap2hpoutre\FastExcel\FastExcel;

class ProductUploadController extends Controller
{
    private $failed_list = [];

    /**
     * Show upload form
     *
     * @return \Illuminate\Http\Response
     */
    public function showForm()
    {
        return view('admin.product._upload_form');
    }

    /**
     * Upload the csv file and generate the review table
     *
     * @param  ProductUploadRequest $request
     * @return \Illuminate\Http\Response
     */
    public function upload(ProductUploadRequest $request)
    {
        $path = $request->file('products')->getRealPath();
        $records = array_map('str_getcsv', file($path));

        // Validations check for csv_import_limit
        if (count($records) > get_csv_import_limit()) {
            $err = (new MessageBag)->add('error', trans('validation.upload_rows', ['rows' => get_csv_import_limit()]));

            return back()->withErrors($err);
        }

        // Get field names from header column
        $fields = array_map('strtolower', $records[0]);

        // Remove the header column
        array_shift($records);

        $rows = [];
        foreach ($records as $record) {
            if (count($fields) != count($record)) {
                $err = (new MessageBag)->add('error', trans('validation.csv_upload_invalid_data'));

                return back()->withErrors($err);
            }

            // Decode unwanted html entities
            $record =  array_map("html_entity_decode", $record);

            // Set the field name as key
            $record = array_combine($fields, $record);

            // Get the clean data
            $rows[] = clear_encoding_str($record);
        }

        return view('admin.product.upload_review', compact('rows'));
    }

    /**
     * Perform import action
     *
     * @param  ProductImportRequest $request
     * @return \Illuminate\Http\Response
     */
    public function import(ProductImportRequest $request)
    {
        // Reset the Failed list
        $this->failed_list = [];

        $shop_id = Auth::user()->merchantId();
        $records = $request->input('data');

        foreach ($records as $row) {
            $data = unserialize($row);

            // Skip invalid data
            if (!is_array($data)) continue;

            // Ignore if required info is not given
            if (!verifyRequiredDataForBulkUpload($data, 'product')) {
                $this->pushIntoFailed($data, trans('help.missing_required_data'));
                continue;
            }

            // If the slug is not given the make it
            if (!$data['slug']) {
                $data['slug'] = convertToSlugString($data['name'], $data['gtin']);
            }

            // Ignore if the slug is exist in the database
            if (Product::select('slug')->where('slug', $data['slug'])->withTrashed()->first()) {
                $this->pushIntoFailed($data, trans('help.slug_already_exist'));

                continue;
            }

            // Find categories and make the category_list. Ignore the row if category not found
            $data['category_list'] = Category::whereIn('slug', explode(',', $data['categories']))->pluck('id')->toArray();

            if (empty($data['category_list'])) {
                $this->pushIntoFailed($data, trans('help.invalid_category'));

                continue;
            }

            // Set added by info
            $data['shop_id'] = $shop_id;

            // Create the product and get it, If failed then insert into the ignored list
            if (!$this->createProduct($data)) {
                $this->pushIntoFailed($data, trans('help.input_error'));

                continue;
            }
        }

        $request->session()->flash('success', trans('messages.imported', ['model' => trans('app.products')]));

        $failed_rows = $this->getFailedList();

        if (empty($failed_rows)) {
            return redirect()->route('admin.catalog.product.index');
        }

        return view('admin.product.import_failed', compact('failed_rows'));
    }

    /**
     * Create Product
     *
     * @param  array $product
     * @return App\Models\Product
     */
    private function createProduct($data)
    {
        if ($data['origin_country']) {
            $origin_country = DB::table('countries')->select('id')->where('iso_code', strtoupper($data['origin_country']))->first();
        }

        if ($data['manufacturer']) {
            $manufacturer = Manufacturer::firstOrCreate(
                ['name' => $data['manufacturer']],
                ['slug' => Str::slug($data['manufacturer'])]
            );
        }

        // Create the product
        $product = Product::create([
            'shop_id' => $data['shop_id'],
            'name' => $data['name'],
            'slug' => $data['slug'],
            'model_number' => $data['model_number'],
            'description' => $data['description'],
            'gtin' => $data['gtin'],
            'gtin_type' => $data['gtin_type'],
            'mpn' => $data['mpn'],
            'brand' => $data['brand'],
            'origin_country' => isset($origin_country) ? $origin_country->id : null,
            'manufacturer_id' => isset($manufacturer) ? $manufacturer->id : null,
            'min_price' => ($data['minimum_price'] && $data['minimum_price'] > 0) ? $data['minimum_price'] : 0,
            'max_price' => ($data['maximum_price'] && $data['maximum_price'] > $data['minimum_price']) ? $data['maximum_price'] : null,
            'requires_shipping' => strtoupper($data['requires_shipping']) == 'TRUE' ? 1 : 0,
            'active' => strtoupper($data['active']) == 'TRUE' ? 1 : 0,
        ]);

        // Sync categories
        if ($data['category_list']) {
            $product->categories()->sync($data['category_list']);
        }

        // Upload featured image
        if ($data['image_link']) {
            $product->saveImageFromUrl($data['image_link'], 'feature');
        }

        // Sync tags
        if ($data['tags']) {
            $product->syncTags($product, explode(',', $data['tags']));
        }

        return $product;
    }

    /**
     * [downloadCategorySlugs]
     *
     * @param  Excel  $excel
     */
    public function downloadCategorySlugs(ExportCategoryRequest $request)
    {
        $categories = Category::select('name', 'slug')->get();

        return (new FastExcel($categories))->download('categories.xlsx');
    }

    /**
     * downloadTemplate
     *
     * @return response response
     */
    public function downloadTemplate()
    {
        $pathToFile = public_path('csv_templates/products.csv');

        return response()->download($pathToFile);
    }

    /**
     * [downloadFailedRows]
     *
     * @param  Excel  $excel
     */
    public function downloadFailedRows(Request $request)
    {
        foreach ($request->input('data') as $row) {
            $data[] = unserialize($row);
        }

        return (new FastExcel(collect($data)))->download('failed_rows.xlsx');
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
     * Return the failed list
     *
     * @return array
     */
    private function getFailedList()
    {
        return $this->failed_list;
    }
}
