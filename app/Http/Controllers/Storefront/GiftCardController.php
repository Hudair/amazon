<?php

namespace App\Http\Controllers\Storefront;

use App\Models\GiftCard;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class GiftCardController extends Controller
{
    /**
     * Show the application dashboard.
     *
     * @return Response
     */
    public function index()
    {
        $giftCards = GiftCard::all();

        return view('theme::gift_cards', compact('giftCards'));
    }
}
