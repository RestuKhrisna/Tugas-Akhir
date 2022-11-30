<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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

    public function authenticated(Request $request, $user)
    {
        if(Auth::user()->level_user == 'operator_keuangan'){
            $this->redirectTo = 'OperatorKeuangan/dashboard';
        }
        else if(Auth::user()->level_user == 'operator_legalisir'){
            $this->redirectTo = 'OperatorLegalisir/dashboard';
        }
        else if(Auth::user()->level_user == 'admin'){
            $this->redirectTo = 'Admin/dashboard';
        }
        else{
            $this->redirectTo = '/home';
        }
    }
}
