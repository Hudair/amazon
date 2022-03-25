<?php

namespace App\Http\Controllers\Admin;

use App\Common\Authorizable;
use App\Models\Currency;
use App\Http\Controllers\Controller;
use App\Http\Requests\Validations\CreateCurrencyRequest;
use App\Http\Requests\Validations\UpdateCurrencyRequest;
use Illuminate\Http\Request;

class CurrencyController extends Controller
{
    use Authorizable;

    private $model;

    /**
     * construct
     */
    public function __construct()
    {
        parent::__construct();
        $this->model = trans('app.model.currency');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $currencies = Currency::orderBy('active', 'desc')->orderBy('priority', 'asc')->get();

        return view('admin.currency.index', compact('currencies'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.currency._create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreateCurrencyRequest $request)
    {
        Currency::create($request->all());

        return back()->with('success', trans('messages.created', ['model' => $this->model]));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Currency  $currency
     * @return \Illuminate\Http\Response
     */
    public function edit(Currency $currency)
    {
        return view('admin.currency._edit', compact('currency'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Currency  $currency
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateCurrencyRequest $request, Currency $currency)
    {
        $currency->update($request->all());

        return back()->with('success', trans('messages.updated', ['model' => $this->model]));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Currency  $currency
     * @return \Illuminate\Http\Response
     */
    public function destroy(Currency $currency)
    {
        $currency->delete();

        return back()->with('success', trans('messages.deleted', ['model' => $this->model]));
    }

    /**
     * Trash the mass resources.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function massDestroy(Request $request)
    {
        Currency::whereIn('id', $request->ids)->forceDelete();

        if ($request->ajax()) {
            return response()->json(['success' => trans('messages.deleted', ['model' => $this->model])]);
        }

        return back()->with('success', trans('messages.deleted', ['model' => $this->model]));
    }
}
