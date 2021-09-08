<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

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

    public function login(Request $request)
    {
        $request->validate(
            array(
                'email'    => 'required',
                'password' => 'required',
            )
        );

        $user           = User::firstWhere('email', $request->email);
        $check_password = $user !== null ? Hash::check($request->password, $user->password) : '';

        if ($check_password && $user !== null) {
            Auth::login($user);

            $datas = array(
                'url' => route('home'),
            );

            $response = array(
                'status'  => 'success',
                'code'    => 200,
                'data'    => $datas,
                'message' => 'Berhasil login',
            );
        } else {
            $response = array(
                'status'  => 'fail',
                'code'    => 401,
                'message' => 'Email / Password tidak valid'
            );
        }

        return response()->json($response);
    }

    /**
     * Log the user out of the application.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }
}
