<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use App\User;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

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
    protected $redirectTo = '/account/subscribe';//RouteServiceProvider::HOME;
    //'/home';

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
        $rule=[
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'phone' => 'digits_between:10,10|unique:users,phone',
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ];

        $messages = [
            'required' => 'The :attribute field is required.',
            'email' => 'Please enter a valid :attribute.',
            'max' => 'Maximum limit for :attribute reached.',
            'password.min' => 'Password required atleast 8 character.',
            'unique' => 'Email address already exist.',
            'confirmed' => 'Your confirmation password not same.',
            'phone' => 'Please enter a valid mobile number.',
            'phone.unique' => 'Mobile number is already linked to another account.',
            'phone.digits_between' => 'Please enter the 10 digit mobile number.'
        ];
        return Validator::make($data, $rule,$messages);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\User
     */
    protected function create(array $data)
    {
        return User::create([
            'name' => $data['name'],
            'firstname' => $data['name'],
            'email' => $data['email'],
            'phone' => $data['phone'],
            'password' => Hash::make($data['password']),
        ]);

        $userRole = config('roles.models.role')::where('name', '=', 'User')->first();
        $user->attachRole($userRole);


        // $user=User::find($user->id);
        // $user->title=$data['title'];
        // $user->firstname=$data['firstname'];
        // $user->middlename=$data['middlename'];
        // $user->lastname=$data['lastname'];
        // $user->phone=empty($data['phone'])?'':$data['phone'];
        // $user->save();

        
        return $user;

    }
}
