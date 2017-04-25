<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use Google2FA;
use DB;
use Auth;

class Google2FAController extends Controller
{
    //
    public function generatesecretkey()
    {
        //
        return Google2FA::generateSecretKey();
    }

    public function test($secret)
    {
        //
        $user = DB::table('users')->where('email', 'admin@admin.com')->first();
//        dd($user);
        $valid = Google2FA::verifyKey($user->google2fa_secret, $secret);
        dd($valid);
    }

    public function login(Request $request)
    {
        //
//        dd($request->all());
        $user = DB::table('users')->where('email', $request->input('email'))->first();
        if (isset($user))
        {
            $secret = $request->input('google2fa_secret');
            if (isset($user->google2fa_secret))
            {
                $valid = Google2FA::verifyKey($user->google2fa_secret, $secret);
                if ($valid)
                {
                    Auth::loginUsingId($user->id);
                    return redirect('/');
                }
                else
                    return "验证失败";
            }
            else
                return "还未绑定Google Authenticator.";

        }
        else
            return "无此用户";
//
//        $valid = Google2FA::verifyKey($user->google2fa_secret, $secret);
//        dd($valid);
    }
}
