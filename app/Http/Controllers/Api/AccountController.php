<?php

namespace App\Http\Controllers\Api;

use App\Models\Customer;
// use App\Address;
use App\Events\Customer\CustomerProfileUpdated;
// use App\Helpers\ListHelper;
use App\Events\Customer\PasswordUpdated;
use App\Http\Controllers\Controller;
// use App\Http\Resources\WishlistResource;
use App\Http\Requests\Validations\SelfPasswordUpdateRequest;
// use App\Http\Resources\OrderResource;
// use App\Http\Resources\OrderCollection;
// use App\Http\Resources\DisputeResource;
// use App\Http\Resources\DisputeCollection;
use App\Http\Resources\CouponResource;
use App\Http\Resources\CustomerResource;
use App\Http\Resources\DashboardResource;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class AccountController extends Controller
{
    /**
     * Show the customer dashboard.
     *
     * @return Response
     */
    public function index()
    {
        $customer = Customer::where('id', Auth::guard('api')->user()->id)->with('image')
            ->withCount([
                'orders', 'wishlists',
                'disputes' => function ($query) {
                    $query->open();
                },
                'coupons' => function ($query) {
                    $query->active();
                },
            ])->first();

        return new DashboardResource($customer);
    }

    /**
     * Return edit info
     * @return collection
     */
    public function edit()
    {
        $customer = Customer::where('id', Auth::guard('api')->user()->id)
            ->with('image')->first();

        return new CustomerResource($customer);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        if (
            (config('app.demo') == true) &&
            (Auth::guard('api')->user()->id <= config('system.demo.customers', 1))
        ) {
            return ['warning' => trans('messages.demo_restriction')];
        }

        $customer = Customer::where('id', Auth::guard('api')->user()->id)->first();

        $customer->update($request->all());

        if ($request->hasFile('avatar') || $request->has('delete_avatar')) {
            $customer->deleteImage();
        }

        if ($request->hasFile('avatar')) {
            $customer->saveImage($request->file('avatar'));
        }

        event(new CustomerProfileUpdated($customer));

        return new CustomerResource($customer);
    }

    /**
     * Return coupons
     * @return collection
     */
    public function coupons()
    {
        $coupons = Auth::guard('api')->user()->coupons()
            ->with('shop')->paginate(10);

        return CouponResource::collection($coupons);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function password_update(SelfPasswordUpdateRequest $request)
    {
        if (
            config('app.demo') == true &&
            Auth::guard('api')->user()->id <= config('system.demo.customers', 1)
        ) {
            return response()->json(['message' => trans('messages.demo_restriction')], 400);
        }

        $customer = Customer::where('id', Auth::guard('api')->user()->id)->first();

        $customer->update($request->all());

        event(new PasswordUpdated(Auth::user()));

        return new CustomerResource($customer);
    }
}
