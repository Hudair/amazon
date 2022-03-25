<?php

namespace App\Observers;

use App\Notifications\ShopCreated;
use App\Models\Shop;
use App\Models\User;

class ShopObserver
{
    /**
     * Listen to the Shop created event.
     *
     * @param  \App\Models\Shop  $shop
     * @return void
     */
    public function created(Shop $shop)
    {
        // $user = User::find($shop->owner_id);
        // $user->shop_id = $shop->id;
        // $user->save();

        // $user->notify(new ShopCreated($shop));
    }

    /**
     * Listen to the Shop deleting event.
     *
     * @param  \App\Models\Shop  $shop
     * @return void
     */
    public function deleting(Shop $shop)
    {
        $shop->owner()->delete();
        $shop->staffs()->delete();
    }

    /**
     * Listen to the Shop restored event.
     *
     * @param  \App\Models\Shop  $shop
     * @return void
     */
    public function restored(Shop $shop)
    {
        $shop->owner()->restore();
        $shop->staffs()->restore();
    }
}
