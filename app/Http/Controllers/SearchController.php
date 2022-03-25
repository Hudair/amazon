<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Inventory;
use App\Models\Message;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;
// use Response;

class SearchController extends Controller
{
    public function findProduct(Request $request)
    {
        $term = $request->input('q');

        $results = [];

        if (strlen($term) < 3) {
            return Response::json($results);
        }

        $query = Product::search($term)->where('active', 1);

        if (!$request->user()->shop->canUseSystemCatalog()) {
            $query->where('shop_id', $request->user()->merchantId());
        }

        $products = $query->take(10)->get();

        $results = '';

        foreach ($products as $product) {
            $results .= View::make('admin.inventory._product_list', ['product' => $product])->render();
        }

        if ($results != '') {
            return $results;
        }

        return '<p class="lead"><span class="indent50">' . trans('responses.no_product_found_for_inventory') . '</span></p>';
    }

    public function findCustomer(Request $request)
    {
        $term = $request->input('q');

        $results = [];

        if (strlen($term) < 3) {
            return Response::json($results);
        }

        $customers = Customer::search($term)->where('active', 1)->take(5)->get();

        foreach ($customers as $customer) {
            $results[] = ['text' => get_formated_cutomer_str($customer), 'id' => $customer->id];
        }

        return Response::json($results);
    }

    public function findMessage(Request $request)
    {
        $search_q = $request->input('q');

        $messages = Message::where('subject', 'LIKE', '%' . $search_q . '%')
            ->orWhere('message', 'LIKE', '%' . $search_q . '%')
            ->orWhereHas('customer', function ($query) use ($search_q) {
                $query->where('email', 'LIKE', '%' . $search_q . '%')
                    ->orWhere('nice_name', 'LIKE', '%' . $search_q . '%')
                    ->orWhere('name', 'LIKE', '%' . $search_q . '%');
            })
            ->with('customer')->withCount('replies')
            ->paginate(config('system_settings.pagination'));

        return view('admin.message.index', compact('messages', 'search_q'));
    }

    public function findMerchant(Request $request)
    {
        $term = $request->input('q');

        $results = [];

        if (strlen($term) < 3) {
            return Response::json($results);
        }

        $merchants = DB::table('shops')
            ->join('users', 'users.id', 'shops.owner_id')
            ->select('users.name as user', 'shops.id', 'shops.name as shop', 'shops.email')
            ->where(function ($query) use ($term) {
                $query->where('shops.name', 'LIKE', '%' . $term . '%');
                $query->orWhere('shops.legal_name', 'LIKE', '%' . $term . '%');
            })
            ->where('shops.active', 1)
            ->take(5)
            ->get();

        foreach ($merchants as $merchant) {
            $results[] = ['text' => $merchant->user . ' | ' . $merchant->shop . ' | ' . $merchant->email, 'id' => $merchant->id];
        }

        return Response::json($results);
    }

    public function findProductForSelect(Request $request)
    {
        $term = $request->input('q');

        $results = [];

        if (strlen($term) < 3) {
            return Response::json($results);
        }

        $products = Product::search($term)->where('active', 1)->take(5)->get();

        foreach ($products as $product) {
            $results[] = ['text' => $product->name . ' | ' . $product->model_number . ' | ' . $product->brand, 'id' => $product->id];
        }

        return Response::json($results);
    }

    public function findInventoryForSelect(Request $request)
    {
        $term = $request->input('q');

        $results = [];

        if (strlen($term) < 3) {
            return Response::json($results);
        }

        $items = Inventory::search($term)->where('active', 1)->take(5)->get();

        foreach ($items as $item) {
            $results[] = ['text' => $item->title . ' | ' . $item->sku . ' | ' . get_formated_currency($item->current_sale_price()), 'id' => $item->id];
        }

        return Response::json($results);
    }
}
