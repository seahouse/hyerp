<?php

namespace App\Http\Controllers\util;

use AlibabaCloud\Client\AlibabaCloud;
use AlibabaCloud\Client\Exception\ClientException;
use AlibabaCloud\Client\Exception\ServerException;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

// Download：https://github.com/aliyun/openapi-sdk-php
// Usage：https://github.com/aliyun/openapi-sdk-php/blob/master/README.md

class AliyunSMSController extends Controller
{
    /**
     * 发送验证信息
     */
    public function sendsmscode(Request $request)
    {
        $code = rand(1000, 9999);

        // {Message: "OK", RequestId: "81E325BF-062B-4AE8-A104-F477F88A1A35", BizId: "618121105352368202^0", Code: "OK"}
        // 模拟数据
        // $data = ['Message' => 'OK', 'Code' => 'OK'];
        $data = static::send($request->phonenum, config('custom.aliyun.sms_signname'), config('custom.aliyun.sms_templatecode'), json_encode(['code' => $code]));
        $data['vcode'] = $code;
        Log::info($data);
        return response()->json($data);
    }

    /**
     * 发送信息
     */
    protected static function send($phone, $signname, $templatecode, $param)
    {
        AlibabaCloud::accessKeyClient(config('custom.aliyun.keyid'), config('custom.aliyun.keysecret'))
            ->regionId('cn-hangzhou')
            ->asDefaultClient();

        try {
            $result = AlibabaCloud::rpc()
                ->product('Dysmsapi')
                // ->scheme('https') // https | http
                ->version('2017-05-25')
                ->action('SendSms')
                ->method('POST')
                ->host('dysmsapi.aliyuncs.com')
                ->options(['query' => [
                    'RegionId' => "cn-hangzhou",
                    'PhoneNumbers' => $phone,
                    'SignName' => $signname,
                    'TemplateCode' => $templatecode,
                    'TemplateParam' => $param,
                    // 'SmsUpExtendCode' => "1234",
                    // 'OutId' => "1234",
                ],])
                ->request();

            $data = $result->toArray();
            return $data;
        } catch (ClientException $e) {
            return ['Code' => 'NG', 'Message' => $e->getErrorMessage()];
        } catch (ServerException $e) {
            return ['Code' => 'NG', 'Message' => $e->getErrorMessage()];
        }
    }
}
