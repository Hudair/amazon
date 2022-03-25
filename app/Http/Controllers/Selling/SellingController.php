<?php

namespace App\Http\Controllers\Selling;

use App\Models\FaqTopic;
use App\Http\Controllers\Controller;
use App\Models\SubscriptionPlan;
use Illuminate\Http\Request;

class SellingController extends Controller
{
    /**
     * Show the application dashboard.
     *
     * @return Response
     */
    public function index()
    {
        $faqTopics = FaqTopic::merchant()->with('faqs')->get();
        $subscription_plans = SubscriptionPlan::orderBy('order', 'asc')->get();

        return view('index', compact('subscription_plans', 'faqTopics'));
    }
}
