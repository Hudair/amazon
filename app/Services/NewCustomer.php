<?php

namespace App\Services;

use App\Models\Customer;
use App\Notifications\Auth\SendVerificationEmail as EmailVerificationNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class NewCustomer
{
    public function save(Request $request)
    {
        $customer = Customer::create([
            'name' => $request->address_title ?? 'Customer Name',
            'email' => $request->email,
            'password' => $request->password,
            'accepts_marketing' => $request->accepts_marketing,
            'verification_token' => Str::random(40),
            'active' => 1,
        ]);

        // Sent email address verification notich to customer
        $customer->notify(new EmailVerificationNotification($customer));

        $customer->addresses()->create($request->all()); // Save address

        return $customer;
    }
}
