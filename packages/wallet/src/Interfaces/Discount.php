<?php

namespace Incevio\Package\Wallet\Interfaces;

interface Discount extends Product
{
    /**
     * @param Customer $customer
     * @return int|float
     */
    public function getPersonalDiscount(Customer $customer);
}
