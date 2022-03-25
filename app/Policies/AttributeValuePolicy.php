<?php

namespace App\Policies;

use App\Models\AttributeValue;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class AttributeValuePolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view attributeValues.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function index(User $user)
    {
        return true;
    }

    /**
     * Determine whether the user can view the attributeValue.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\AttributeValue  $attributeValue
     * @return mixed
     */
    public function view(User $user, AttributeValue $attributeValue)
    {
        return true;
    }

    /**
     * Determine whether the user can create attributeValues.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return true;
    }

    /**
     * Determine whether the user can update the attributeValue.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\AttributeValue  $attributeValue
     * @return mixed
     */
    public function update(User $user, AttributeValue $attributeValue)
    {
        return $user->isFromPlatform() || $user->shop_id == $attributeValue->shop_id;
    }

    /**
     * Determine whether the user can delete the attributeValue.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\AttributeValue  $attributeValue
     * @return mixed
     */
    public function delete(User $user, AttributeValue $attributeValue)
    {
        return $user->isFromPlatform() || $user->shop_id == $attributeValue->shop_id;
    }

    /**
     * Determine whether the user can delete the Product.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function massDelete(User $user)
    {
        return $user->isFromPlatform();
    }
}
