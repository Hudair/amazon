<?php

namespace App\Http\Controllers\Admin;

// use App\Common\Authorizable;
use App\Models\ConfigCyberSource;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class ConfigCyberSourceController extends Controller
{
    // use Authorizable;

    private $model_name;

    /**
     * construct
     */
    public function __construct()
    {
        parent::__construct();

        $this->model_name = trans('app.model.payment_method');
    }

    /**
     * Activate the Paypal Express checkout gateway
     *
     * @return \Illuminate\Http\Response
     */
    public function activate()
    {
        if (config('app.demo') == true) {
            return view('demo_modal');
        }

        $cybersource = ConfigCyberSource::firstOrCreate(['shop_id' => Auth::user()->merchantId()]);

        return view('admin.config.payment-method.cybersource', compact('cybersource'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $cybersource = ConfigCyberSource::firstOrCreate(['shop_id' => Auth::user()->merchantId()]);

        $cybersource->update($request->all());

        return back()->with('success', trans('messages.created', ['model' => $this->model_name]));
    }

    /**
     * Deactivate the Paypal Express checkout gateway
     *
     * @return \Illuminate\Http\Response
     */
    public function deactivate()
    {
        $cybersource = ConfigCyberSource::firstOrCreate(['shop_id' => Auth::user()->merchantId()]);

        $cybersource->delete();

        return back()->with('success', trans('messages.updated', ['model' => $this->model_name]));
    }
}
