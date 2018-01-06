<?php

namespace App\Http\Controllers;

use App\Http\Controllers\util\HttpDingtalkEco;
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
//        $corpid = 'ding6ed55e00b5328f39';
//        $corpsecret = 'gdQvzBl7IW5f3YUSMIkfEIsivOVn8lcXUL_i1BIJvbP4kPJh8SU8B8JuNe8U9JIo';

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

    public static function register_call_back_user()
    {
        // Cache::flush();
        $access_token = self::getAccessToken();
        // dd(str_random(32));

        // self::$ENCODING_AES_KEY = str_random(43);
        $data = [
            'call_back_tag' => ['user_add_org', 'user_modify_org', 'user_leave_org'],
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
                if (isset($user->orgEmail) && !empty($user->orgEmail))
                    UsersController::synchronizedtuser($user);
//                if ($user->userid === "0823230530894101")
//                    dd(isset($user->orgEmail));
//                echo '<li> ' . $user->name  . ' ' . $user->orgEmail .  '</li>';
//                dd($user);
//                UsersController::synchronizedtuser($user);
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

    public function issuedrawing()
    {
        Cache::flush();
        $user = Auth::user();
        $method = 'dingtalk.smartwork.bpms.processinstance.create';
        $session = self::getAccessToken();
        $timestamp = time('2017-07-19 13:06:00');
        $format = 'json';
        $v = '2.0';

//        $process_code = 'PROC-EF6YRO35P2-7MPMNW3BNO0R8DKYN8GX1-2EACCA5J-6';     // hyerp
//        $process_code = 'PROC-EF6YJDXRN2-V88CLW5WMN8R63JUE7XW3-M0DE5SQI-2K';    // huaxing
        $process_code = 'PROC-FF6YT8E1N2-TTFRATBAPC9QE86BLRWM1-SUHHCXBJ-2';    // huaxing
        $originator_user_id = $user->dtuserid;
        $departmentList = json_decode($user->dtuser->department);
        $dept_id = 0;
        if (count($departmentList) > 0)
            $dept_id = array_first($departmentList);
        $approvers = $user->dtuserid;
        $formdata = [
            [
                'name'      => '设计部门',
                'value'     => '工艺一室',
            ],
            [
                'name'      => '项目名称',
                'value'     => 'aaaa',
            ],
            [
                'name'      => '制作概述',
                'value'     => 'bbbb',
            ],
            [
                'name'      => '吨位（吨）',
                'value'     => '200',
            ],
            [
                'name'      => '项目编号',
                'value'     => 'cccc',
            ],
            [
                'name'      => '制作公司',
                'value'     => '无锡制造中心',
            ],
            [
                'name'      => '材料供应方',
                'value'     => '华星东方',
            ],
            [
                'name'      => '图纸校核人',
                'value'     => '张三',
            ],
            [
                'name'      => '要求发货日',
                'value'     => '2018-1-3',
            ],
            [
                'name'      => '图纸份数（份）',
                'value'     => '3',
            ],
//            [
//                'name'      => '目录上传，图纸邮寄',
//                'value'     => 'ffff',
//            ],
            [
                'name'      => '图纸签收回执',
                'value'     => '["http://pic4.nipic.com/20091217/3885730_124701000519_2.jpg"]',
            ],
        ];
//        $form_component_values = '{name:\'测试1\', value:\'aaa\'}';
//        dd(json_encode($formdata));
        $form_component_values = json_encode($formdata);
        $params = compact('method', 'session', 'v', 'format',
            'process_code', 'originator_user_id', 'dept_id', 'approvers', 'form_component_values');
        $data = [
//            'process_code' => '001'
        ];
        $response = DingTalkController::post('https://eco.taobao.com/router/rest', $params, json_encode($data), false);
        dd($response);
    }
}
