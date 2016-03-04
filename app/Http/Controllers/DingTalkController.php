<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
use Cache;

class DingTalkController extends Controller
{
    //
    const CORP_ID = 'ding8414637331385d36';
    const CORP_SECRET = 'T3nba1syKPqaFxJPv9XZMSUGkLdHbEenF2wjEsdHQAV4_XDgp8X5NsHEfRCrlK5F';
    
    protected static $jsapi_ticket;
    
    public function __construct()
    {
        try {
            $corpid = 'ding8414637331385d36';
            $corpsecret = 'T3nba1syKPqaFxJPv9XZMSUGkLdHbEenF2wjEsdHQAV4_XDgp8X5NsHEfRCrlK5F';
            // Get access_token
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
                $response = response()->view(self::CONFIG['viewname'], compact('ddconfig'));
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
                //$id = DB::table('users')->where('ding_userid', '=', $info->userid)->value('id');
                $testarray = ['superadmin', 'admin', 'user1'];
                $username = $testarray[array_rand($testarray)];
                $id = DB::table('users')->where('name', '=', $username)->value('id');
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
                    'username' => $username,
                    'id' => $id,
                ];
                dd($userInfo);
                session()->put('user', $userInfo);
                $response = redirect()->to(request()->get('backurl'));
            }
        }
        catch (\Exception $e) {
            dd($e);
        }
    }
    
    public function index()
    {
//         $response = "";
        
//         try {
//             if (Request()->has('error')) {
//                 $response = response(Request()->get('error'));
//             }
//             else if (Request()->has('code')) {
//                 // Get user ID by auth code
//                 $code = Request()->get('code');
//                 $url = 'https://oapi.dingtalk.com/user/getuserinfo';
//                 $params = ['access_token' => self::$access_token, 'code' => $code];
//                 $reply = $this->get($url, $params);
//                 $userid = $reply->userid;
//                 $deviceId = $reply->deviceId;
//                 $is_sys = $reply->is_sys;
//                 $sys_level = $reply->sys_level;
        
//                 // Get user information
//                 $url = 'https://oapi.dingtalk.com/user/get';
//                 $params = ['access_token' => self::$access_token, 'userid' => $userid];
//                 $reply = $this->get($url, $params);
//                 $name = $reply->name;
//                 $mobile = $reply->mobile;
//                 $isAdmin = $reply->isAdmin;
//                 $isBoss = $reply->isBoss;
//                 $dingId = $reply->dingId;
        
//                 $response = response('userid:' . $userid . '<br>'
//                     .'deviceId: ' . $deviceId . '<br>'
//                     .'is_sys: ' . $is_sys . '<br>'
//                     .'sys_level: ' . $sys_level . '<br>'
//                     .'name: ' . $name . '<br>'
//                     .'mobile: ' . $mobile . '<br>'
//                     .'isAdmin: ' . $isAdmin . '<br>'
//                     .'isBoss: ' . $isBoss . '<br>'
//                     .'dingId: ' . $dingId . '<br>'
//                 );
//             }
//             else {
//                 // Calc signature
//                 $corpid = self::CORP_ID;
//                 $access_token = self::$access_token;
//                 $jsapi_ticket = self::$jsapi_ticket;
//                 $url = Request()->url();
//                 $timestamp  = time();
//                 $noncestr   = substr(md5(time()), 0, 16);
//                 $array = compact('jsapi_ticket', 'url', 'timestamp', 'noncestr');
//                 ksort($array);
//                 $rawstring  = urldecode(http_build_query($array));
//                 $signature = sha1($rawstring);
        
//                 $ddconfig = compact('corpid', 'timestamp', 'noncestr', 'signature', 'access_token', 'rawstring');
        
//                 $response = response()->view('dingtalk', compact('ddconfig'));
//             }
//         }
//         catch (\Exception $e) {
//             dd($e);
//         }
        
//         return $response;
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
