<?php

namespace App\Http\Controllers\Auth;

use Auth;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
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

    /**
     * Where to redirect users after login.
     *
     * @var string
     */

    public function credentials(Request $request)
    {
        return [
            'email'     => $request -> email,
            'password'  => $request -> password,
            'active' => 'yes'

        ];
    }

    public function redirectTo() {

        if (auth() -> user() -> super_user == 'yes') {
            session(['super_user' => true]);
        }

        $path = parse_url($this -> previous_url, PHP_URL_PATH);

        // redirect to page requested or dashboard
        if ($this -> previous_url != '' && stristr($this -> previous_url, $_SERVER['HTTP_HOST']) && stristr($this -> previous_url, 'login') === false && $path != '/' && ! preg_match('/dashboard/', $path)) {
            $this -> redirectTo = $this -> previous_url;
        } else {
            $this -> redirectTo = '/dashboard';
        }

        return $this -> redirectTo;

    }

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(Request $request) {

		$this -> previous_url = $request -> previous_url;
        $this -> middleware('guest') -> except(['logout', 'login']);


    }

}
