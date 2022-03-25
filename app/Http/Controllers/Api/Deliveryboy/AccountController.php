<?php

namespace App\Http\Controllers\Api\Deliveryboy;

use App\Models\Shop;
use App\Helpers\ApiAlert;
use App\Http\Controllers\Controller;
use App\Http\Resources\ShopLightResource;
use App\Http\Resources\DeliveryBoyResource;
use App\Http\Requests\DeliveryBoy\UpdateProfileRequest;
use App\Repositories\DeliveryBoy\DeliveryBoyRepository;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class AccountController extends Controller
{
    use ApiAlert;

    protected $model;

    private $deliveryBoy;

    /**
     * construct
     */
    public function __construct(DeliveryBoyRepository $deliveryBoy)
    {
        parent::__construct();
        $this->deliveryBoy = $deliveryBoy;
    }

    /**
     * Delivery boy profile
     * 
     * @return [delivery boy]
     */

    public function profile()
    {
        return new DeliveryBoyResource(Auth::guard('delivery_boy-api')->user());
    }

    /**
     * Delivery boy profile update
     * 
     * @return [json] $object
     */

    public function updateProfile(UpdateProfileRequest $request)
    {
        $user = Auth::guard('delivery_boy-api')->user();

        if (config('app.demo') == true && $user->id <= config('system.demo.delivery_boys')) {
            return ['warning' => trans('messages.demo_restriction')];
        }

        $user->update($request->all());

        if ($request->hasFile('avatar') || $request->has('delete_avatar')) {
            $user->deleteImage();
        }

        if ($request->has('avatar')) {
            $file = create_file_from_base64($request->get('avatar'));

            $user->saveImage($file);
        }

        return new DeliveryBoyResource($user);
    }

    /**
     * Getting vendor name
     * 
     * @return [json] $object
     */

    public function vendor()
    {
        $shop_id = auth()->guard('delivery_boy-api')->user()->shop_id;
        $shop = Shop::findOrFail($shop_id);

        return new ShopLightResource($shop);
    }
}
