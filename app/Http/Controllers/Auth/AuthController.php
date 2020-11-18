<?php

namespace App\Http\Controllers\Auth;

use App\Models\System\User;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\ThrottlesLogins;
use Illuminate\Foundation\Auth\AuthenticatesAndRegistersUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use App\Models\System\Role;

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
            'mobile' => 'required|unique:users',
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
        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'mobile' => $data['mobile'],
            'password' => bcrypt($data['password']),
        ]);

        $user->roles()->save(Role::where('name', 'supplier')->first());
        return $user;
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

        $redirect_uri = urlencode("https://oapi.dingtalk.com/connect/oauth2/sns_authorize?appid=" . config('custom.dingtalk.appid') . "&response_type=code&scope=snsapi_login&state=STATE&redirect_uri=" . url("/mddauth") . "/");
        return view('auth.login2', compact('redirect_uri'));
        return redirect()->intended($this->redirectTo);
    }

    public function changeuser()
    {
        //        if (!Auth::check())
        //        {
        //            Auth::loginUsingId(config('custom.changeuser_id'));
        //        }
        //        else
        //        {
        //            Auth::logout();
        //            Auth::loginUsingId(config('custom.changeuser_id'));
        //        }
        ////
        ////        //login and redirect user
        ////        Auth::loginUsingId($userId);
        //
        ////        $redirect_uri = urlencode("https://oapi.dingtalk.com/connect/oauth2/sns_authorize?appid=" . config('custom.dingtalk.appid') . "&response_type=code&scope=snsapi_login&state=STATE&redirect_uri=" .url("/mddauth") . "/");
        ////        return view('auth.login2', compact('redirect_uri'));
        //        return redirect()->intended($this->redirectTo);
    }

    /**
     * 检查手机号是否在users表中
     */
    public function checkPhoneExist($phone)
    {
        $user = User::where('mobile', $phone)->first();
        $ret = isset($user) ? 'OK' : 'NG';
        return response()->json(['code' => $ret]);
    }

    public function loginbysms(Request $request)
    {
        $this->validate($request, [
            'phonenum' => 'required',
            'code' => 'required|integer',
        ]);
        // dd($request->all());
        $mobile = $request->phonenum;
        $code = $request->code;
        $syscode = $request->syscode;

        if ($code != $syscode) {
            return redirect()->back()
                ->withInput(['phonenum' => $mobile, 'tab_active' => 1, 'syscode' => $syscode])
                ->withErrors(['code' => '验证码不正确']);
        }

        $user = User::where('mobile', $mobile)->first();
        if (isset($user)) {
            Auth::login($user);
            return redirect()->intended($this->redirectTo);
        } else {
            return redirect()->back()
                ->withInput(['phonenum' => $mobile, 'tab_active' => 1, 'syscode' => $syscode])
                ->withErrors(['phonenum' => '手机号不存在']);
        }
    }
}
