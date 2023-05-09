<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use App\Models\User;
use App\Models\Referral;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use App\Http\Requests\UserCreationRequest;

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
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
          //  'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\Models\User
     */
    protected function create(array $data)
    {
        $newUser =  User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'referral_code' => $this->getCode(),
            'password' => Hash::make('123456'),
        ]);

        if (!empty($data['referralCode'])) {
            $refferingUser = User::where('referral_code', $data['referralCode'])->select('id', 'name')->first();
            if (!empty($refferingUser)) {
                Referral::create([
                    'user_id' => $newUser->id,
                    'referred_by' => $refferingUser->id,
                ]);
            }

        }
    }

    protected function getCode()
    {
        return $this->generateReferralCode();
    }

    protected function generateReferralCode()
    {
        return Str::random(6);
    }

    public function store(UserCreationRequest $request)
    {
        $newUser =  User::create([
            'name' => $request->get('name'),
            'email' => $request->get('email'),
            'referral_code' => $this->getCode(),
            'password' => Hash::make('123456'),
        ]);

        if (!empty($request->get('referralCode'))) {
            $refferingUser = User::where('referral_code', $request->get('referralCode'))->select('id', 'name')->first();
            if (!empty($refferingUser)) {
                Referral::create([
                    'user_id' => $newUser->id,
                    'referred_by' => $refferingUser->id,
                ]);

                $point = Referral::where('referred_by', $refferingUser->id)->count();
                User::where('id', $refferingUser->id)->update(['point' => $point]);
            }

        }
    }
}
