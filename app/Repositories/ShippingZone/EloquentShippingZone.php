<?php

namespace App\Repositories\ShippingZone;

use App\Helpers\ListHelper;
use App\Repositories\BaseRepository;
use App\Repositories\EloquentRepository;
use App\Models\ShippingZone;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;

class EloquentShippingZone extends EloquentRepository implements BaseRepository, ShippingZoneRepository
{
    protected $model;

    public function __construct(ShippingZone $shipping_zone)
    {
        $this->model = $shipping_zone;
    }

    public function all()
    {
        return $this->model->mine()->with('rates')->get();
    }

    public function update(Request $request, $id)
    {
        $zone = $this->model->findOrFail($id);

        if ($request->has('rest_of_the_world') && $request->input('rest_of_the_world') == 1) {
            $request->merge(['state_ids' => [], 'country_ids' => []]);
        } else {
            $state_ids = [];
            if ($request->has('country_ids')) {
                $country_ids = $request->input('country_ids');
                $old_country_ids = $zone->country_ids; //Current values

                $kept_country_ids = array_intersect($old_country_ids, $country_ids); //Unchanged countries
                $temp_states = get_states_of($kept_country_ids); //All states of unchanged countries
                $kept_state_ids = array_intersect($zone->state_ids, array_keys($temp_states)); //States what will keep unchange

                $new_country_ids = array_diff($country_ids, $old_country_ids); //If there is new countries
                $new_state_ids = get_states_of($new_country_ids); //States of new countries

                $state_ids = array_merge($kept_state_ids, array_keys($new_state_ids)); //Creating new and updated values
            }

            $request->merge(['state_ids' => $state_ids]);
        }

        $zone->update($request->all());

        return $zone;
    }

    public function updateStates(Request $request, $id, $country)
    {
        $shop_id = $request->user()->merchantId(); //Get current user's shop_id

        // State ids that are already in other shipping zones
        $allZones = ShippingZone::select('id', 'state_ids')->where('shop_id', $shop_id)->get();
        $zone = $allZones->where('id', $id)->first();
        $otherZones = $allZones->where('id', '!=', $id)->all();

        $otherZone_stateIds = Arr::flatten(array_filter(collect($otherZones)->pluck('state_ids')->toArray()));
        $new_stateIds = $request->input('states');
        $valid_newIds = array_diff($new_stateIds, $otherZone_stateIds);

        //Remove all state ids of the country and keep the other country states
        if (!empty($valid_newIds)) {
            $states = \DB::table('states')->where('country_id', $country)->pluck('id')->toArray();
            $kept = array_diff($zone->state_ids, $states);
            $valid_newIds = empty($kept) ? $valid_newIds : array_merge($valid_newIds, $kept);
        }

        //Creating new and updated values
        $zone->state_ids = $valid_newIds;
        $zone->save();

        return $zone;
    }

    public function removeCountry(Request $request, $id, $country)
    {
        $zone = $this->model->findOrFail($id);

        //Remove state ids of the country
        $old_states = $zone->state_ids;
        $states = get_states_of($country, true);
        $state_ids = array_diff($old_states, array_keys($states));

        //Remove country id
        $country_ids = $zone->country_ids;
        $find = array_search($country, $country_ids);
        unset($country_ids[$find]);

        //Save the new values
        $zone->country_ids = $country_ids;
        $zone->state_ids = $state_ids;
        $zone->save();

        return $zone;
    }

    public function destroy($id)
    {
        return $this->model->findOrFail($id)->forceDelete();
    }
}
