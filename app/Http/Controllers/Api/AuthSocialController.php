<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\SocialiteBaseController;
use App\Http\Requests\Validations\SpcialLoginRequest;
use App\Http\Resources\CustomerResource;
use Illuminate\Support\Facades\Log;

class AuthSocialController extends SocialiteBaseController
{
  /**
   * Social auth request handler.
   *
   * @return \Illuminate\Http\Response
   */
  public function socialLgin(SpcialLoginRequest $request, $provider)
  {
    try {
      $socialUser = $this->getSocialUser($provider, $request->get('access_token'));
    } catch (\GuzzleHttp\Exception\ClientException $e) {
      Log::info($e);

      $response = json_decode($e->getResponse()->getBody()->getContents(), true);

      return response()->json([
        'message'   => trans('api.auth_failed'),
        'errors'    => $response['error'] ?? 'Error',
        'error_description'    => $response['error_description'] ?? '',
      ], 401);
    }

    $customer = $this->getLocalUser($socialUser);

    $customer->generateToken();

    return new CustomerResource($customer);
  }
}
