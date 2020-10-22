<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Input;
use Redirect;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

use App\Models\Ekanban_Usersetup As user;

class LoginController extends Controller
{
    use AuthenticatesUsers;

    protected $redirectTo = '/portal';

    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function showLoginForm()
    {
        return view('login');
    }

    public function logout(Request $request)
    {
        $this->guard()->logout();

        $request->session()->invalidate();

        return redirect('/');
    }

    public function resetPassword(Request $request){
        return view('reset-password');
    }



    public function login()
    {
        if ($this->guard()->user()) {
            return redirect()->route('/');
        }

        return $this->showLoginForm();
    }

    public function postLogin(Request $request)
    {
        $this->validateLogin($request);
        
        // If the class is using the ThrottlesLogins trait, we can automatically throttle
        // the login attempts for this application. We'll key this by the username and
        // the IP address of the client making these requests into this application.
        if ($this->hasTooManyLoginAttempts($request)) {
            $this->fireLockoutEvent($request);

            return $this->sendLockoutResponse($request);
        }

        if ($this->attemptLogin($request)) {
            return $this->sendLoginResponse($request);
        }

        // If the login attempt was unsuccessful we will increment the number of attempts
        // to login and redirect the user back to the login form. Of course, when this
        // user surpasses their maximum number of attempts they will get locked out.
        $this->incrementLoginAttempts($request);

        return $this->sendFailedLoginResponse($request);
    }

    

    protected function validateLogin(Request $request)
    {
        $this->validate($request, [
            $this->username()   => 'required',
            'password'          => 'required',
        ]);
    }

    public function username()
    {
        return 'userid';
    }

    protected function credentials(Request $request)
    {
        return $request->only($this->username(), 'password');
    }

    protected function attemptLogin(Request $request)
    {
        //Override Auth Method to use MD5 instead of Hash
        $user = user::where([
            'userid'    => $request->userid,
            'password'  => md5($request->password)
        ])->first();

        if($user) {
            return $this->guard()->attempt(
                $this->credentials($request), $request->filled('remember')
            );
        }
        return false;
    }

    protected function guard()
    {
        return Auth::guard();
    }

}
