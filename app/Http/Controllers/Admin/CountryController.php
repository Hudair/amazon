<?php

namespace App\Http\Controllers\Admin;

use App\Models\Country;
use App\Common\Authorizable;
use App\Http\Controllers\Controller;
use App\Http\Requests\Validations\CreateCountryRequest;
use App\Http\Requests\Validations\UpdateCountryRequest;
use Illuminate\Support\Facades\Cache;

class CountryController extends Controller
{
    use Authorizable;

    private $model;

    /**
     * construct
     */
    public function __construct()
    {
        parent::__construct();
        $this->model = trans('app.country');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $countries = Country::orderBy('active', 'desc')
            ->orderBy('name', 'asc')->withCount('states')->get();

        return view('admin.country.index', compact('countries'));
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function states(Country $country)
    {
        return view('admin.country.states', compact('country'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.country._create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreateCountryRequest $request)
    {
        Country::create($request->all());

        // Clear countries from cache
        Cache::forget('countries_cached');

        return back()->with('success', trans('messages.created', ['model' => $this->model]));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Country  $country
     * @return \Illuminate\Http\Response
     */
    public function edit(Country $country)
    {
        return view('admin.country._edit', compact('country'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Country  $country
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateCountryRequest $request, Country $country)
    {
        if (
            ($country->iso_numeric && $request->has('iso_numeric')) ||
            ($country->iso_code && $request->has('iso_code'))
        ) {
            return back()->with('error', trans('response.invalid_data'));
        }

        $country->update($request->all());

        // Clear countries from cache
        Cache::forget('countries_cached');

        return back()->with('success', trans('messages.updated', ['model' => $this->model]));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Country  $country
     * @return \Illuminate\Http\Response
     */
    // public function destroy(Country $country)
    // {
    //     $country->delete();

    //     return back()->with('success',  trans('messages.deleted', ['model' => $this->model]));
    // }

    /**
     * Trash the mass resources.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    // public function massDestroy(Request $request)
    // {
    //     \Log::info($request->ids);

    //     Country::whereIn('id', $request->ids)->delete();

    //     if ($request->ajax()) {
    //         return response()->json(['success' => trans('messages.deleted', ['model' => $this->model])]);
    //     }

    //     return back()->with('success', trans('messages.deleted', ['model' => $this->model]));
    // }
}
