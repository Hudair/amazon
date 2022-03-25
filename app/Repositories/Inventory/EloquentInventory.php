<?php

namespace App\Repositories\Inventory;

use App\Models\Attribute;
use App\Models\AttributeValue;
use App\Models\Inventory;
use App\Models\Product;
use App\Repositories\BaseRepository;
use App\Repositories\EloquentRepository;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class EloquentInventory extends EloquentRepository implements BaseRepository, InventoryRepository
{
    protected $model;

    public function __construct(Inventory $inventory)
    {
        $this->model = $inventory;
    }

    public function all($status = null)
    {
        $inventory = $this->model->with('product', 'image');

        switch ($status) {
            case 'active':
                $inventory = $inventory->active();
                break;

            case 'inactive':
                $inventory = $inventory->inActive();
                break;

            case 'outOfStock':
                $inventory = $inventory->stockOut();
                break;
        }

        if (!Auth::user()->isFromPlatform()) {
            return $inventory->mine()->get();
        }

        return $inventory->get();
    }

    public function trashOnly()
    {
        if (!Auth::user()->isFromPlatform()) {
            return $this->model->mine()->onlyTrashed()->with('product', 'image')->get();
        }

        return $this->model->onlyTrashed()->with('product', 'image')->get();
    }

    public function checkInventoryExist($productId)
    {
        return $this->model->mine()->where('product_id', $productId)->first();
    }

    public function store(Request $request)
    {
        $inventory = parent::store($request);

        $this->setAttributes($inventory, $request->input('variants'));

        if ($request->input('packaging_list')) {
            $inventory->packagings()->sync($request->input('packaging_list'));
        }

        if ($request->input('tag_list')) {
            $inventory->syncTags($inventory, $request->input('tag_list'));
        }

        if ($request->hasFile('image')) {
            $inventory->saveImage($request->file('image'));
        }

        return $inventory;
    }

    public function storeWithVariant(Request $request)
    {
        $product = json_decode($request->input('product'));

        // Common informations
        $commonInfo['user_id'] = $request->user()->id; //Set user_id

        $commonInfo['shop_id'] = $request->user()->merchantId(); //Set shop_id

        $commonInfo['title'] = $request->has('title') ? $request->input('title') : $product->name;

        $commonInfo['product_id'] = $product->id;

        $commonInfo['brand'] = $product->brand;

        $commonInfo['warehouse_id'] = $request->input('warehouse_id');

        $commonInfo['supplier_id'] = $request->input('supplier_id');

        $commonInfo['shipping_width'] = $request->input('shipping_width');

        $commonInfo['shipping_height'] = $request->input('shipping_height');

        $commonInfo['shipping_depth'] = $request->input('shipping_depth');

        $commonInfo['shipping_weight'] = $request->input('shipping_weight');

        $commonInfo['available_from'] = $request->input('available_from');

        $commonInfo['active'] = $request->input('active');

        $commonInfo['tax_id'] = $request->input('tax_id');

        $commonInfo['min_order_quantity'] = $request->input('min_order_quantity');

        $commonInfo['alert_quantity'] = $request->input('alert_quantity');

        $commonInfo['description'] = $request->input('description');

        $commonInfo['condition_note'] = $request->input('condition_note');

        $commonInfo['key_features'] = $request->input('key_features');

        $commonInfo['linked_items'] = $request->input('linked_items');

        $commonInfo['meta_title'] = $request->input('meta_title');

        $commonInfo['meta_description'] = $request->input('meta_description');

        // Arrays
        $skus = $request->input('sku');

        $conditions = $request->input('condition');

        $stock_quantities = $request->input('stock_quantity');

        $purchase_prices = $request->input('purchase_price');

        $sale_prices = $request->input('sale_price');

        $offer_prices = $request->input('offer_price');

        $images = $request->file('image');

        // Relations
        $packaging_lists = $request->input('packaging_list');

        $tag_lists = $request->input('tag_list');

        $variants = $request->input('variants');

        //Preparing data and insert records.
        $dynamicInfo = [];
        foreach ($skus as $key => $sku) {
            $dynamicInfo['sku'] = $skus[$key];

            if (config('system_settings.show_item_conditions')) {
                $dynamicInfo['condition'] = $conditions[$key];
            }

            $dynamicInfo['stock_quantity'] = $stock_quantities[$key];

            $dynamicInfo['purchase_price'] = $purchase_prices[$key];

            $dynamicInfo['sale_price'] = $sale_prices[$key];

            $dynamicInfo['offer_price'] = ($offer_prices[$key]) ? $offer_prices[$key] : null;

            $dynamicInfo['offer_start'] = ($offer_prices[$key]) ? $request->input('offer_start') : null;

            $dynamicInfo['offer_end'] = ($offer_prices[$key]) ? $request->input('offer_end') : null;

            $dynamicInfo['slug'] = Str::slug($request->input('slug') . ' ' . $sku, '-');

            // Merge the common info and dynamic info to data array
            $data = array_merge($dynamicInfo, $commonInfo);

            // Insert the record
            $inventory = Inventory::create($data);

            // Sync Attributes
            if ($variants[$key]) {
                $this->setAttributes($inventory, $variants[$key]);
            }

            // Sync packaging
            if ($packaging_lists) {
                $inventory->packagings()->sync($packaging_lists);
            }

            // Sync tags
            if ($tag_lists) {
                $inventory->syncTags($inventory, $tag_lists);
            }

            // Save Images
            if (isset($images[$key])) {
                $inventory->saveImage($images[$key]);
            }
        }

        return true;
    }

    public function updateQtt(Request $request, $id)
    {
        $inventory = parent::find($id);

        $inventory->stock_quantity = $request->input('stock_quantity');

        return $inventory->save();
    }

    public function update(Request $request, $id)
    {
        $inventory = parent::update($request, $id);

        $this->setAttributes($inventory, $request->input('variants'));

        $inventory->packagings()->sync($request->input('packaging_list', []));

        $inventory->syncTags($inventory, $request->input('tag_list', []));

        if ($request->hasFile('image') || ($request->input('delete_image') == 1)) {
            $inventory->deleteImage();
        }

        if ($request->hasFile('image')) {
            $inventory->saveImage($request->file('image'));
        }

        return $inventory;
    }

    public function destroy($inventory)
    {
        if (!$inventory instanceof Inventory) {
            $inventory = parent::findTrash($inventory);
        }

        $inventory->detachTags($inventory->id, 'inventory');

        $inventory->flushImages();

        return $inventory->forceDelete();
    }

    public function massDestroy($ids)
    {
        $inventories = $this->model->withTrashed()->whereIn('id', $ids)->get();

        foreach ($inventories as $inventory) {
            $inventory->detachTags($inventory->id, 'inventory');
            $inventory->flushImages();
        }

        return parent::massDestroy($ids);
    }

    public function emptyTrash()
    {
        $inventories = $this->model->onlyTrashed()->get();

        foreach ($inventories as $inventory) {
            $inventory->detachTags($inventory->id, 'inventory');
            $inventory->flushImages();
        }

        return parent::emptyTrash();
    }

    public function findProduct($id)
    {
        return Product::findOrFail($id);
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

    public function getAttributeList(array $variants)
    {
        return Attribute::find($variants)->pluck('name', 'id');
    }

    /**
     * Check the list of attribute values and add new if need
     * @param  [type] $attribute
     * @param  array  $values
     * @return array
     */
    public function confirmAttributes($attributeWithValues)
    {
        $results = [];

        foreach ($attributeWithValues as $attribute => $values) {
            foreach ($values as $value) {
                $oldValueId = AttributeValue::find($value);

                $oldValueName = AttributeValue::where('value', $value)->where('attribute_id', $attribute)->first();

                if ($oldValueId || $oldValueName) {
                    $results[$attribute][($oldValueId) ? $oldValueId->id : $oldValueName->id] = ($oldValueId) ? $oldValueId->value : $oldValueName->value;
                } else {
                    // if the value not numeric thats meaninig that its new value and we need to create it
                    $newID = AttributeValue::insertGetId(['attribute_id' => $attribute, 'value' => $value]);

                    $newAttrValue = AttributeValue::find($newID);

                    $results[$attribute][$newAttrValue->id] = $newAttrValue->value;
                }
            }
        }

        return $results;
    }
}
