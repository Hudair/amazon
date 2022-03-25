<?php

namespace App\Http\Controllers\Api\Deliveryboy;

use Carbon\Carbon;
use App\Helpers\ApiAlert;
use App\Models\DeliveryBoy;
use App\Http\Controllers\Controller;
use App\Http\Resources\DeliveryBoyResource;
use App\Http\Requests\DeliveryBoy\LoginRequest;
use App\Http\Requests\DeliveryBoy\UpdatePasswordRequest;
use App\Notifications\DeliveryBoy\PasswordReset;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    use ApiAlert;

    /**
     * login
     *
     * @param  [request]
     * 
     * @return [json] logged in delivery boy details
     */

    public function login(LoginRequest $request)
    {
        $credentials = [
            'email' => $request->email,
            'password' => $request->password,
        ];

        if (Auth::guard('delivery_boy')->attempt($credentials)) {
            $deliveryboys = Auth::guard('delivery_boy')->user();
            $deliveryboys->generateToken();

            return new DeliveryBoyResource($deliveryboys);
        }

        return response()->json(['message' => trans('api.auth_failed')], 401);
    }

    /**
     * logout
     *
     * @param  [request]
     * 
     * @return [json] $string
     */

    public function logout(Request $request)
    {
        $deliveryBoy = Auth::guard('delivery_boy-api')->user();

        if ($deliveryBoy) {
            $deliveryBoy->api_token = null;
            $deliveryBoy->save();
        }

        return response()->json(trans('api.auth_out'), 200);
    }

    /**
     * update password 
     *
     * @param  [request]
     * 
     * @return [json] $string
     */

    public function updatePassword(UpdatePasswordRequest $request)
    {
        $user = Auth::guard('delivery_boy-api')->user();

        if (!Hash::check($request->get('oldpassword'), $user->password)) {
            return $this->error(trans('api.old_password_doesnt_matched'));
        }

        if (Hash::check($request->get('newpassword'), $user->password)) {
            return $this->error(trans('api.new_password_cant_be_the_old_password'));
        }

        try {
            $user->password = $request->get('newpassword');
            $user->save();
        } catch (\Exception $e) {
            return $this->error(trans('api.something_Went_wrong'));
        }

        // DeliveryBoy::where('id', Auth::guard('delivery_boy-api')->id())->update([
        //     'password' =>  Hash::make($request->get('newpassword'))
        // ]);

        return $this->success(trans('api.password_update'));
    }

    /**
     * forgot password 
     *
     * @param  [request]
     * 
     * @return [json] $string
     */

    public function forgot(Request $request)
    {
        $request->validate(['email' => 'required|email',]);

        $deliveryBoy = DeliveryBoy::where('email', $request->email)->first();

        if (!$deliveryBoy) {
            return response()->json(['message' => trans('api.email_account_not_found')], 404);
        }

        $token = generateUniqueNumber();

        $passwordReset = DB::table('password_resets')
            ->updateOrInsert(
                ['email' => $deliveryBoy->email],
                [
                    'email' => $deliveryBoy->email,
                    'token' => $token,
                    'created_at' => Carbon::now(),
                ]
            );

        if ($deliveryBoy && $passwordReset) {
            $deliveryBoy->notify(new PasswordReset($token));
        }

        return response()->json(['message' => trans('api.password_reset_email')], 201);
    }

    /**
     * Find token password reset
     *
     * @param  [string] $token
     * @return [string] message
     * @return [json] passwordReset object
     */
    public function token(Request $request)
    {
        $token = '';

        if ($request->token) {
            $token = $request->token;
        }
        $passwordReset = DB::table('password_resets')
            ->where('token', $token)->first();

        if (!$passwordReset) {
            return response()->json([
                'message' => trans('api.password_reset_token_404')
            ], 404);
        }

        if (Carbon::parse($passwordReset->created_at)->addMinutes(720)->isPast()) {
            DB::table('password_resets')->where('token', $token)->delete();

            return response()->json([
                'message' => trans('api.password_reset_token_invalid')
            ], 404);
        }

        return response()->json($passwordReset);
    }

    /**
     * Reset password
     *
     * @param  [string] password
     * @param  [string] password_confirmation
     * @param  [string] token
     * @return [string] message
     */
    public function reset(Request $request)
    {
        $request->validate([
            'password' => 'required|string|min:6',
            'token' => 'required|string',
        ]);

        $passwordReset = DB::table('password_resets')
            ->where('token', $request->token)->first();

        if (!$passwordReset) {
            return response()->json([
                'message' => trans('api.password_reset_token_404')
            ], 404);
        }

        $deliveryBoy = DeliveryBoy::where('email', $passwordReset->email)->first();
        if (!$deliveryBoy) {
            return response()->json([
                'message' => trans('api.email_account_not_found')
            ], 404);
        }

        $deliveryBoy->password = $request->password;
        $deliveryBoy->save();

        DB::table('password_resets')->where('token', $request->token)->delete();

        //$deliveryBoy->notify(new PasswordResetSuccess($customer));

        return response()->json([
            'message' => trans('api.password_reset_successful')
        ], 200);
    }
}
