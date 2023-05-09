<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Referral;
 
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use App\Http\Requests\UserCreationRequest;

class UserRegisterController extends Controller
{
	public function index()
	{
		return view('auth.user-register');
	}

	public function registerSuccess()
	{
		return view('auth.register-success');
	}

	public function store(UserCreationRequest $request)
    {
    	$referralCode = $this->getCode();
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

        return redirect()->route('register-success')->with('message', 'Your Referral Code is '. $referralCode);
    }

    protected function getCode()
    {
        return $this->generateReferralCode();
    }

    protected function generateReferralCode()
    {
        return Str::random(6);
    }

}
