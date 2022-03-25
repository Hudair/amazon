<?php

namespace App\Helpers;

use Symfony\Component\HttpFoundation\Response;

trait ApiAlert
{
    public function success(string $message = null)
    {
        return response()->json([
            'isSuccess' =>  true,
            'message'   => $message
        ], 200);
    }

    public function error(string $message = null)
    {
        return response()->json([
            'isSuccess' =>  false,
            'message'   => $message
        ], Response::HTTP_UNPROCESSABLE_ENTITY);
    }
}
