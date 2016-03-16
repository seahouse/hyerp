<?php

namespace App\God\Controllers\DingTalk;

use Illuminate\Http\Request;
use App\Http\Requests;
use Illuminate\Support\Facades\Input;
use DB, Auth, Cache, Redirect;

class AuthController extends \App\God\Controllers\GodController
{
    const CONFIG = [
        'corpid'     => 'ding05d110a55ed1446d',
        'corpsecret' => 'vk6AtJvIZZEyiiTx7psL5BaFZG268F8YXUF4VVwabbGAAgGDNXiIPRxjU8uEhtPX',
    ];

    public function __construct()
    {
        parent::__construct();
    }

    public function logout()
    {
        session()->flush();
        Auth::logout();
        return response(trans('dingtalk.auth.logout_message'));
    }

    public function login()
    {
        $response = "";

        try {
            if (!request()->has('agentid')) {
                return response('No agentid.');
            }
            if (request()->has('error')) {
                return response(request()->get('error'));
            }
            $agentid = request()->get('agentid');
            $corpid = self::CONFIG['corpid'];
            $corpsecret = self::CONFIG['corpsecret'];

            Cache::flush(); // TODO: need be deleted after release

            // Step1. Query access_token via corpid and corpsecret.
            $access_token = Cache::remember('access_token', 7200/60, function() use($corpid, $corpsecret) {
                $url = 'https://oapi.dingtalk.com/gettoken';
                $params = compact('corpid', 'corpsecret');
                $reply = $this->get($url, $params);
                $access_token = $reply->access_token;
                return $access_token;
            });

            // Step2. Query jsapi_ticket via access_token.
            if (!Cache::has('jsapi_ticket')) {
                $url = 'https://oapi.dingtalk.com/get_jsapi_ticket';
                $params = ['access_token' => $access_token];
                $reply = $this->get($url, $params);
                $ticket = $reply->ticket;
                $expires_in = $reply->expires_in;
                Cache::put('jsapi_ticket', $ticket, $expires_in/60);
            }
            $jsapi_ticket = Cache::get('jsapi_ticket');

            if (!request()->has('code')) {
                // Step3. Calc the signature via jsapi_ticket, etc.
                $timestamp  = time();
                $noncestr   = str_random(32);
                $rawstring = 'jsapi_ticket=' . $jsapi_ticket .
                             '&noncestr=' . $noncestr .
                             '&timestamp=' . $timestamp .
                             '&url=' . urldecode(request()->fullurl());
                $signature = sha1($rawstring);

                // Step4. Back to client/js to query auth code via signature, etc.
                $ddconfig = compact('agentid', 'corpid', 'timestamp', 'noncestr', 'signature', 'access_token', 'rawstring');
                $response = response()->view(parent::VIEW_NAMESPACE.'::'.'dingtalk.auth', compact('ddconfig'));
            }
            else {
                $code = request()->get('code');

                // Step5. Query user identity via access_token and auth code.
                $url = 'https://oapi.dingtalk.com/user/getuserinfo';
                $params = compact('access_token', 'code');
                $user = $this->get($url, $params);

                // Step6. Get user information via user identity.
                $url = 'https://oapi.dingtalk.com/user/get';
                $params = ['access_token' => $access_token, 'userid' => $user->userid];
                $info = $this->get($url, $params);

                // Step7. Authenticate ERP user via DingTalk user.
                $id = DB::table('users')->where('dtuserid', '=', $info->userid)->value('id');
                if(!$id) {
                    throw new \Exception('No dingtalk Id in ERP!');
                }
                Auth::loginUsingId($id);

                // Step8. Save session and redirect back to the previous page which user requested.
                $userInfo = [
                    'userid' => $user->userid,
                    'deviceId' => $user->deviceId,
                    'is_sys' => $user->is_sys,
                    'sys_level' => $user->sys_level,
                    'name' => $info->name,
                    'mobile' => $info->mobile,
                    'isAdmin' => $info->isAdmin,
                    'isBoss' => $info->isBoss,
                    'dingId' => $info->dingId,
                    'username' => DB::table('users')->where('id', '=', $id)->value('name'),
                    'id' => $id,
                ];
                session()->put('user', $userInfo);
                $response = redirect()->to(request()->get('backurl'));
            }
        }
        catch (\Exception $e) {
            $response = response($e->getMessage());
        }

        return $response;
    }

    private function get($url, $params)
    {
        $response = \Httpful\Request::get($url . '?' . http_build_query($params))->send();
        if ($response->hasErrors()) {
            throw new \Exception($response->hasErrors());
        }
        if (!$response->hasBody()) {
            throw new \Exception("No response body.");
        }
        if ($response->body->errcode != 0) {
            throw new \Exception($response->body->errmsg);
        }
        return $response->body;
    }

    private function post($url, $params, $data)
    {
        $response = \Httpful\Request::post($url . '?' . http_build_query($params))
                                    ->body($data)
                                    ->sendsJson()
                                    ->send();
        if ($response->hasErrors()) {
            throw new \Exception($response->hasErrors());
        }
        if (!$response->hasBody()) {
            throw new \Exception("No response body.");
        }
        if ($response->body->errcode != 0) {
            throw new \Exception($response->body->errmsg);
        }
        return $response->body;
    }
}
