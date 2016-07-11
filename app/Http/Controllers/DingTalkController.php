<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
use Cache;
use DB, Auth, Config;

class DingTalkController extends Controller
{

    // private static $CORPID = 'ding6ed55e00b5328f39';
    // private static $CORPSECRET = 'gdQvzBl7IW5f3YUSMIkfEIsivOVn8lcXUL_i1BIJvbP4kPJh8SU8B8JuNe8U9JIo';
    // private static $AGENTID = '';      // 在登录时进行确定（mddauth）
    // private static $AGENTIDS = ['approval' => '13231599'];

    private static $APPNAME = '';

    // const corpid = 'ding6ed55e00b5328f39';
    // const corpsecret = 'gdQvzBl7IW5f3YUSMIkfEIsivOVn8lcXUL_i1BIJvbP4kPJh8SU8B8JuNe8U9JIo';

    public static function getAccessToken() {
        $accessToken = Cache::remember('access_token', 7200/60 - 5, function() {        // 减少5分钟来确保不会因为与钉钉存在时间差而导致的问题
            $url = 'https://oapi.dingtalk.com/gettoken';
            $corpid = config('custom.dingtalk.corpid');
            $corpsecret = config('custom.dingtalk.corpsecret');
            $params = compact('corpid', 'corpsecret');
            // $reply = $this->get($url, $params);
            $reply = self::get($url, $params);
            $accessToken = $reply->access_token;
            return $accessToken;
        });
        return $accessToken;
    }

    public static function getTicket($access_token)
    {
        $ticket = Cache::remember('ticket', 7200/60 - 5, function() use($access_token) {
            $url = 'https://oapi.dingtalk.com/get_jsapi_ticket';
            $params = compact('access_token');
            $reply = self::get($url, $params);
            $ticket = $reply->ticket;
            return $ticket;
        });
        return $ticket;
    }

    public static function sign($ticket, $nonceStr, $timeStamp, $url)
    {
        $rawstring = 'jsapi_ticket=' . $ticket .
                     '&noncestr=' . $nonceStr .
                     '&timestamp=' . $timeStamp .
                     '&url=' . $url;
        $signature = sha1($rawstring);    
        
        return $signature;
    }


    public static function getconfig()
    {
        // Cache::flush();
        $nonceStr = str_random(32);
        $timeStamp = time();
        // $url = urldecode(request()->fullurl());
        $url = urldecode(request()->url());
        $corpAccessToken = self::getAccessToken();
        $ticket = self::getTicket($corpAccessToken);
        $signature = self::sign($ticket, $nonceStr, $timeStamp, $url);

        $config = array(
            'url' => $url,
            'nonceStr' => $nonceStr,
            'timeStamp' => $timeStamp,
            'corpId' => config('custom.dingtalk.corpid'),
            'signature' => $signature,
            'ticket' => $ticket,
            'agentId' => config('custom.dingtalk.agentidlist.' . self::$APPNAME),       // such as: config('custom.dingtalk.agentidlist.approval')      // request('app')
            'appname' => self::$APPNAME,
        );

        return $config;
        // return json_encode($config, JSON_UNESCAPED_SLASHES);
        // return response()->json($config);
    }

    public function getuserinfo($code)
    {
        $corpid = 'ding6ed55e00b5328f39';
        $corpsecret = 'gdQvzBl7IW5f3YUSMIkfEIsivOVn8lcXUL_i1BIJvbP4kPJh8SU8B8JuNe8U9JIo';

        // $url = 'https://oapi.dingtalk.com/gettoken';
        // $params = compact('corpid', 'corpsecret');
        // $reply = $this->get($url, $params);
        // $access_token = $reply->access_token;
        $access_token = self::getAccessToken();

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

    public function mddauth($appname = 'approval')
    {
        // Cache::flush();
        // self::$AGENTID = array_get(self::$AGENTIDS, request('app'), '13231599');
        self::$APPNAME = $appname;
        $config = $this->getconfig();
        // dd(compact('config'));
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
    
    private static function get($url, $params)
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
    
    public static function post($url, $params, $data, $handlererr = true)
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
        if ($handlererr)
        {
            if ($response->body->errcode != 0) {
                throw new \Exception($response->body->errmsg);
            }            
        }

        return $response->body;
    }

    /**
     * send enterprise message.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public static function send($touser, $toparty, $message, $agentid = '')
    {
        $url = 'https://oapi.dingtalk.com/message/send';
        $access_token = self::getAccessToken();
        $params = compact('access_token');
        if ($agentid == '')
            $agentid = config('custom.dingtalk.agentidlist.' . self::$APPNAME);
        $data = [
            'touser' => $touser,
            'toparty' => '',
            'agentid' => $agentid,
            'msgtype' => 'text',
            'text' => [
                'content' => $message,
            ],
        ];
        DingTalkController::post($url, $params, json_encode($data), false);
    }

    /**
     * send enterprise message.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public static function userGet($userid) {
        $url = 'https://oapi.dingtalk.com/user/get';
        $access_token = self::getAccessToken();
        $params = compact('access_token', 'userid');
        return self::get($url, $params);
    }
}
