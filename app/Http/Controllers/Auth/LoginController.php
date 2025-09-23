<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Session;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    
    public function backendlogin()
    {
        return view('auth.backendlogin');
    }
    
    public function sendOtp()
    {
        return view('auth.sendotp');
    }

    public function loginWithOtp()
    {
        return view('auth.loginotp');
    }

    protected function authenticated(Request $request, $user)
    {

        if ($user->isAdmin() || $user->isSubadmin() || $user->isEditor()) {
            return redirect()->route('admin.dashboard.index');
        }
        return redirect('/');
    }
}
