<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PackageController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return \Illuminate\Http\Response
     */
    public function isLoaded(Request $request, $slug)
    {
        return response()->json([
            'data' => (bool) is_incevio_package_loaded($slug)
        ]);
    }
}
