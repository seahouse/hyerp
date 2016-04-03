<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
use Cache;
use DB, Auth;

class DingTalkController extends Controller
{
    const corpid = 'ding6ed55e00b5328f39';
    const corpsecret = 'gdQvzBl7IW5f3YUSMIkfEIsivOVn8lcXUL_i1BIJvbP4kPJh8SU8B8JuNe8U9JIo';

    public function getAccessToken() {
        $accessToken = Cache::get('access_token', '');
        if ($accessToken == '')
        {            
            $accessToken = Cache::remember('access_token', 7200/60, function() {
                $url = 'https://oapi.dingtalk.com/gettoken';
                $corpid = self::corpid;
                $corpsecret = self::corpsecret;
                $params = compact('corpid', 'corpsecret');
                $reply = $this->get($url, $params);
                $accessToken = $reply->access_token;
                return $accessToken;
            });
        }
        return $accessToken;
    }

    public function getTicket($access_token)
    {
        $ticket = Cache::get('ticket', '');
        if ($ticket == '')
        {            
            $ticket = Cache::remember('ticket', 7200/60, function() use($access_token) {
                $url = 'https://oapi.dingtalk.com/get_jsapi_ticket';
                $params = compact('access_token');
                $reply = $this->get($url, $params);
                $ticket = $reply->ticket;
                return $ticket;
            });
        }
        return $ticket;
    }

    public function sign($ticket, $nonceStr, $timeStamp, $url)
    {
        $rawstring = 'jsapi_ticket=' . $ticket .
                     '&noncestr=' . $nonceStr .
                     '&timestamp=' . $timeStamp .
                     '&url=' . $url;
        $signature = sha1($rawstring);    
        
        return $signature;
    }

    public function getconfig()
    {
        $nonceStr = str_random(32);
        $timeStamp = time();
        $url = urldecode(request()->fullurl());
        $corpAccessToken = self::getAccessToken();
        $ticket = self::getTicket($corpAccessToken);
        $signature = self::sign($ticket, $nonceStr, $timeStamp, $url);

        $config = array(
            'url' => $url,
            'nonceStr' => $nonceStr,
            'timeStamp' => $timeStamp,
            'corpId' => self::corpid,
            'signature' => $signature,
            'ticket' => $ticket
        );

        return $config;
        // return json_encode($config, JSON_UNESCAPED_SLASHES);
        // return response()->json($config);
    }

    public function getuserinfo($code)
    {
        $corpid = 'ding6ed55e00b5328f39';
        $corpsecret = 'gdQvzBl7IW5f3YUSMIkfEIsivOVn8lcXUL_i1BIJvbP4kPJh8SU8B8JuNe8U9JIo';

        // Get access_token
        // $access_token = Cache::remember('access_token', 7200/60, function() use($corpid, $corpsecret) {
        //     $url = 'https://oapi.dingtalk.com/gettoken';
        //     $params = compact('corpid', 'corpsecret');
        //     $reply = $this->get($url, $params);
        //     $access_token = $reply->access_token;
        //     return $access_token;
        // });

        $url = 'https://oapi.dingtalk.com/gettoken';
        $params = compact('corpid', 'corpsecret');
        $reply = $this->get($url, $params);
        $access_token = $reply->access_token;

        // Get user info
        $url = 'https://oapi.dingtalk.com/user/getuserinfo';
        $params = compact('access_token', 'code');
        $userInfo = $this->get($url, $params);

        // get erp user info and set session userid
        $user_erp = DB::table('users')->where('dtuserid', $userInfo->userid)->first();
        $userid_erp = -1;
        if (!is_null($user_erp))
        {
            $userid_erp = $user_erp->id;
            session()->put('userid', $userid_erp);
            // login 
            if (!Auth::check())
            {
                Auth::loginUsingId($userid_erp);
            }
        }

        $user = [
            'deviceId' => $userInfo->deviceId,
            'errcode' => $userInfo->errcode,
            'errmsg' => $userInfo->errmsg,
            'is_sys' => $userInfo->is_sys,
            'sys_level' => $userInfo->sys_level,
            'userid' => $userInfo->userid,
            'userid_erp' => $userid_erp,
        ];
        return response()->json($user);
    }

    public function mddauth()
    {
        $config = $this->getconfig();
        return view('mddauth', compact('config'));
    }
    
    public function index()
    {

    }
    
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }
    
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }
    
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }
    
    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }
    
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }
    
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
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
//         $response = \Httpful\Request::post($url . '?' . http_build_query($params))
//         ->body($data)
//         ->sendsJson()
//         ->send();
//         if ($response->hasErrors()) {
//             throw new \Exception($response->hasErrors());
//         }
//         if (!$response->hasBody()) {
//             throw new \Exception("No response body.");
//         }
//         if ($response->body->errcode != 0) {
//             throw new \Exception($response->body->errmsg);
//         }
//         return $response->body;
    }
}
