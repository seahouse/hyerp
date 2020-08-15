<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Approval\AdditionsalesorderController;
use App\Http\Controllers\Approval\CorporatepaymentController;
use App\Http\Controllers\Approval\IssuedrawingController;
use App\Http\Controllers\Approval\McitempurchaseController;
use App\Http\Controllers\Approval\PppaymentController;
use App\Http\Controllers\Approval\ProjectsitepurchaseController;
use App\Http\Controllers\Approval\TechpurchaseController;
use App\Http\Controllers\Approval\VendordeductionController;
use App\Http\Controllers\util\HttpDingtalkEco;
use App\Http\Controllers\util\taobaosdk\dingtalk\DingTalkClient;
use App\Http\Controllers\util\taobaosdk\dingtalk\request\SmartworkBpmsProcessinstanceCreateRequest;
use App\Http\Controllers\util\taobaosdk\dingtalk\request\OapiMessageCorpconversationAsyncsendV2Request;
use App\Models\System\User;
use App\Models\System\Userold;
use Carbon\Carbon;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
use Cache;
use DB, Auth, Config, Log;
use Jenssegers\Agent\Agent;
use App\Http\Controllers\util\Http;
use App\Http\Controllers\crypto\DingtalkCrypt;
use App\Http\Controllers\System\UsersController;
use App\Models\Approval\Paymentrequest;
use GA;
//use PragmaRX\Google2FA\Google2FA;
//use PragmaRX\Google2FA\Contracts\Google2FA;
use Google2FA;

class DingTalkController extends Controller
{

    // private static $CORPID = 'ding6ed55e00b5328f39';
    // private static $CORPSECRET = 'gdQvzBl7IW5f3YUSMIkfEIsivOVn8lcXUL_i1BIJvbP4kPJh8SU8B8JuNe8U9JIo';
    // private static $AGENTID = '';      // 在登录时进行确定（mddauth）
    // private static $AGENTIDS = ['approval' => '13231599'];

    private static $APPNAME = '';
    // private static $ENCODING_AES_KEY;

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

    public static function getAccessToken_appkey($agentname = 'approval') {
        $accessToken = Cache::remember('access_token_appkey', 7200/60 - 5, function() use ($agentname) {        // 减少5分钟来确保不会因为与钉钉存在时间差而导致的问题
            $url = 'https://oapi.dingtalk.com/gettoken';
            $appkey = config('custom.dingtalk.hx_henan.apps.' . $agentname . '.appkey');
            $appsecret = config('custom.dingtalk.hx_henan.apps.' . $agentname . '.appsecret');
//            $corpid = config('custom.dingtalk.corpid');
//            $corpsecret = config('custom.dingtalk.corpsecret');
            $params = compact('appkey', 'appsecret');
            // $reply = $this->get($url, $params);
            $reply = self::get($url, $params);
            $accessToken = $reply->access_token;
            return $accessToken;
        });
        return $accessToken;
    }

    public static function getAccessToken_suite() {
        $accessToken = Cache::remember('access_token_suite', 7200/60 - 5, function() {        // 减少5分钟来确保不会因为与钉钉存在时间差而导致的问题
            // signature=kKlP1QmmXXX&timestamp=1527130370219&suiteTicket=xxx&accessKey=suitezmpdnvsw4xxxxx
            $url = 'https://oapi.dingtalk.com/service/get_corp_token';
            $timestamp = time();
            $suiteTicket = 'test';
            $str = $timestamp + "\n" + $suiteTicket;
            $suitesecret = 'yfjcsow8AozeUIGyb23xzAFilCz-Jgm6ylQDiQ7JnE6fpU74k4uYycpEay458RV6';
            $signature = hash_hmac('sha256', $str, $suitesecret, true);
            Log::info('str: ' . $str);
            Log::info('signature'. $signature);
            $accessKey = 'suiteuvrgsabybcf6vo1h';
//            $corpid = config('custom.dingtalk.corpid');
//            $corpsecret = config('custom.dingtalk.corpsecret');
            $url .= '?signature=' . $signature . '&timestamp=' . $timestamp . '&suiteTicket=' . $suiteTicket . '&accessKey=' . $accessKey;
            $auth_corpid = config('custom.dingtalk.corpid');
            $params = compact('auth_corpid');
            $data = ['auth_corpid' => $auth_corpid];
            $reply = self::post($url, [], $data);
            $accessToken = $reply->access_token;
            Log::info('accesstoken: ' . $accessToken);
            return $accessToken;
        });
        return $accessToken;
    }

    public static function getTokenSns() {
        $accessToken = Cache::remember('access_token_sns', 7200/60 - 5, function() {        // 减少5分钟来确保不会因为与钉钉存在时间差而导致的问题
            $url = 'https://oapi.dingtalk.com/sns/gettoken';
            $appid = config('custom.dingtalk.appid');
            $appsecret = config('custom.dingtalk.appsecret');
            $params = compact('appid', 'appsecret');
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

    public static function getconfig($agentid = '')
    {
//         Cache::flush();
        $nonceStr = str_random(32);
        $timeStamp = time();
        $url = urldecode(request()->fullurl());
//        $url = request()->url();
//        if (request()->getQueryString() <> '')
//            $url .= urldecode('?' . request()->getQueryString());
//        Log::info(request()->url());
//        Log::info(urldecode(request()->getQueryString()));
//        Log::info(request()->query());
//        Log::info(http_build_query(request()->query()));
//        Log::info($url);
        $corpAccessToken = self::getAccessToken();
        $ticket = self::getTicket($corpAccessToken);
        $signature = self::sign($ticket, $nonceStr, $timeStamp, $url);
        if ($agentid == '')
            $agentid = config('custom.dingtalk.agentidlist.' . self::$APPNAME);
//        Log::info($agentid);

        $config = array(
            'url' => $url,
            'nonceStr' => $nonceStr,
            'timeStamp' => $timeStamp,
            'corpId' => config('custom.dingtalk.corpid'),
            'signature' => $signature,
            'ticket' => $ticket,
//            'agentId' => config('custom.dingtalk.agentidlist.' . self::$APPNAME),       // such as: config('custom.dingtalk.agentidlist.approval')      // request('app')
            'agentId' => $agentid,
            'appname' => self::$APPNAME,
            'session' => $corpAccessToken,
        );

        return $config;
        // return json_encode($config, JSON_UNESCAPED_SLASHES);
        // return response()->json($config);
    }

    public static function getconfig2($agentname = 'approval')
    {
//         Cache::flush();
        $nonceStr = str_random(32);
        $timeStamp = time();
        $url = urldecode(request()->fullurl());
//        $url = request()->url();
//        if (request()->getQueryString() <> '')
//            $url .= urldecode('?' . request()->getQueryString());
//        Log::info(request()->url());
//        Log::info(urldecode(request()->getQueryString()));
//        Log::info(request()->query());
//        Log::info(http_build_query(request()->query()));
//        Log::info($url);
//        $corpAccessToken = self::getAccessToken_suite();
        $corpAccessToken = self::getAccessToken_appkey($agentname);
        Log::info('token_appkey: ' . $corpAccessToken);
        $ticket = self::getTicket($corpAccessToken);
        Log::info('ticket: ' . $ticket);
        $signature = self::sign($ticket, $nonceStr, $timeStamp, $url);
        Log::info('signature: ' . $signature);
        $agentid = config('custom.dingtalk.hx_henan.apps.' . $agentname . '.agentid');
//        Log::info($agentid);

        $config = array(
            'url' => $url,
            'nonceStr' => $nonceStr,
            'timeStamp' => $timeStamp,
            'corpId' => config('custom.dingtalk.hx_henan.corpid'),
            'signature' => $signature,
            'ticket' => $ticket,
//            'agentId' => config('custom.dingtalk.agentidlist.' . self::$APPNAME),       // such as: config('custom.dingtalk.agentidlist.approval')      // request('app')
            'agentId' => $agentid,
            'appname' => self::$APPNAME,
        );

        return $config;
        // return json_encode($config, JSON_UNESCAPED_SLASHES);
        // return response()->json($config);
    }

    public static function cacheflush()
    {
        dd('aaa');
        Cache::flush();
    }

    public function getuserinfo($code)
    {
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

    public function getuserinfo2($code)
    {
        // $url = 'https://oapi.dingtalk.com/gettoken';
        // $params = compact('corpid', 'corpsecret');
        // $reply = $this->get($url, $params);
        // $access_token = $reply->access_token;
        $access_token = self::getAccessToken_appkey();

        // Get user info
        $url = 'https://oapi.dingtalk.com/user/getuserinfo';
        $params = compact('access_token', 'code');
        $userInfo = $this->get($url, $params);

        // get erp user info and set session userid
        $user_erp = DB::table('dtuser2s')->where('userid', $userInfo->userid)->first();
        $userid_erp = -1;
        if (!is_null($user_erp))
        {
            $userid_erp = $user_erp->user_id;
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

    // 扫码免登的获取用户信息接口
    public function getuserinfoByScancode($code)
    {

        // $url = 'https://oapi.dingtalk.com/gettoken';
        // $params = compact('corpid', 'corpsecret');
        // $reply = $this->get($url, $params);
        // $access_token = $reply->access_token;
        $access_token = self::getTokenSns();

        $data = ['tmp_auth_code' => $code];
        $response = Http::post("/sns/get_persistent_code",
            array("access_token" => $access_token), json_encode($data));
//        return $response;
        $openid = $response->openid;
        $persistent_code = $response->persistent_code;

        $data = ['openid' => $openid, 'persistent_code' => $persistent_code];
        $response = Http::post("/sns/get_sns_token",
            array("access_token" => $access_token), json_encode($data));
//        return $response;
        $sns_token = $response->sns_token;

        // Get user info
        $url = 'https://oapi.dingtalk.com/sns/getuserinfo';
        $params = compact('sns_token');
        $userInfo = $this->get($url, $params);
//        Log::info(json_encode($userInfo));

        // get erp user info and set session userid
//        $user_erp = DB::table('users')->where('dtuserid', $userInfo->user_info->dingId)->first();
        $dtuser = DB::table('dtusers')->where('dingId', $userInfo->user_info->dingId)->first();
        $userid_erp = -1;
        if (isset($dtuser))
        {
            $userid_erp = $dtuser->user_id;
            session()->put('userid', $userid_erp);
            // login
            if (!Auth::check())
            {
                Auth::loginUsingId($userid_erp);
            }
            else
            {
                Auth::logout();
                Auth::loginUsingId($userid_erp);
            }
        }

        $user = [
//            'deviceId' => $userInfo->deviceId,
//            'errcode' => $userInfo->errcode,
//            'errmsg' => $userInfo->errmsg,
//            'is_sys' => $userInfo->is_sys,
//            'sys_level' => $userInfo->sys_level,
//            'userid' => $userInfo->userid,
            'userid_erp' => $userid_erp,
        ];
        return response()->json($user);
    }

    // 扫码免登的获取用户信息接口
    public function getuserinfoByScancode_hxold($code)
    {

        $access_token = self::getTokenSns();

        $data = ['tmp_auth_code' => $code];
        $response = Http::post("/sns/get_persistent_code",
            array("access_token" => $access_token), json_encode($data));
//        return $response;
        $openid = $response->openid;
        $persistent_code = $response->persistent_code;

        $data = ['openid' => $openid, 'persistent_code' => $persistent_code];
        $response = Http::post("/sns/get_sns_token",
            array("access_token" => $access_token), json_encode($data));
//        return $response;
        $sns_token = $response->sns_token;

        // Get user info
        $url = 'https://oapi.dingtalk.com/sns/getuserinfo';
        $params = compact('sns_token');
        $userInfo = $this->get($url, $params);
//        Log::info(json_encode($userInfo));

        // get erp user info and set session userid
//        $user_erp = DB::table('users')->where('dtuserid', $userInfo->user_info->dingId)->first();
        $dtuser = DB::table('dtusers')->where('dingId', $userInfo->user_info->dingId)->first();
        $userid_erp = -1;
        if (isset($dtuser))
        {
            $userold = DB::table('userolds')->where('user_id', $dtuser->user_id)->first();
            if (isset($userold))
            {
                $userid_erp = $userold->user_hxold_id;
            }
//            $userid_erp = $dtuser->user_id;
//            session()->put('userid', $userid_erp);
        }

        $data = [
            'userid_erp' => $userid_erp,
        ];
        return response()->json($data);
    }

    public function mddauth($appname = 'approval', $url = '')
    {
        // dd($url);
        // Cache::flush();
        // self::$AGENTID = array_get(self::$AGENTIDS, request('app'), '13231599');
        self::$APPNAME = $appname;
        $config = $this->getconfig();
        // dd(compact('config'));
        $agent = new Agent();
        $url = str_replace("-", "/", $url);
        $code = request('code', '');
        return view('mddauth', compact('config', 'agent', 'url', 'code'));
    }

    public function mddauth2($appname = '', $url = '')
    {
        // dd($url);
        // Cache::flush();
        // self::$AGENTID = array_get(self::$AGENTIDS, request('app'), '13231599');
        self::$APPNAME = $appname;
        $config = $this->getconfig2($appname);
        // dd(compact('config'));
        $agent = new Agent();
        $url = str_replace("-", "/", $url);
        $code = request('code', '');
        return view('mddauth2', compact('config', 'agent', 'url', 'code'));
    }

    public function ddauth($appname = 'approval')
    {
        // Cache::flush();
        // self::$AGENTID = array_get(self::$AGENTIDS, request('app'), '13231599');
        self::$APPNAME = $appname;
        $config = $this->getconfig();
        // dd(compact('config'));
        $agent = new Agent();
        // dd($agent->is('Firefox'));
        return view('ddauth', compact('config', 'agent'));
    }

    /**
     * register call back function.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    // public static function register_call_back() {
    //     $url = 'https://oapi.dingtalk.com/call_back/register_call_back';
    //     $access_token = self::getAccessToken();
    //     $params = compact('access_token', 'userid');
    //     $data = [
    //         'call_back_tag' => ['user_modify_org'],
    //         'token' => '',
    //         'aes_key' => $agentid,
    //         'aes_key' => 'text',
    //         'url' => '',
    //     ];
    //     return self::post($url, $params, json_encode($data), false);
    //     // return self::post($url, $params);
    // }
    
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
    public static function send_link($touser, $toparty, $messageUrl, $picUrl, $title, $text, $agentid = '')
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
            'msgtype' => 'link',
            'link' => [
                'messageUrl' => $messageUrl,
                'picUrl' => $picUrl,
                'title' => $title,
                'text' => $text,
            ],
        ];

        // $response = self::send2($access_token, $data);

        $response = DingTalkController::post($url, $params, json_encode($data), false);
//        dd($response);
        // Log::info($response->errmsg);
        // Log::info($response->invaliduser);
        // Log::info($response->forbiddenUserId);
        // DingTalkController::post($url, $params, json_encode($data), false);
    }

    /**
     * send enterprise message.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public static function send_oa_paymentrequest($touser, $toparty, $messageUrl, $picUrl, $title, $text, $paymentrequest, $agentid = '')
    {
        $form = [];
        if (Auth::user()->email == "admin@admin.com")
        {
            $form = [
                [
                    'key' => '金额:',
                    'value' => $paymentrequest->amount
                ],
                [
                    'key' => '申请人:',
                    'value' => $paymentrequest->applicant->name
                ]
            ];
        }

        $response = self::send_oa($touser, $toparty, $messageUrl, $picUrl, $title, $text, $form, $agentid);
    }

    /**
     * send enterprise message.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public static function send_oa($touser, $toparty, $messageUrl, $picUrl, $title, $text, $form, $agentid = '')
    {
        // $url = 'https://oapi.dingtalk.com/message/send';
        $access_token = self::getAccessToken();
        // $params = compact('access_token');
        if ($agentid == '')
            $agentid = config('custom.dingtalk.agentidlist.' . self::$APPNAME);

        $data = [
            'touser' => $touser,
            'toparty' => '',
            'agentid' => $agentid,
            'msgtype' => 'oa',
            'oa' => [
                'message_url' => $messageUrl,
                'pc_message_url' => $messageUrl,
                'head' => [
                    'bgcolor' => 'FFBBBBBB',
                    'text' => $title
                ],
                'body' => [
                    'title' => $text,
                    'form' => $form
                ]
            ]
        ];

        $response = self::send2($access_token, $data);
    }

    public static function send2($accessToken, $opt)
    {
        $response = Http::post("/message/send",
            array("access_token" => $accessToken), json_encode($opt));
        return $response;
    }

    public static function sendCorpMessageText($strJson)
    {
        $method = 'dingtalk.corp.message.corpconversation.asyncsend';
        $session = self::getAccessToken();
        $format = 'json';
        $v = '2.0';

        $msgtype = 'text';
        $agent_id = config('custom.dingtalk.agentidlist.erpmessage');

        $userid_list = '';
        $msgcontent = '';

        $json = json_decode($strJson);
        $user = User::where('id', $json->userid)->first();
        if (isset($user))
        {
            $userid_list = $user->dtuserid;
            $msgcontent = '{"content":"' . $json->msgcontent . '"}';
        }

//        // sent to wuceshi for test
//        if (strlen($userid_list) > 0)
//            $userid_list .= ",04090710367573";


        $params = compact('method', 'session', 'v', 'format',
            'msgtype', 'agent_id', 'userid_list', 'msgcontent');
        $data = [
//            'content' => $strJson
        ];
//        dd($params);
//        $response = DingTalkController::post('https://eco.taobao.com/router/rest', $params, json_encode($data), false);
//        dd($response);

        $response = HttpDingtalkEco::post("",
            $params, json_encode($data));
        return $response;
        return response()->json($response);

    }

    public static function sendCorpMessageTextReminder($strJson)
    {
        $method = 'dingtalk.corp.message.corpconversation.asyncsend';
        $session = self::getAccessToken();
        $format = 'json';
        $v = '2.0';

        $msgtype = 'text';
        $agent_id = config('custom.dingtalk.agentidlist.erpreminder');

        $userid_list = '';
        $msgcontent = '';

        $json = json_decode($strJson);
        $user = User::where('id', $json->userid)->first();
        if (isset($user))
        {
            $userid_list = $user->dtuserid;
            $msgcontent = '{"content":"' . $json->msgcontent . '"}';
        }

//        // sent to wuceshi for test
//        if (strlen($userid_list) > 0)
//            $userid_list .= ",04090710367573";

        $params = compact('method', 'session', 'v', 'format',
            'msgtype', 'agent_id', 'userid_list', 'msgcontent');
        $data = [
//            'content' => $strJson
        ];

        $response = HttpDingtalkEco::post("",
            $params, json_encode($data));
        return $response;
        return response()->json($response);
    }

    public function send_erp($strJson)
    {
        $method = 'dingtalk.corp.message.corpconversation.asyncsend';
        $session = self::getAccessToken();
//        $timestamp = date('Y-m-d h:i:s');
//        $timestamp = Carbon::now()->toDateTimeString();
//        dd($timestamp . ' ' . $session);
        $format = 'json';
        $v = '2.0';

        $msgtype = 'text';
        $agent_id = config('custom.dingtalk.agentidlist.erpmessage');

        $userid_list = '';
        $msgcontent = '';

        $json = json_decode($strJson);
        $userold = Userold::where('user_hxold_id', $json->userid)->first();
        if (isset($userold))
        {
            $user = User::where('id', $userold->user_id)->first();
            if (isset($user))
            {
                $userid_list = $user->dtuserid;
                $dtuser = self::userGet($user->dtuserid);
                $jsonIsLeaderInDepts = $dtuser->isLeaderInDepts;
//                dd($dtuser->department[0]);
                if (!strpos($jsonIsLeaderInDepts, "true"))
                {
                    $userListResponse = Http::get("/user/list",
                        array("access_token" => $session, 'department_id' => $dtuser->department[0]));
                    $userlist = $userListResponse->userlist;
                    foreach ($userlist as $user)
                    {
                        if ($user->isLeader == "true")
                            $userid_list .= "," . $user->userid;
                        else
                            ;
                    }
                }
                else
                {
                    //
                    $uplevelusers = config('custom.dingtalk.uplevel.' . $user->userid);
                    if (strlen($uplevelusers) > 0)
                        $userid_list .= "," . $uplevelusers;
                }
                $msgcontent = '{"content":' . $json->msgcontent . '}';
            }
        }
        Log::info($userid_list);

        // sent to wuceshi for test
        if (strlen($userid_list) > 0)
            $userid_list .= ",04090710367573";

//        $userid_list = 'manager1200';
//        $msgcontent = '{"content":' . $strJson . '}';
//        $msgcontent = '{"content":"张三的请假申请9"}';

        $params = compact('method', 'session', 'v', 'format',
            'msgtype', 'agent_id', 'userid_list', 'msgcontent');
        $data = [
//            'content' => $strJson
        ];
//        dd($params);
//        $response = DingTalkController::post('https://eco.taobao.com/router/rest', $params, json_encode($data), false);
//        dd($response);

        $response = HttpDingtalkEco::post("",
            $params, json_encode($data));
        return response()->json($response);

        return "";
    }

    public static function sendActionCardMsg($useridList, $agentid, $data)
    {
        $c = new DingTalkClient();
        $req = new OapiMessageCorpconversationAsyncsendV2Request();
        $req->setUseridList($useridList);
        $req->setAgentId($agentid);

//        Log::info(url('mddauth'));
//        $data = [
//            'msgtype'   => 'action_card',
//            'action_card' => [
//                'title' => '是透出到会话列表和通知的文案',
//                'markdown'  => '支持markdown格式的正文内容',
//                'btn_orientation' => '1',
//                'btn_json_list' => [
//                    [
//                        'title' => '一个按钮',
//                        'action_url' => 'http://www.huaxing-east.cn:2016/mddauth/approval/sales/salesorders/',
//                    ],
//                    [
//                        'title' => '两个按钮',
//                        'action_url' => 'https://www.tmall.com',
//                    ],
//                ]
//            ],
//        ];
        Log::info(json_encode($data));
        $req->setMsg(json_encode($data));

        $session = self::getAccessToken();
        $response = $c->execute($req, $session);
        Log::info(json_encode($response));
        return $response;


//        $method = 'dingtalk.corp.message.corpconversation.asyncsend';
//        $session = self::getAccessToken();
//        $format = 'json';
//        $v = '2.0';
//
//        $msgtype = 'text';
//        $agent_id = config('custom.dingtalk.agentidlist.erpreminder');
//
//        $userid_list = '';
//        $msgcontent = '';
//
//        $json = json_decode($strJson);
//        $user = User::where('id', $json->userid)->first();
//        if (isset($user))
//        {
//            $userid_list = $user->dtuserid;
//            $msgcontent = '{"content":"' . $json->msgcontent . '"}';
//        }
//
//
//        $params = compact('method', 'session', 'v', 'format',
//            'msgtype', 'agent_id', 'userid_list', 'msgcontent');
//        $data = [
//        ];
//
//        $response = HttpDingtalkEco::post("",
//            $params, json_encode($data));
//        return $response;
//        return response()->json($response);
    }

    public static function sendWorkNotificationMessage($useridList, $agentid, $msg)
    {
        $c = new DingTalkClient();
        $req = new OapiMessageCorpconversationAsyncsendV2Request();
        $req->setUseridList($useridList);
        $req->setAgentId($agentid);
        $req->setMsg($msg);

        $session = self::getAccessToken();
        $response = $c->execute($req, $session);
//        Log::info(json_encode($response));
        return $response;
    }

    public static function register_call_back_user()
    {
        // Cache::flush();
        $access_token = self::getAccessToken();
        // dd(str_random(32));

        // self::$ENCODING_AES_KEY = str_random(43);
        $data = [
            'call_back_tag' => ['user_add_org', 'user_modify_org', 'user_leave_org', 'bpms_task_change', 'bpms_instance_change'],
            'token' => config('custom.dingtalk.TOKEN'),
            'aes_key' => config('custom.dingtalk.ENCODING_AES_KEY'),
//            'url' => 'http://139.224.8.136:81/dingtalk/receive'
            'url' => url('dingtalk/receive')
//             'url' => 'http://www.huaxing-east.cn:2016/dingtalk/receive'
//             'url' => 'http://hyerp.ricki.cn/dingtalk/receive'
//             'url' => 'http://139.224.8.136:81/dingtalk/receive'
        ];
        // dd($data);

        $response = self::register_call_back($access_token, $data);
        return $response;
    }

    public static function register_call_back_user2()
    {
        // Cache::flush();
        $access_token = self::getAccessToken_appkey();
        // dd(str_random(32));

        // self::$ENCODING_AES_KEY = str_random(43);
        $data = [
            'call_back_tag' => ['user_add_org', 'user_modify_org', 'user_leave_org', 'bpms_task_change', 'bpms_instance_change'],
            'token' => config('custom.dingtalk.TOKEN'),
            'aes_key' => config('custom.dingtalk.ENCODING_AES_KEY'),
//            'url' => 'http://139.224.8.136:81/dingtalk/receive'
            'url' => url('dingtalk/receive2')
//             'url' => 'http://www.huaxing-east.cn:2016/dingtalk/receive'
//             'url' => 'http://hyerp.ricki.cn/dingtalk/receive'
//             'url' => 'http://139.224.8.136:81/dingtalk/receive'
        ];
        // dd($data);

        $response = self::register_call_back($access_token, $data);
        return $response;
    }

    // register call back approval
    // do not need this function. not use.
    public static function register_call_back_bpms()
    {
        $access_token = self::getAccessToken();

        $data = [
            'call_back_tag' => ['bpms_task_change', 'bpms_instance_change'],
            'token' => config('custom.dingtalk.TOKEN'),
            'aes_key' => config('custom.dingtalk.ENCODING_AES_KEY'),
            'url' => url('dingtalk/receivebpms')
        ];

        Log::info(json_encode($data));
        $response = self::register_call_back($access_token, $data);
        return $response;
    }

    public static function register_call_back($accessToken, $data)
    {
        $response = Http::post("/call_back/register_call_back",
            array("access_token" => $accessToken), json_encode($data));
        return $response;
    }

    public function delete_call_back()
    {
        $access_token = self::getAccessToken();

        $response = Http::get("/call_back/delete_call_back",
            array("access_token" => $access_token));
        // dd(response()->json($response));
        $data = [
            'errcode' => $response->errcode,
            'errmsg' => $response->errmsg
        ];
        // dd(json_encode($data));
        return response()->json($data);
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function synchronizeusers()
    {
//        Cache::flush();
        $access_token = self::getAccessToken();

        $response = Http::get("/department/list",
            array("access_token" => $access_token));
        $departments = $response->department;
        foreach ($departments as $department)
        {
            echo  $department->name . "</br>";
            $access_token = self::getAccessToken();
            $response = Http::get("/user/list",
                array("access_token" => $access_token, 'department_id' => $department->id));
            $userlist = $response->userlist;
            foreach ($userlist as $user)
            {
                echo '<li> ' . $user->name  . ' ' . $user->userid . ' ' . (isset($user->orgEmail) ? $user->orgEmail : '') . '</li>';
//                if (isset($user->orgEmail) && !empty($user->orgEmail))
                    UsersController::synchronizedtuser($user);
            }
        }
        dd($departments);
//        dd(response()->json($response));
        $data = [
            'errcode' => $response->errcode,
            'errmsg' => $response->errmsg
        ];
        // dd(json_encode($data));
        return response()->json($data);
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

    public static function userGet2($userid) {
        $url = 'https://oapi.dingtalk.com/user/get';
        $access_token = self::getAccessToken_appkey();
        $params = compact('access_token', 'userid');
        return self::get($url, $params);
    }

    public function receive()
    {
        $signature = $_GET["signature"];
        $timeStamp = $_GET["timestamp"];
        $nonce = $_GET["nonce"];
        $postdata = file_get_contents("php://input");
        $postList = json_decode($postdata,true);
        $encrypt = $postList['encrypt'];
        $crypt = new DingtalkCrypt(config('custom.dingtalk.TOKEN'), config('custom.dingtalk.ENCODING_AES_KEY'), config('custom.dingtalk.corpid'));
        // Log::info("ENCODING_AES_KEY: " . config('custom.dingtalk.ENCODING_AES_KEY'));

        $msg = "";
        $errCode = $crypt->DecryptMsg($signature, $timeStamp, $nonce, $encrypt, $msg);
        Log::info("msg: " . $msg);
//        Log::info("errCode: " . $errCode);

        if ($errCode != 0)
        {
            Log::info(json_encode($_GET) . "  ERR:" . $errCode);
            
            /**
             * 创建套件时检测回调地址有效性，使用CREATE_SUITE_KEY作为SuiteKey
             */
            $crypt = new DingtalkCrypt(config('custom.dingtalk.TOKEN'), config('custom.dingtalk.ENCODING_AES_KEY'), '');
            $errCode = $crypt->DecryptMsg($signature, $timeStamp, $nonce, $encrypt, $msg);
            if ($errCode == 0)
            {
                Log::info("DECRYPT CREATE SUITE MSG SUCCESS " . json_encode($_GET) . "  " . $msg);
                $eventMsg = json_decode($msg);
                $eventType = $eventMsg->EventType;
                if ("check_create_suite_url" === $eventType)
                {
                    $random = $eventMsg->Random;
                    $testSuiteKey = $eventMsg->TestSuiteKey;
                    
                    $encryptMsg = "";
                    $errCode = $crypt->EncryptMsg($random, $timeStamp, $nonce, $encryptMsg);
                    if ($errCode == 0) 
                    {
                        Log::info("CREATE SUITE URL RESPONSE: " . $encryptMsg);
                        echo $encryptMsg;
                        // return $encryptMsg;
                    } 
                    else 
                    {
                        Log::info("CREATE SUITE URL RESPONSE ERR: " . $errCode);
                    }
                }
                else
                {
                    //should never happened
                }
            }
            else 
            {
                Log::error(json_encode($_GET) . "CREATE SUITE ERR:" . $errCode);
            }
            return;
        }
        else
        {
            /**
             * 套件创建成功后的回调推送
             */
            Log::info("DECRYPT MSG SUCCESS " . json_encode($_GET) . "  " . $msg);
            $eventMsg = json_decode($msg);
            $eventType = $eventMsg->EventType;
            /**
             * 套件ticket
             */
            if ("suite_ticket" === $eventType)
            {
                Cache::setSuiteTicket($eventMsg->SuiteTicket);
            }
            /**
             * 临时授权码
             */
            else if ("tmp_auth_code" === $eventType)
            {
                $tmpAuthCode = $eventMsg->AuthCode;
                Activate::autoActivateSuite($tmpAuthCode);
            }
            /**
             * 授权变更事件
             */
            /*user_add_org : 通讯录用户增加
            user_modify_org : 通讯录用户更改
            user_leave_org : 通讯录用户离职
            org_admin_add ：通讯录用户被设为管理员
            org_admin_remove ：通讯录用户被取消设置管理员
            org_dept_create ： 通讯录企业部门创建
            org_dept_modify ： 通讯录企业部门修改
            org_dept_remove ： 通讯录企业部门删除
            org_remove ： 企业被解散
            */
            else if ("user_add_org" === $eventType)
            {
                Log::info(json_encode($_GET) . "  Info:user_add_org");
                //handle auth change event
                $data = json_decode($msg);
                foreach ($data->UserId as $userid) {
                    # code...
                    Log::info("user id: " . $userid);
                    $user = self::userGet($userid);
                    Log::info("user: " . json_encode($user));
                    UsersController::synchronizedtuser($user);
                }

            }
            else if ("user_modify_org" === $eventType)
            {
                Log::error(json_encode($_GET) . "  Info:user_modify_org");
                //handle auth change event
                $data = json_decode($msg);
                foreach ($data->UserId as $userid) {
                    # code...
                    Log::info("user id: " . $userid);
                    $user = self::userGet($userid);
                    Log::info("user: " . json_encode($user));
                    UsersController::synchronizedtuser($user);
//                    UsersController::updatedtuser($userid);
                }
            }
            else if ("user_leave_org" === $eventType)
            {
                Log::error(json_encode($_GET) . "  ERR:user_leave_org");
                // delete dtuser
                $data = json_decode($msg);
                foreach ($data->UserId as $userid) {
                    # code...
                    Log::info("user id: " . $userid);
//                    $user = self::userGet($userid);
//                    Log::info("user: " . json_encode($user));
                    UsersController::destroydtuser($userid);
                }
            }
            else if ("bpms_instance_change" === $eventType)
            {
//                Log::info(json_encode($_GET) . "  INFO:bpms_instance_change");
                $data = json_decode($msg);
//                Log::info("bpms_instance_change: " . $msg);
                if ($data->type == "finish" && $data->result == "agree")
                {
                    if ($data->processCode == "PROC-FF6YT8E1N2-TTFRATBAPC9QE86BLRWM1-SUHHCXBJ-2")
                        IssuedrawingController::updateStatusByProcessInstanceId($data->processInstanceId, 0);
                    elseif ($data->processCode == config('custom.dingtalk.approval_processcode.mcitempurchase'))
                        McitempurchaseController::updateStatusByProcessInstanceId($data->processInstanceId, 0);
                    elseif ($data->processCode == config('custom.dingtalk.approval_processcode.pppayment'))
                        PppaymentController::updateStatusByProcessInstanceId($data->processInstanceId, 0);
                    elseif ($data->processCode == config('custom.dingtalk.approval_processcode.projectsitepurchase'))
                        ProjectsitepurchaseController::updateStatusByProcessInstanceId($data->processInstanceId, 0);
                    elseif ($data->processCode == config('custom.dingtalk.approval_processcode.vendordeduction'))
                        VendordeductionController::updateStatusByProcessInstanceId($data->processInstanceId, 0);
                    elseif ($data->processCode == config('custom.dingtalk.approval_processcode.techpurchase'))
                        TechpurchaseController::updateStatusByProcessInstanceId($data->processInstanceId, 0);
                    elseif ($data->processCode == config('custom.dingtalk.approval_processcode.corporatepayment'))
                        CorporatepaymentController::updateStatusByProcessInstanceId($data->processInstanceId, 0);
                    elseif ($data->processCode == config('custom.dingtalk.approval_processcode.additionsalesorder'))
                        AdditionsalesorderController::updateStatusByProcessInstanceId($data->processInstanceId, 0);
                }
                elseif ($data->type == "finish" && $data->result == "refuse")
                {
                    if ($data->processCode == "PROC-FF6YT8E1N2-TTFRATBAPC9QE86BLRWM1-SUHHCXBJ-2")
                        IssuedrawingController::updateStatusByProcessInstanceId($data->processInstanceId, -1);
                    elseif ($data->processCode == config('custom.dingtalk.approval_processcode.mcitempurchase'))
                        McitempurchaseController::updateStatusByProcessInstanceId($data->processInstanceId, -1);
                    elseif ($data->processCode == config('custom.dingtalk.approval_processcode.pppayment'))
                        PppaymentController::updateStatusByProcessInstanceId($data->processInstanceId, -1);
                    elseif ($data->processCode == config('custom.dingtalk.approval_processcode.projectsitepurchase'))
                        ProjectsitepurchaseController::updateStatusByProcessInstanceId($data->processInstanceId, -1);
                    elseif ($data->processCode == config('custom.dingtalk.approval_processcode.vendordeduction'))
                        VendordeductionController::updateStatusByProcessInstanceId($data->processInstanceId, -1);
                    elseif ($data->processCode == config('custom.dingtalk.approval_processcode.techpurchase'))
                        TechpurchaseController::updateStatusByProcessInstanceId($data->processInstanceId, -1);
                    elseif ($data->processCode == config('custom.dingtalk.approval_processcode.corporatepayment'))
                        CorporatepaymentController::updateStatusByProcessInstanceId($data->processInstanceId, -1);
                    elseif ($data->processCode == config('custom.dingtalk.approval_processcode.additionsalesorder'))
                        AdditionsalesorderController::updateStatusByProcessInstanceId($data->processInstanceId, -1);
                }
                elseif ($data->type == "terminate")
                {
                    if ($data->processCode == "PROC-FF6YT8E1N2-TTFRATBAPC9QE86BLRWM1-SUHHCXBJ-2")
                        IssuedrawingController::updateStatusByProcessInstanceId($data->processInstanceId, -2);
                    elseif ($data->processCode == config('custom.dingtalk.approval_processcode.mcitempurchase'))
                        McitempurchaseController::updateStatusByProcessInstanceId($data->processInstanceId, -2);
                    elseif ($data->processCode == config('custom.dingtalk.approval_processcode.pppayment'))
                        PppaymentController::updateStatusByProcessInstanceId($data->processInstanceId, -2);
                    elseif ($data->processCode == config('custom.dingtalk.approval_processcode.projectsitepurchase'))
                        ProjectsitepurchaseController::updateStatusByProcessInstanceId($data->processInstanceId, -2);
                    elseif ($data->processCode == config('custom.dingtalk.approval_processcode.vendordeduction'))
                        VendordeductionController::updateStatusByProcessInstanceId($data->processInstanceId, -2);
                    elseif ($data->processCode == config('custom.dingtalk.approval_processcode.techpurchase'))
                        TechpurchaseController::updateStatusByProcessInstanceId($data->processInstanceId, -2);
                    elseif ($data->processCode == config('custom.dingtalk.approval_processcode.corporatepayment'))
                        CorporatepaymentController::updateStatusByProcessInstanceId($data->processInstanceId, -2);
                    elseif ($data->processCode == config('custom.dingtalk.approval_processcode.additionsalesorder'))
                        AdditionsalesorderController::updateStatusByProcessInstanceId($data->processInstanceId, -2);
                }
                elseif ($data->type == "delete")
                {
                    if ($data->processCode == "PROC-FF6YT8E1N2-TTFRATBAPC9QE86BLRWM1-SUHHCXBJ-2")
                        IssuedrawingController::deleteByProcessInstanceId($data->processInstanceId);
                    elseif ($data->processCode == config('custom.dingtalk.approval_processcode.mcitempurchase'))
                        McitempurchaseController::deleteByProcessInstanceId($data->processInstanceId);
                    elseif ($data->processCode == config('custom.dingtalk.approval_processcode.pppayment'))
                        PppaymentController::deleteByProcessInstanceId($data->processInstanceId);
                    elseif ($data->processCode == config('custom.dingtalk.approval_processcode.projectsitepurchase'))
                        ProjectsitepurchaseController::deleteByProcessInstanceId($data->processInstanceId);
                    elseif ($data->processCode == config('custom.dingtalk.approval_processcode.vendordeduction'))
                        VendordeductionController::deleteByProcessInstanceId($data->processInstanceId);
                    elseif ($data->processCode == config('custom.dingtalk.approval_processcode.techpurchase'))
                        TechpurchaseController::deleteByProcessInstanceId($data->processInstanceId);
                    elseif ($data->processCode == config('custom.dingtalk.approval_processcode.corporatepayment'))
                        CorporatepaymentController::deleteByProcessInstanceId($data->processInstanceId);
                    elseif ($data->processCode == config('custom.dingtalk.approval_processcode.additionsalesorder'))
                        AdditionsalesorderController::deleteByProcessInstanceId($data->processInstanceId);
                }
            }
            else if ("bpms_task_change" === $eventType)
            {
                Log::info(json_encode($_GET) . "  INFO:bpms_task_change");
                $data = json_decode($msg);
                Log::info("bpms_task_change: " . $msg);
            }
            /**
             * 应用被解除授权的时候，需要删除相应企业的存储信息
             */
            else if ("suite_relieve" === $eventType)
            {
                $corpid = $eventMsg->AuthCorpId;
                // ISVService::removeCorpInfo($corpid);
                //handle auth change event
            }else if ("change_auth" === $eventType)
             {
                 //handle auth change event
             }
            /**
             * 回调地址更新
             */
            else if ("check_update_suite_url" === $eventType)
            {
                $random = $eventMsg->Random;
                $testSuiteKey = $eventMsg->TestSuiteKey;
                
                $encryptMsg = "";
                $errCode = $crypt->EncryptMsg($random, $timeStamp, $nonce, $encryptMsg);
                if ($errCode == 0) 
                {
                    Log::info("UPDATE SUITE URL RESPONSE: " . $encryptMsg);
                    echo $encryptMsg;
                    return $encryptMsg;
                } 
                else 
                {
                    Log::error("UPDATE SUITE URL RESPONSE ERR: " . $errCode);
                }
            }
            else
            {
                //should never happen
            }
            
            $res = "success";
            $encryptMsg = "";
            $errCode = $crypt->EncryptMsg($res, $timeStamp, $nonce, $encryptMsg);
            if ($errCode == 0) 
            {
                Log::info("RESPONSE: " . $encryptMsg);
                echo $encryptMsg;
                // return $encryptMsg;
            } 
            else 
            {
                Log::error("RESPONSE ERR: " . $errCode);
            }
        }
    }

    public function receive2()
    {
        $signature = $_GET["signature"];
        $timeStamp = $_GET["timestamp"];
        $nonce = $_GET["nonce"];
        $postdata = file_get_contents("php://input");
        $postList = json_decode($postdata,true);
        $encrypt = $postList['encrypt'];
        $crypt = new DingtalkCrypt(config('custom.dingtalk.TOKEN'), config('custom.dingtalk.ENCODING_AES_KEY'), config('custom.dingtalk.hx_henan.corpid'));
        // Log::info("ENCODING_AES_KEY: " . config('custom.dingtalk.ENCODING_AES_KEY'));

        $msg = "";
        $errCode = $crypt->DecryptMsg($signature, $timeStamp, $nonce, $encrypt, $msg);
        Log::info("msg: " . $msg);
//        Log::info("errCode: " . $errCode);

        if ($errCode != 0)
        {
            Log::info(json_encode($_GET) . "  ERR:" . $errCode);

            /**
             * 创建套件时检测回调地址有效性，使用CREATE_SUITE_KEY作为SuiteKey
             */
            $crypt = new DingtalkCrypt(config('custom.dingtalk.TOKEN'), config('custom.dingtalk.ENCODING_AES_KEY'), '');
            $errCode = $crypt->DecryptMsg($signature, $timeStamp, $nonce, $encrypt, $msg);
            if ($errCode == 0)
            {
                Log::info("DECRYPT CREATE SUITE MSG SUCCESS " . json_encode($_GET) . "  " . $msg);
                $eventMsg = json_decode($msg);
                $eventType = $eventMsg->EventType;
                if ("check_create_suite_url" === $eventType)
                {
                    $random = $eventMsg->Random;
                    $testSuiteKey = $eventMsg->TestSuiteKey;

                    $encryptMsg = "";
                    $errCode = $crypt->EncryptMsg($random, $timeStamp, $nonce, $encryptMsg);
                    if ($errCode == 0)
                    {
                        Log::info("CREATE SUITE URL RESPONSE: " . $encryptMsg);
                        echo $encryptMsg;
                        // return $encryptMsg;
                    }
                    else
                    {
                        Log::info("CREATE SUITE URL RESPONSE ERR: " . $errCode);
                    }
                }
                else
                {
                    //should never happened
                }
            }
            else
            {
                Log::error(json_encode($_GET) . "CREATE SUITE ERR:" . $errCode);
            }
            return;
        }
        else
        {
            /**
             * 套件创建成功后的回调推送
             */
            Log::info("DECRYPT MSG SUCCESS " . json_encode($_GET) . "  " . $msg);
            $eventMsg = json_decode($msg);
            $eventType = $eventMsg->EventType;
            /**
             * 套件ticket
             */
            if ("suite_ticket" === $eventType)
            {
                Cache::setSuiteTicket($eventMsg->SuiteTicket);
            }
            /**
             * 临时授权码
             */
            else if ("tmp_auth_code" === $eventType)
            {
                $tmpAuthCode = $eventMsg->AuthCode;
                Activate::autoActivateSuite($tmpAuthCode);
            }
            /**
             * 授权变更事件
             */
            /*user_add_org : 通讯录用户增加
            user_modify_org : 通讯录用户更改
            user_leave_org : 通讯录用户离职
            org_admin_add ：通讯录用户被设为管理员
            org_admin_remove ：通讯录用户被取消设置管理员
            org_dept_create ： 通讯录企业部门创建
            org_dept_modify ： 通讯录企业部门修改
            org_dept_remove ： 通讯录企业部门删除
            org_remove ： 企业被解散
            */
            else if ("user_add_org" === $eventType)     // 河南华星
            {
                Log::info(json_encode($_GET) . "  Info:user_add_org");
                //handle auth change event
                $data = json_decode($msg);
                foreach ($data->UserId as $userid) {
                    # code...
                    Log::info("user id: " . $userid);
                    $user = self::userGet2($userid);
                    Log::info("user: " . json_encode($user));
                    UsersController::synchronizedtuser2($user);
                }
            }
            else if ("user_modify_org" === $eventType)
            {
                Log::error(json_encode($_GET) . "  Info:user_modify_org");
                //handle auth change event
                $data = json_decode($msg);
                foreach ($data->UserId as $userid) {
                    # code...
                    Log::info("user id: " . $userid);
                    $user = self::userGet2($userid);
                    Log::info("user: " . json_encode($user));
                    UsersController::synchronizedtuser2($user);
//                    UsersController::updatedtuser($userid);
                }
            }
            else if ("user_leave_org" === $eventType)
            {
                Log::error(json_encode($_GET) . "  ERR:user_leave_org");
                // delete dtuser
                $data = json_decode($msg);
                foreach ($data->UserId as $userid) {
                    # code...
                    Log::info("user id: " . $userid);
//                    $user = self::userGet($userid);
//                    Log::info("user: " . json_encode($user));
                    UsersController::destroydtuser2($userid);
                }
            }
            else if ("bpms_instance_change" === $eventType)
            {
//                Log::info(json_encode($_GET) . "  INFO:bpms_instance_change");
                $data = json_decode($msg);
                Log::info("bpms_instance_change: " . $msg);
                if ($data->type == "finish" && $data->result == "agree")
                {
                    if ($data->processCode == "RPOC-9794564E-DD4B-41BB-ABD7-1F514756FE2F")
                        IssuedrawingController::updateStatusByProcessInstanceId($data->processInstanceId, 0);
                    elseif ($data->processCode == config('custom.dingtalk.hx_henan.approval_processcode.mcitempurchase'))
                        McitempurchaseController::updateStatusByProcessInstanceId($data->processInstanceId, 0);
                    elseif ($data->processCode == config('custom.dingtalk.hx_henan.approval_processcode.pppayment'))
                        PppaymentController::updateStatusByProcessInstanceId($data->processInstanceId, 0);
                    elseif ($data->processCode == config('custom.dingtalk.approval_processcode.projectsitepurchase'))
                        ProjectsitepurchaseController::updateStatusByProcessInstanceId($data->processInstanceId, 0);
                    elseif ($data->processCode == config('custom.dingtalk.approval_processcode.vendordeduction'))
                        VendordeductionController::updateStatusByProcessInstanceId($data->processInstanceId, 0);
                }
                elseif ($data->type == "finish" && $data->result == "refuse")
                {
                    if ($data->processCode == "RPOC-9794564E-DD4B-41BB-ABD7-1F514756FE2F")
                        IssuedrawingController::updateStatusByProcessInstanceId($data->processInstanceId, -1);
                    elseif ($data->processCode == config('custom.dingtalk.hx_henan.approval_processcode.mcitempurchase'))
                        McitempurchaseController::updateStatusByProcessInstanceId($data->processInstanceId, -1);
                    elseif ($data->processCode == config('custom.dingtalk.hx_henan.approval_processcode.pppayment'))
                        PppaymentController::updateStatusByProcessInstanceId($data->processInstanceId, -1);
                    elseif ($data->processCode == config('custom.dingtalk.approval_processcode.projectsitepurchase'))
                        ProjectsitepurchaseController::updateStatusByProcessInstanceId($data->processInstanceId, -1);
                    elseif ($data->processCode == config('custom.dingtalk.approval_processcode.vendordeduction'))
                        VendordeductionController::updateStatusByProcessInstanceId($data->processInstanceId, -1);
                }
                elseif ($data->type == "terminate")
                {
                    if ($data->processCode == "RPOC-9794564E-DD4B-41BB-ABD7-1F514756FE2F")
                        IssuedrawingController::updateStatusByProcessInstanceId($data->processInstanceId, -2);
                    elseif ($data->processCode == config('custom.dingtalk.hx_henan.approval_processcode.mcitempurchase'))
                        McitempurchaseController::updateStatusByProcessInstanceId($data->processInstanceId, -2);
                    elseif ($data->processCode == config('custom.dingtalk.hx_henan.approval_processcode.pppayment'))
                        PppaymentController::updateStatusByProcessInstanceId($data->processInstanceId, -2);
                    elseif ($data->processCode == config('custom.dingtalk.approval_processcode.projectsitepurchase'))
                        ProjectsitepurchaseController::updateStatusByProcessInstanceId($data->processInstanceId, -2);
                    elseif ($data->processCode == config('custom.dingtalk.approval_processcode.vendordeduction'))
                        VendordeductionController::updateStatusByProcessInstanceId($data->processInstanceId, -2);
                }
                elseif ($data->type == "delete")
                {
                    if ($data->processCode == "RPOC-9794564E-DD4B-41BB-ABD7-1F514756FE2F")
                        IssuedrawingController::deleteByProcessInstanceId($data->processInstanceId);
                    elseif ($data->processCode == config('custom.dingtalk.hx_henan.approval_processcode.mcitempurchase'))
                        McitempurchaseController::deleteByProcessInstanceId($data->processInstanceId);
                    elseif ($data->processCode == config('custom.dingtalk.hx_henan.approval_processcode.pppayment'))
                        PppaymentController::deleteByProcessInstanceId($data->processInstanceId);
                    elseif ($data->processCode == config('custom.dingtalk.approval_processcode.projectsitepurchase'))
                        ProjectsitepurchaseController::deleteByProcessInstanceId($data->processInstanceId);
                    elseif ($data->processCode == config('custom.dingtalk.approval_processcode.vendordeduction'))
                        VendordeductionController::deleteByProcessInstanceId($data->processInstanceId);
                }
            }
            else if ("bpms_task_change" === $eventType)
            {
                Log::info(json_encode($_GET) . "  INFO:bpms_task_change");
                $data = json_decode($msg);
                Log::info("bpms_task_change: " . $msg);
            }
            /**
             * 应用被解除授权的时候，需要删除相应企业的存储信息
             */
            else if ("suite_relieve" === $eventType)
            {
                $corpid = $eventMsg->AuthCorpId;
                // ISVService::removeCorpInfo($corpid);
                //handle auth change event
            }else if ("change_auth" === $eventType)
            {
                //handle auth change event
            }
            /**
             * 回调地址更新
             */
            else if ("check_update_suite_url" === $eventType)
            {
                $random = $eventMsg->Random;
                $testSuiteKey = $eventMsg->TestSuiteKey;

                $encryptMsg = "";
                $errCode = $crypt->EncryptMsg($random, $timeStamp, $nonce, $encryptMsg);
                if ($errCode == 0)
                {
                    Log::info("UPDATE SUITE URL RESPONSE: " . $encryptMsg);
                    echo $encryptMsg;
                    return $encryptMsg;
                }
                else
                {
                    Log::error("UPDATE SUITE URL RESPONSE ERR: " . $errCode);
                }
            }
            else
            {
                //should never happen
            }

            $res = "success";
            $encryptMsg = "";
            $errCode = $crypt->EncryptMsg($res, $timeStamp, $nonce, $encryptMsg);
            if ($errCode == 0)
            {
                Log::info("RESPONSE: " . $encryptMsg);
                echo $encryptMsg;
                // return $encryptMsg;
            }
            else
            {
                Log::error("RESPONSE ERR: " . $errCode);
            }
        }
    }

    // do not need function, not use.
    public function receivebpms()
    {
        $signature = $_GET["signature"];
        $timeStamp = $_GET["timestamp"];
        $nonce = $_GET["nonce"];
        $postdata = file_get_contents("php://input");
        $postList = json_decode($postdata,true);
        $encrypt = $postList['encrypt'];
        $crypt = new DingtalkCrypt(config('custom.dingtalk.TOKEN'), config('custom.dingtalk.ENCODING_AES_KEY'), config('custom.dingtalk.corpid'));
        // Log::info("ENCODING_AES_KEY: " . config('custom.dingtalk.ENCODING_AES_KEY'));

        $msg = "";
        $errCode = $crypt->DecryptMsg($signature, $timeStamp, $nonce, $encrypt, $msg);
        Log::info("msg: " . $msg);
//        Log::info("errCode: " . $errCode);

        if ($errCode != 0)
        {
            Log::info(json_encode($_GET) . "  ERR:" . $errCode);

            /**
             * 创建套件时检测回调地址有效性，使用CREATE_SUITE_KEY作为SuiteKey
             */
            $crypt = new DingtalkCrypt(config('custom.dingtalk.TOKEN'), config('custom.dingtalk.ENCODING_AES_KEY'), '');
            $errCode = $crypt->DecryptMsg($signature, $timeStamp, $nonce, $encrypt, $msg);
            if ($errCode == 0)
            {
                Log::info("DECRYPT CREATE SUITE MSG SUCCESS " . json_encode($_GET) . "  " . $msg);
                $eventMsg = json_decode($msg);
                $eventType = $eventMsg->EventType;
                if ("check_create_suite_url" === $eventType)
                {
                    $random = $eventMsg->Random;
                    $testSuiteKey = $eventMsg->TestSuiteKey;

                    $encryptMsg = "";
                    $errCode = $crypt->EncryptMsg($random, $timeStamp, $nonce, $encryptMsg);
                    if ($errCode == 0)
                    {
                        Log::info("CREATE SUITE URL RESPONSE: " . $encryptMsg);
                        echo $encryptMsg;
                        // return $encryptMsg;
                    }
                    else
                    {
                        Log::info("CREATE SUITE URL RESPONSE ERR: " . $errCode);
                    }
                }
                else
                {
                    //should never happened
                }
            }
            else
            {
                Log::error(json_encode($_GET) . "CREATE SUITE ERR:" . $errCode);
            }
            return;
        }
        else
        {
            /**
             * 套件创建成功后的回调推送
             */
            Log::info("DECRYPT MSG SUCCESS " . json_encode($_GET) . "  " . $msg);
            $eventMsg = json_decode($msg);
            $eventType = $eventMsg->EventType;
            /**
             * 套件ticket
             */
            if ("suite_ticket" === $eventType)
            {
                Cache::setSuiteTicket($eventMsg->SuiteTicket);
            }
            /**
             * 临时授权码
             */
            else if ("tmp_auth_code" === $eventType)
            {
                $tmpAuthCode = $eventMsg->AuthCode;
                Activate::autoActivateSuite($tmpAuthCode);
            }
            /**
             * 授权变更事件
             */
            /*bpms_task_change :
            bpms_instance_change :
            user_leave_org : 通讯录用户离职
            org_admin_add ：通讯录用户被设为管理员
            org_admin_remove ：通讯录用户被取消设置管理员
            org_dept_create ： 通讯录企业部门创建
            org_dept_modify ： 通讯录企业部门修改
            org_dept_remove ： 通讯录企业部门删除
            org_remove ： 企业被解散
            */
            else if ("bpms_task_change" === $eventType)
            {
                Log::info(json_encode($_GET) . "  Info:bpms_task_change");
                //handle auth change event
                $data = json_decode($msg);
//                foreach ($data->UserId as $userid) {
//                    # code...
//                    Log::info("user id: " . $userid);
//                    $user = self::userGet($userid);
//                    Log::info("user: " . json_encode($user));
//                    UsersController::synchronizedtuser($user);
//                }

            }
            else if ("bpms_instance_change" === $eventType)
            {
                Log::error(json_encode($_GET) . "  Info:bpms_instance_change");
                //handle auth change event
                $data = json_decode($msg);
//                foreach ($data->UserId as $userid) {
//                    # code...
//                    Log::info("user id: " . $userid);
//                    $user = self::userGet($userid);
//                    Log::info("user: " . json_encode($user));
//                    UsersController::synchronizedtuser($user);
////                    UsersController::updatedtuser($userid);
//                }
            }
            else if ("user_leave_org" === $eventType)
            {
                Log::error(json_encode($_GET) . "  ERR:user_leave_org");
                // delete dtuser
                $data = json_decode($msg);
//                foreach ($data->UserId as $userid) {
//                    Log::info("user id: " . $userid);
//                    UsersController::destroydtuser($userid);
//                }
            }
            /**
             * 应用被解除授权的时候，需要删除相应企业的存储信息
             */
            else if ("suite_relieve" === $eventType)
            {
                $corpid = $eventMsg->AuthCorpId;
                // ISVService::removeCorpInfo($corpid);
                //handle auth change event
            }else if ("change_auth" === $eventType)
            {
                //handle auth change event
            }
            /**
             * 回调地址更新
             */
            else if ("check_update_suite_url" === $eventType)
            {
                $random = $eventMsg->Random;
                $testSuiteKey = $eventMsg->TestSuiteKey;

                $encryptMsg = "";
                $errCode = $crypt->EncryptMsg($random, $timeStamp, $nonce, $encryptMsg);
                if ($errCode == 0)
                {
                    Log::info("UPDATE SUITE URL RESPONSE: " . $encryptMsg);
                    echo $encryptMsg;
                    return $encryptMsg;
                }
                else
                {
                    Log::error("UPDATE SUITE URL RESPONSE ERR: " . $errCode);
                }
            }
            else
            {
                //should never happen
            }

            $res = "success";
            $encryptMsg = "";
            $errCode = $crypt->EncryptMsg($res, $timeStamp, $nonce, $encryptMsg);
            if ($errCode == 0)
            {
                Log::info("RESPONSE: " . $encryptMsg);
                echo $encryptMsg;
                // return $encryptMsg;
            }
            else
            {
                Log::error("RESPONSE ERR: " . $errCode);
            }
        }
    }

    public static function chat_create()
    {
        // dd('chat_create');
        $access_token = self::getAccessToken();
        $data = [
            'name' => 'hi',
            'owner' => 'manager1200',
            'useridlist' => ['03035843446917', '03360238665830']
            // 'url' => 'http://www.huaxing-east.cn:2016/dingtalk/receive'
            // 'url' => 'http://hyerp.ricki.cn/dingtalk/receive'
        ];

        $response = Http::post("/chat/create",
            array("access_token" => $access_token), json_encode($data));
        return json_encode($response);
    }

    public static function send_to_conversation(Request $request)
    {
//         dd('send_to_conversation');
        $access_token = self::getAccessToken();
        $paymentrequestid = $request->input('id', -1);
        $paymentrequest = Paymentrequest::findOrFail($paymentrequestid);
//        dd("审批日期: " . $paymentrequest->created_at . ", 客户: " . (isset($paymentrequest->supplier_hxold->name) ? $paymentrequest->supplier_hxold->name : "") . ", 金额: " . $paymentrequest->amount);
        $data = [
            'sender' => Auth::user()->dtuser->userid,
            'cid' => $request->input('cid'),
            'msgtype' => "text",
            "text" => [
                "content" => "审批日期: " . $paymentrequest->created_at . ", 客户: " . (isset($paymentrequest->supplier_hxold->name) ? $paymentrequest->supplier_hxold->name : "") . ", 金额: " . $paymentrequest->amount
            ]
        ];

        $response = Http::post("/message/send_to_conversation",
            array("access_token" => $access_token), json_encode($data));
        return json_encode($response);
    }

    public static function googleauthenticator(Google2FA $google2fa)
    {
//        $ga = new GA();
//        $secret = $ga->createSecret();
//        echo "Secret is: ".$secret."\n\n";

//        $google2fa = new Google2FA();
//        return $google2fa->generateSecretKey();

//        return $google2fa->generateSecretKey();

        return Google2FA::generateSecretKey();
    }

    public function routerrest()
    {
//        $response = Http::post("/message/send_to_conversation",
//            array("access_token" => $access_token), json_encode($data));
//        return json_encode($response);

        $method = 'dingtalk.smartwork.bpms.processinstance.create';
        $session = self::getAccessToken();
        $timestamp = time('2017-07-19 13:06:00');
        $format = 'json';
        $v = '2.0';

        $process_code = 'PROC-EF6YRO35P2-7MPMNW3BNO0R8DKYN8GX1-2EACCA5J-6';
        $originator_user_id = 'manager1200';
        $dept_id = 6643803;
        $approvers = 'manager1200';
        $formdata = [
            [
                'name'      => '测试1',
                'value'     => 'aaa',
            ],
            [
                'name'      => '测试2',
                'value'     => 'bbb',
            ],
        ];
//        $form_component_values = '{name:\'测试1\', value:\'aaa\'}';
        $form_component_values = json_encode($formdata);
        $params = compact('method', 'session', 'v', 'format',
            'process_code', 'originator_user_id', 'dept_id', 'approvers', 'form_component_values');
        $data = [
//            'process_code' => '001'
        ];
        $response = DingTalkController::post('https://eco.taobao.com/router/rest', $params, json_encode($data), false);
        dd($response);
    }

    public function processinstance_list()
    {
        $method = 'dingtalk.smartwork.bpms.processinstance.list';
        $session = self::getAccessToken();
        $timestamp = date('Y-m-d h:i:s');
        $format = 'json';
        $v = '2.0';

        $process_code = 'PROC-EF6YRO35P2-7MPMNW3BNO0R8DKYN8GX1-2EACCA5J-6';
        $start_time = 1502323200000;
//        $originator_user_id = 'manager1200';
//        $dept_id = 6643803;
//        $approvers = 'manager1200';
//        $form_component_values = '{name:\'测试1\', value:\'aaa\'}';
        $params = compact('method', 'session', 'timestamp', 'v', 'format',
            'process_code', 'start_time');
        $data = [
//            'process_code' => '001'
        ];
        $response = DingTalkController::post('https://eco.taobao.com/router/rest', $params, json_encode($data), false);
        dd($response);
    }

    public static function issuedrawing($inputs)
    {
        $user = Auth::user();
        $method = 'dingtalk.smartwork.bpms.processinstance.create';
        $format = 'json';
        $v = '2.0';
        if ($inputs['syncdtdesc'] == "许昌")
        {
            $session = self::getAccessToken_appkey();
            $process_code = config('custom.dingtalk.hx_henan.approval_processcode.issuedrawing');
            $originator_user_id = $user->dtuser2->userid;
            $departmentList = json_decode($user->dtuser2->department);
            $cc_list = config('custom.dingtalk.hx_henan.approversettings.issuedrawing.cc_list.' . $inputs['productioncompany']);
            $cc_list_default = config('custom.dingtalk.hx_henan.approversettings.issuedrawing.cc_list.default');
            $cc_list_designdepartment = config('custom.dingtalk.hx_henan.approversettings.issuedrawing.cc_list.designdepartment.' . $inputs['designdepartment']);
        }
        else
        {
            $session = self::getAccessToken();
            $process_code = config('custom.dingtalk.approval_processcode.issuedrawing');
            $originator_user_id = $user->dtuserid;
            $departmentList = json_decode($user->dtuser->department);
            $cc_list = config('custom.dingtalk.approversettings.issuedrawing.cc_list.' . $inputs['productioncompany']);
            $cc_list_default = config('custom.dingtalk.approversettings.issuedrawing.cc_list.default');
            $cc_list_designdepartment = config('custom.dingtalk.approversettings.issuedrawing.cc_list.designdepartment.' . $inputs['designdepartment']);
        }
//        $session = self::getAccessToken();

//        $process_code = 'PROC-EF6YJDXRN2-V88CLW5WMN8R63JUE7XW3-M0DE5SQI-2K';    // huaxing
//        $process_code = 'PROC-FF6YT8E1N2-TTFRATBAPC9QE86BLRWM1-SUHHCXBJ-2';    // huaxing
        $dept_id = 0;
        if (count($departmentList) > 0)
            $dept_id = array_first($departmentList);
        $approvers = $inputs['approvers'];
        $cc_list .= empty($cc_list) ? $cc_list_default : '';
        $cc_list .= empty($cc_list) ? $cc_list_designdepartment : ',' . $cc_list_designdepartment;
        $cc_position = "FINISH";
//        if (strlen($cc_list) == 0)
//            $cc_list = config('custom.dingtalk.approversettings.mcitempurchase.cc_list.default');
//        if ($cc_list <> "")
//        {
//            $req->setCcList($cc_list);
//            $req->setCcPosition("FINISH");
//        }

        $detail_array = [];
        $issuedrawingcabinet_items = json_decode($inputs['items_string']);
        foreach ($issuedrawingcabinet_items as $value) {
            if (strlen($value->name) > 0)
            {
                $item_array = [
                    [
                        'name'      => '名称',
                        'value'     => $value->name,
                    ],
                    [
                        'name'      => '数量',
                        'value'     => $value->quantity,
                    ],
                ];
                array_push($detail_array, $item_array);
            }
        }

        $formdata = [
            [
                'name'      => '设计部门',
                'value'     => $inputs['designdepartment'],
            ],
            [
                'name'      => '公司',
                'value'     => $inputs['company_name'],
            ],
            [
                'name'      => '项目名称',
                'value'     => $inputs['project_name'],
            ],
            [
                'name'      => '制作概述',
                'value'     => $inputs['overview'],
            ],
            [
                'name'      => '柜体明细',
                'value'     => json_encode($detail_array),
            ],
            [
                'name'      => '吨位明细',
                'value'     => $inputs['tonnagedetails'],
            ],
            [
                'name'      => '吨位（吨）',
                'value'     => $inputs['tonnage'],
            ],
            [
                'name'      => '项目编号',
                'value'     => $inputs['sohead_number'],
            ],
            [
                'name'      => '制作公司',
                'value'     => $inputs['productioncompany'],
            ],
            [
                'name'      => '外协单位',
                'value'     => $inputs['outsourcingcompany'],
            ],
            [
                'name'      => '材料供应方',
                'value'     => $inputs['materialsupplier'],
            ],
            [
                'name'      => '图纸校核人',
                'value'     => $inputs['drawingchecker'],
            ],
            [
                'name'      => '要求发货日',
                'value'     => $inputs['requestdeliverydate'],
            ],
            [
                'name'      => '是否栓接',
                'value'     => $inputs['bolt_str'],
            ],
            [
                'name'      => '图纸份数（份）',
                'value'     => $inputs['drawingcount'],
            ],
            [
                'name'      => '附件地址',
                'value'     => $inputs['drawingattachments_url'],
//                'value'     => '<a href="http://www.huaxing-east.cn:2016/uploads/approval/issuedrawing/52/drawingattachments/20180218232347132.pdf">aaa</a>',
            ],
            [
                'name'      => '备注',
                'value'     => $inputs['remark'],
//                'value'     => '<a href="http://www.huaxing-east.cn:2016/uploads/approval/issuedrawing/52/drawingattachments/20180218232347132.pdf">aaa</a>',
            ],
            [
                'name'      => '图纸签收回执',
                'value'     => $inputs['image_urls'],
            ],
            [
                'name'      => '关联审批单',
                'value'     => $inputs['associatedapprovals'],
            ],
        ];
//        $form_component_values = '{name:\'测试1\', value:\'aaa\'}';
//        dd(json_encode($formdata));
        $form_component_values = json_encode($formdata);
        Log::info('process_code: ' . $process_code);
        Log::info('originator_user_id: ' . $originator_user_id);
        Log::info('dept_id: ' . $dept_id);
        Log::info('approvers: ' . $approvers);
        Log::info('cc_list: ' . $cc_list);
        $params = compact('method', 'session', 'v', 'format',
            'process_code', 'originator_user_id', 'dept_id', 'approvers', 'cc_list', 'cc_position', 'form_component_values');
        $data = [
//            'process_code' => '001'
        ];

        $c = new DingTalkClient();
        $req = new SmartworkBpmsProcessinstanceCreateRequest();
//        $req->setAgentId("41605932");
        $req->setProcessCode($process_code);
        $req->setOriginatorUserId($originator_user_id);
        $req->setDeptId("$dept_id");
        $req->setApprovers($approvers);
        if ($cc_list <> "")
        {
            $req->setCcList($cc_list);
            $req->setCcPosition("FINISH");
        }
//        $form_component_values = new FormComponentValueVo();
//        $form_component_values->name="请假类型";
//        $form_component_values->value="事假";
//        $form_component_values->ext_value="总天数:1";
        $req->setFormComponentValues("$form_component_values");
        $response = $c->execute($req, $session);
        return json_encode($response);

        $response = DingTalkController::post('https://eco.taobao.com/router/rest', $params, json_encode($data), false);
        return $response;
    }

    public static function processinstance_get($process_instance_id)
    {
        $method = 'dingtalk.smartwork.bpms.processinstance.get';
        $session = self::getAccessToken();
        $timestamp = time('2017-07-19 13:06:00');
        $format = 'json';
        $v = '2.0';

//        $process_code = 'PROC-FF6YT8E1N2-TTFRATBAPC9QE86BLRWM1-SUHHCXBJ-2';    // huaxing
//        $originator_user_id = $user->dtuserid;
//        $departmentList = json_decode($user->dtuser->department);
//        $dept_id = 0;
//        if (count($departmentList) > 0)
//            $dept_id = array_first($departmentList);

        $params = compact('method', 'session', 'v', 'format',
            'process_instance_id');
        $data = [
//            'process_instance_id' => $process_instance_id,
        ];
//        Log::info(json_encode($data));
        $response = HttpDingtalkEco::post("",
            $params, json_encode($data));
        return $response;

    }

    public static function processinstance_get2($process_instance_id)
    {
        $method = 'dingtalk.smartwork.bpms.processinstance.get';
        $session = self::getAccessToken_appkey();
        $timestamp = time('2017-07-19 13:06:00');
        $format = 'json';
        $v = '2.0';

        $params = compact('method', 'session', 'v', 'format',
            'process_instance_id');
        $data = [
//            'process_instance_id' => $process_instance_id,
        ];
//        Log::info(json_encode($data));
        $response = HttpDingtalkEco::post("",
            $params, json_encode($data));
        return $response;
    }
}
