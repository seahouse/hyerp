<?php

namespace App\Http\Controllers\Auth;

use App\Models\System\User;
use Validator;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\ThrottlesLogins;
use Illuminate\Foundation\Auth\AuthenticatesAndRegistersUsers;

class AuthController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Registration & Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users, as well as the
    | authentication of existing users. By default, this controller uses
    | a simple trait to add these behaviors. Why don't you explore it?
    |
    */

    use AuthenticatesAndRegistersUsers, ThrottlesLogins;

    /**
     * Where to redirect users after login / registration.
     *
     * @var string
     */
    protected $redirectTo = '/';

    /**
     * Create a new authentication controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest', ['except' => 'logout']);
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
            'name' => 'required|max:255',
            'email' => 'required|email|max:255|unique:users',
            'password' => 'required|confirmed|min:6',
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return User
     */
    protected function create(array $data)
    {
        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => bcrypt($data['password']),
        ]);
    }

//    private function authenticated(Request $request, Authenticatable $user)
//    {
//        if ($user->google2fa_secret) {
//            Auth::logout();
//
//            $request->session()->put('2fa:user:id', $user->id);
//
//            return redirect('2fa/validate');
//        }
//
//        return redirect()->intended($this->redirectTo);
//    }
//
//    public function getValidateToken()
//    {
//        if (session('2fa:user:id')) {
//            return view('2fa/validate');
//        }
//
//        return redirect('login');
//    }
//
//    public function postValidateToken(ValidateSecretRequest $request)
//    {
//        //get user id and create cache key
//        $userId = $request->session()->pull('2fa:user:id');
//        $key    = $userId . ':' . $request->totp;
//
//        //use cache to store token to blacklist
//        Cache::add($key, true, 4);
//
//        //login and redirect user
//        Auth::loginUsingId($userId);
//
//        return redirect()->intended($this->redirectTo);
//    }

    public function showLoginForm2()
    {
        //get user id and create cache key
//        $userId = $request->session()->pull('2fa:user:id');
//        $key    = $userId . ':' . $request->totp;
//
//        //use cache to store token to blacklist
//        Cache::add($key, true, 4);
//
//        //login and redirect user
//        Auth::loginUsingId($userId);

        $redirect_uri = urlencode("https://oapi.dingtalk.com/connect/oauth2/sns_authorize?appid=dingoan0l3bqaulubiap2y&response_type=code&scope=snsapi_login&state=STATE&redirectUrl=http://www.huaxing-east.cn:2016/");
        return view('auth.login2', compact('redirect_uri'));
        return redirect()->intended($this->redirectTo);
    }
}
