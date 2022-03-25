<?php

namespace App\Http\Controllers\Api;

use App\Models\Address;
use App\Helpers\ListHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\Validations\CreateAddressRequest;
use App\Http\Requests\Validations\SelfAddressDeleteRequest;
use App\Http\Requests\Validations\SelfAddressUpdateRequest;
use App\Http\Resources\AddressResource;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class AddressController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        // $addresses = Auth::guard('api')->user()->addresses()->create($request->all());

        return AddressResource::collection(Auth::guard('api')->user()->addresses);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        return [
            'address_types' => ListHelper::address_types(),
            'countries' => ListHelper::countries(),
        ];
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreateAddressRequest $request)
    {
        Auth::guard('api')->user()->addresses()->create($request->all());

        return AddressResource::collection(Auth::guard('api')->user()->addresses);
    }

    /**
     * Update the specified resource in storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function edit(Address $address)
    {
        return (new AddressResource($address))->additional([
            'address_types' => ListHelper::address_types(),
            'countries' => ListHelper::countries(),
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update(SelfAddressUpdateRequest $request, Address $address)
    {
        $address->update($request->all());

        return AddressResource::collection(Auth::guard('api')->user()->addresses);
    }

    /**
     * delete the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function delete(SelfAddressDeleteRequest $request, Address $address)
    {
        $address->delete();

        return AddressResource::collection(Auth::guard('api')->user()->addresses);
    }
}
