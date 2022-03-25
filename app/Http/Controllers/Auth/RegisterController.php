<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use App\Models\System;
use App\Events\Shop\ShopCreated;
use App\Http\Controllers\Controller;
use App\Http\Requests\Validations\RegisterMerchantRequest;
use App\Jobs\CreateShopForMerchant;
use App\Jobs\SubscribeShopToNewPlan;
use App\Notifications\Auth\SendVerificationEmail as EmailVerificationNotification;
use App\Notifications\SuperAdmin\VerdorRegistered as VerdorRegisteredNotification;
use App\Providers\RouteServiceProvider;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Auth\Events\Registered;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\MessageBag;
use Illuminate\Support\Str;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::DASHBOARD;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('verify');
    }

    /**
     * Show the application registration form.
     *
     * @param  string  $plan subscription plan
     * @return \Illuminate\Http\Response
     */
    public function showRegistrationForm($plan = null)
    {
        return view('auth.register', compact('plan'));
    }

    /**
     * Handle a registration request for the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function register(RegisterMerchantRequest $request)
    {
        // Start transaction!
        DB::beginTransaction();

        try {
            $merchant = $this->create($request->all());

            // Dispatching Shop create job
            CreateShopForMerchant::dispatch($merchant, $request->all());

            Auth::guard()->login($merchant);

            if (is_subscription_enabled()) {
                SubscribeShopToNewPlan::dispatch($merchant, $request->input('plan'));
            }
        } catch (\Exception $e) {

            // rollback the transaction and log the error
            DB::rollback();
            Log::error('Vendor Registration Failed: ' . $e->getMessage());

            // Set error messages:
            $error = new MessageBag();
            $error->add('errors', trans('responses.vendor_config_failed'));

            return redirect()->route('vendor.register')->withErrors($error)->withInput();
        }

        // Everything is fine. Now commit the transaction
        DB::commit();

        // Trigger after registration events
        $this->triggerAfterEvents($merchant);

        // Send notification to Admin
        if (config('system_settings.notify_when_vendor_registered')) {
            $system = System::orderBy('id', 'asc')->first();
            $system->superAdmin()->notify(new VerdorRegisteredNotification($merchant));
        }

        return $this->registered($request, $merchant) ?: redirect($this->redirectPath());
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\Models\User
     */
    protected function create(array $data)
    {
        return User::create([
            'email' => $data['email'],
            'password' => bcrypt($data['password']),
            'verification_token' => Str::random(40),
        ]);
    }

    /**
     * Trigger some events after a valid registration.
     *
     * @param  User  $merchant
     * @return void
     */
    protected function triggerAfterEvents(User $merchant)
    {
        // Trigger the systems default event
        event(new Registered($merchant));

        // Trigger shop created event
        event(new ShopCreated($merchant->owns));

        // Send email verification notification
        $merchant->notify(new EmailVerificationNotification($merchant));
    }

    /**
     * Verify the User the given token.
     *
     * @param  string|null  $token
     * @return \Illuminate\Http\Response
     */
    public function verify($token = null)
    {
        if (!$token) {
            $user = Auth::user();

            $user->verification_token = Str::random(40);

            if ($user->save()) {
                $user->notify(new EmailVerificationNotification($user));

                return redirect()->back()->with('success', trans('auth.verification_link_sent'));
            }

            return redirect()->back()->with('success', trans('auth.verification_link_sent'));
        }

        $user = User::where('verification_token', $token)->first();

        if (!$user) {
            return redirect()->route('admin.admin.dashboard')->with('success', trans('auth.invalid_token'));
        }

        $user->verification_token = null;

        if ($user->save()) {
            return redirect()->route('admin.admin.dashboard')->with('success', trans('auth.verification_successful'));
        }

        return redirect()->route('admin.admin.dashboard')->with('error', trans('auth.verification_failed'));
    }

    /**
     * The user has been registered.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  mixed  $user
     * @return mixed
     */
    protected function registered($request, $user)
    {
        //
    }
}
