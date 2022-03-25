<?php

namespace App\Http\Controllers\Storefront;

use App\Models\Address;
use App\Models\Customer;
use App\Models\Wishlist;
use App\Helpers\ListHelper;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
// use App\Events\Profile\ProfileUpdated;
// use App\Events\Profile\PasswordUpdated;
use Illuminate\Database\Eloquent\Builder;
use App\Http\Requests\Validations\CreateAddressRequest;
use App\Http\Requests\Validations\SelfAvatarUpdateRequest;
use App\Http\Requests\Validations\SelfAddressDeleteRequest;
use App\Http\Requests\Validations\SelfAddressUpdateRequest;
use App\Http\Requests\Validations\SelfPasswordUpdateRequest;

class AccountController extends Controller
{
    /**
     * Show the customer dashboard.
     *
     * @return Response
     */
    public function index($tab = 'dashboard')
    {
        if (!method_exists($this, $tab)) {
            abort(404);
        }

        // Call the methods dynamically to load needed models
        $$tab = $this->$tab();


        return view('theme::dashboard', compact('tab', $tab));
    }

    /**
     * Load dashboard content
     * @return mix
     */
    private function dashboard()
    {
        return Customer::where('id', Auth::guard('customer')->user()->id)
            ->with([
                'wishlists' => function ($query) {
                    $query->with('inventory:id,slug,title', 'inventory.images')->latest()->take(5);
                },
                'orders' => function ($query) {
                    $query->select(['id', 'customer_id', 'shop_id', 'order_number', 'item_count', 'grand_total', 'order_status_id', 'created_at'])
                        ->with(['shop:id,slug,name', 'shop.image'])->latest()->take(5);
                },
            ])
            ->withCount([
                'orders', 'wishlists',
                'messages' => function ($query) {
                    $query->unread();
                },
                'disputes' => function ($query) {
                    $query->open();
                },
                'coupons',
            ])->first();
    }

    /**
     * Return orders
     * @return collection
     */
    private function orders()
    {   
        $orders = Auth::guard('customer')->user()->orders()
                    ->when(request()->q,function(Builder $query){
                        $query->where('order_number','like','%' . request()->q . '%');
                    })
                    ->with([
                        'shop:id,name,slug',
                        'inventories:id,title,slug,product_id',
                        'inventories.image:path,imageable_id,imageable_type',
                    ])
                    ->paginate(10);
 
        return $orders;
    }

    /**
     * Return inbox
     * @return collection
     */
    private function messages()
    {
        return Auth::guard('customer')->user()->messages()
            ->with(['shop:id,name,slug', 'item:id,slug,sku', 'order:id,order_number'])
            ->withCount('replies')->paginate(10);
    }

    /**
     * Return wishlist
     * @return collection
     */
    private function wishlist()
    {
        return Wishlist::mine()->whereHas('inventory', function ($q) {
            $q->available();
        })->with([
            'inventory',
            'inventory.feedbacks:rating,feedbackable_id,feedbackable_type',
            'inventory.images:path,imageable_id,imageable_type',
        ])->paginate(10);
    }

    /**
     * Return disputes
     * @return collection
     */
    private function disputes()
    {
        return Auth::guard('customer')->user()->disputes()
            ->with([
                'shop:id,name,slug',
                'order.inventories:id,product_id,slug',
                'order.inventories.product:id,slug',
                'order.inventories.image:path,imageable_id,imageable_type',
            ])
            ->paginate(10);
    }

    /**
     * Return coupons
     * @return collection
     */
    private function coupons()
    {
        return Auth::guard('customer')->user()->coupons()
            ->with('shop:id,name,slug')->paginate(20);
    }

    /**
     * Return account info
     * @return collection
     */
    private function addresses()
    {
        //Supply important data to the views
        View::share('address_types', ListHelper::address_types());
        View::share('countries', ListHelper::countries());

        return Auth::guard('customer')->user();
    }

    /**
     * Return account info
     * @return collection
     */
    private function password()
    {
        return Auth::guard('customer')->user();
    }

    /**
     * Return account info
     * @return collection
     */
    private function account()
    {
        //Supply important data to the views
        View::share('address_types', ListHelper::address_types());
        View::share('countries', ListHelper::countries());

        return Auth::guard('customer')->user();
    }

    /**
     * Return gift_cards
     * @return collection
     */
    private function gift_cards()
    {
        return Auth::guard('customer')->user()->gift_cards()->paginate(20);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        if (config('app.demo') == true && Auth::guard('customer')->user()->id <= config('system.demo.customers', 1)) {
            return redirect()->route('account', 'account#account-info-tab')
                ->with('warning', trans('messages.demo_restriction'));
        }

        $user = Auth::guard('customer')->user();
        $user->name = $request->input('name');
        $user->nice_name = $request->input('nice_name');
        $user->email = $request->input('email');
        $user->dob = $request->input('dob');
        $user->description = $request->input('description');
        $user->save();

        return redirect()->route('account', 'account#account-info-tab')
            ->with('success', trans('theme.notify.info_updated'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function password_update(SelfPasswordUpdateRequest $request)
    {
        if (config('app.demo') == true && Auth::guard('customer')->user()->id <= config('system.demo.customers', 1)) {
            return redirect()->route('account', 'account#password-tab')
                ->with('warning', trans('messages.demo_restriction'));
        }

        Auth::guard('customer')->user()->update($request->all());

        // event(new PasswordUpdated(Auth::user()));

        return redirect()->route('account', 'account#password-tab')
            ->with('success', trans('theme.notify.info_updated'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function create_address(Request $request)
    {
        $countries = ListHelper::countries(); // Country list for ship_to dropdown

        $address_types = ListHelper::address_types();

        $states = config('system_settings.address_default_state') ? ListHelper::states(config('system_settings.address_default_country')) : [];

        return view(
            'theme::modals._create_address',
            compact('countries', 'states', 'address_types')
        )->render();
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function save_address(CreateAddressRequest $request)
    {
        $address = Auth::guard('customer')->user()
            ->addresses()->create($request->all());

        return redirect()->to(url()->previous() . '?address=' . $address->id . '#address-tab')
            ->with('success', trans('theme.notify.address_created'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function address_edit(Request $request, Address $address)
    {
        $countries = ListHelper::countries(); // Country list for ship_to dropdown
        $states = $address->state_id ? ListHelper::states($address->country_id) : [];
        $address_types = ListHelper::address_types();

        return view('theme::modals._edit_address', compact('address', 'countries', 'states', 'address_types'))->render();
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function address_update(SelfAddressUpdateRequest $request, Address $address)
    {
        $address->update($request->all());

        return redirect()->route('account', 'account#address-tab')->with('success', trans('theme.notify.info_updated'));
    }

    /**
     * delete the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function address_delete(SelfAddressDeleteRequest $request, Address $address)
    {
        $address->delete();

        return redirect()->route('account', 'account#address-tab')->with('success', trans('theme.notify.address_deleted'));
    }

    public function avatar(SelfAvatarUpdateRequest $request)
    {
        Auth::guard('customer')->user()->deleteImage();

        Auth::guard('customer')->user()->saveImage($request->file('avatar'));

        return redirect()->route('account', 'account#account-info-tab')->with('success', trans('theme.notify.info_updated'));
    }

    public function delete_avatar(Request $request)
    {
        Auth::guard('customer')->user()->deleteImage();

        return redirect()->route('account', 'account#account-info-tab')->with('success', trans('theme.notify.info_deleted'));
    }
}
