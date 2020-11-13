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
    public function send(Request $request)
    {
        // Log::info($request);
        $data = [
            'success' => true,
            'msg' => 'ok',
            'code' => rand(1000, 9999),
            'phonenum' => $request->phonenum,
        ];

        return response()->json($data);

        AlibabaCloud::accessKeyClient('LTAI4G9stCP8utMpMHZDGdvC', '6d67vYxyBy5DW6JGEPVlgpcp3EbdK7')
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
                    'PhoneNumbers' => "13656188391",
                    'SignName' => "HX网站",
                    'TemplateCode' => "SMS_205432234",
                    'TemplateParam' => "{'code': '1234'}",
                    'SmsUpExtendCode' => "1234",
                    'OutId' => "1234",
                ],])
                ->request();
            print_r($result->toArray());
        } catch (ClientException $e) {
            echo $e->getErrorMessage() . PHP_EOL;
        } catch (ServerException $e) {
            echo $e->getErrorMessage() . PHP_EOL;
        }
    }
}
