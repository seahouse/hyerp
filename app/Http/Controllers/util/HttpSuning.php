<?php

namespace App\Http\Controllers\util;

use Config, Log;

Class HttpSuning
{
	public static function get($path, $params)
	{
		$url = self::joinParams($path, $params);
		$response = \Httpful\Request::get($url)->send();
		if ($response->hasErrors())
		{
			var_dump($response);
		}
		if ($response->body->errorCode != 0)
		{
			var_dump($response->body);
		}
		return $response->body;
	}

	public static function post($path, $params, $data)
	{
		$url = self::joinParams($path, $params);
		echo $url . "</br></br>";
		$aaa = urlencode($url);
        echo $aaa . "</br></br>";
        Log::info($url);
		$response = \Httpful\Request::post($url)
			->body($data)
//			->sendsJson()
			->send();
//		dd($response);
		if ($response->hasErrors())
		{
			var_dump($response);
		}
		if ($response->body->errorCode != 0)
		{
			var_dump($response->body);
		}
		return $response->body;
	}

	private static function joinParams($path, $params)
	{
		$url = "https://fopenapipre.cnsuning.com:8443" . $path;
		$str_params = '';
		if (count($params) > 0)
		{
//            $str_params = $str_params . "?";
//            foreach ($params as $key => $value)
//            {
//                $str_params = $str_params . $key . "=" . $value . "&";
//            }
//            $length = strlen($str_params);
//            if ($str_params[$length - 1] == '&')
//            {
//                $str_params = substr($str_params, 0, $length - 1);
//            }

			$url = $url . "?";
			foreach ($params as $key => $value)
			{
				$url = $url . $key . "=" . $value . "&";
			}
			$length = strlen($url);
			if ($url[$length - 1] == '&')
			{
				$url = substr($url, 0, $length - 1);
			}
		}

		Log::info($str_params);
        $str_params = urlencode($str_params);
        Log::info($str_params);
//        return $url . $str_params;
//		return $url . "?" . $str_params;
        return $url;
	}
}