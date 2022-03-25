<?php

namespace App\Common;

use Illuminate\Support\Str;

/**
 * Attach this Trait to a User has ApiAuthTokens
 *
 * @author Munna Khan
 */
trait ApiAuthTokens
{
    public function generateToken()
    {
        $token = Str::random(60);

        $this->api_token = $token;
        // $this->api_token = hash('sha256', $token);
        $this->save();

        return $token;
    }
}
