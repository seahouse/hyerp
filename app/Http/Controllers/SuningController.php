<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\util\HttpSuning;

class SuningController extends Controller
{
    private $pubKey = "-----BEGIN PUBLIC KEY-----
MIGfMA0GCSqGSIb3DQEBAQUAA4GNADCBiQKBgQDpbpdWvLvMzfYB8gDoBSxtJ72e
w0HOa6zgSd1MC+EtE2MnsKilZzg6SZQ69Uq4Y0YEakkXUtKgZJQ0NSo8jBG3f88O
8zAgpG/5ylzauCDxEhE+1GgYz5EWJ5tV+KOT8AjFJdQ8cDJjbc9C5dZKAkkreNmQ
dS4q46YXoo0CDuYehwIDAQAB
-----END PUBLIC KEY-----";

    private $priKey = "-----BEGIN PRIVATE KEY-----
MIICdwIBADANBgkqhkiG9w0BAQEFAASCAmEwggJdAgEAAoGBAOlul1a8u8zN9gHy
AOgFLG0nvZ7DQc5rrOBJ3UwL4S0TYyewqKVnODpJlDr1SrhjRgRqSRdS0qBklDQ1
KjyMEbd/zw7zMCCkb/nKXNq4IPESET7UaBjPkRYnm1X4o5PwCMUl1DxwMmNtz0Ll
1koCSSt42ZB1LirjpheijQIO5h6HAgMBAAECgYEAkQNT3t1maWe1oSW+7GRyYekj
QiKYmeyIO9mHMXvbyg3WNkznp8FEy+jzveCuJ3f8gjeo6mVYVa3F59zzPSPiyIVS
d3wj0zaQboCEUhUdMAhRCD2CYv8dzgxFlM/qHw17HeDOSIcXrxmy1KU2qbWA3b5x
CZ+sJG459hU9czev/8ECQQD0jdpXM15Z9cawg2iuMdCgqBc4kAmpeIwloXCgOoPH
ebRX4xfJPL13WA5H5dAzog4Sjzb9ixkiIhznY6b6dz0nAkEA9Ft6RpBkCFffmqIj
s4FFBEPByPy++YWlYOg9JyuODsCOOuRGp6ZgE5EKWWsArpmDNI+HW77JLa8jQwI3
hu2voQJAHeeSwYMIkJubVk5baKGyz1J+tG34oH6bNKbPLOi64JOjV/PvHq6MxKFB
7czObuHsMpzMrqhpxGDDhBANhSc7lQJBAMdsuPhJ8znT7YuX9CcVwyvd2JOdooam
Cfhy0SXcqA1mHS33C6rbX+HYJ2geqenVI32L/d5kcG+2VcLkVkjqRsECQCuug0oN
JyK9UsSV/xgTuZEzAU5GgZBgdRVUW6/Agq+NGuU66oOEXuILAa4M2Jgtz0x3Ttk5
U0Nr19uAFaIM5sE=
-----END PRIVATE KEY-----";

    /**
     * 生成签名
     *
     * @param string 签名材料
     * @param string 签名编码（base64/hex/bin）
     * @return 签名值
     */
    public function sign($data, $code = 'base64'){
        $ret = false;
        if (openssl_sign($data, $ret, $this->priKey)){
            $ret = $this->_encode($ret, $code);
        }
        return $ret;
    }


    //
    public function gateway()
    {
        $b0 = file_get_contents("rsa_private_key.pem");
        echo $b0 . "</br></br>";
        $timestamp  = date('Ymdhis');
        $transNo = date('Ymdhis') . str_random(5);
        $serialNo = str_random();
        $custName = '张三';
        $custMobile = '15852750586';
        $idType = '01';
        $idNo = '321322198512286054';
        $loanApplyAmount = 1000.0;
        $loanTerm = 9;
        $termType = '02';
        $loanRate = 0.00012;
        $payWay = 'R9925';
        $accountType = '01';
        $accountName = '阿飞大法师法';
        $bankCardNum = '8000000000000000' . '211';
        $bankName = '00';

        $a0 = [
            'version=1.0',
            'app_id=yfbm70058709e2017060701',
            'service=suning.fosps.pls.ptdpc.savecustomerptdpc',
            'timestamp=\'' . $timestamp . '\'',
            'partnerNo=18',
            'bizCode=113',
            'productNo=04',
            'intfNo=I001',
            'transNo=' . $transNo,
            'serialNo=' . $serialNo,
            'custName=' . $custName,
            'custMobile=' . $custMobile,
            'idType=' . $idType,
            'idNo=' . $idNo,
            'loanApplyAmount=' . $loanApplyAmount,
            'loanTerm=' . $loanTerm,
            'termType=' . $termType,
            'loanRate=' . $loanRate,
            'payWay=' . $payWay,
            'accountType=' . $accountType,
            'accountName=' . $accountName,
            'bankCardNum=' . $bankCardNum,
            'bankName=' . $bankName,
        ];
        $ret2 = md5(http_build_query($a0));
        sort($a0);
//        ksort($a0);
        $a1 = implode('&', $a0);
        echo $a1 . "</br>";
//        $a1 = serialize($a0);
//        $a2 = strtoupper(md5(utf8_encode($a1)));
        $a2 = strtoupper(md5($a1));
//        $a3 = $this->sign($a2);
        echo $a2 . "</br>";

//        $pi_key = openssl_pkey_get_private($this->priKey);
        $pi_key = openssl_pkey_get_private($b0);
        $pu_key = openssl_pkey_get_public($this->pubKey);
        echo "pu_key: " . $pu_key . "</br>";
        echo "pu_key2: " . openssl_get_publickey($this->pubKey) . "</br></br>";

        $ret = '';
        if (openssl_sign($a2, $ret, $pi_key)){
            openssl_free_key($pi_key);
//            dd($ret);
            $ret = base64_encode($ret);
            echo $ret . "</br>";
//            $ret = $this->_encode($ret, $code);
        }

        $encrypted = '';
        $decrypted = '';
        openssl_private_encrypt($a2, $encrypted, $pi_key);
        $encrypted = base64_encode($encrypted);
        echo $encrypted . "</br>";
//        dd($encrypted);
//        dd(openssl_verify($a2, $encrypted, $this->pubKey));
        openssl_public_decrypt(base64_decode($encrypted),$decrypted,$pu_key);
        echo $decrypted . "</br>";


        $data = [
//            'version'       => '1.0',
//            'app_id'       => 'yfbm70058709e2017060701'
        ];

        $aa0 = array(
            'version'       => '1.0',
            'app_id'        => 'yfbm70058709e2017060701',
            'sign_type'     => 'RSA2',
            'signkey_index' => '0001',
//                'sign'           => $encrypted,
            'sign'           => $ret,
            'service'       => 'suning.fosps.pls.ptdpc.savecustomerptdpc',
            'timestamp'     => $timestamp,
            'partnerNo'     => '18',
            'bizCode'       => '113',
            'productNo'     => '04',
            'intfNo'        => 'I001',
            'transNo'       => $transNo,
            'serialNo'      => $serialNo,
            'custName'      => $custName,
            'custMobile'    => $custMobile,
            'idType'        => $idType,
            'idNo'          => $idNo,
            'loanApplyAmount'   => $loanApplyAmount,
            'loanTerm'      => $loanTerm,
            'termType'      => $termType,
            'loanRate'      => $loanRate,
            'payWay'        => $payWay,
            'accountType'   => $accountType,
            'accountName'   => $accountName,
            'bankCardNum'   => $bankCardNum,
            'bankName'      => $bankName,
        );
//        dd($aa0);

        $response = HttpSuning::post("/gateway.htm",
            array(
                'version'       => '1.0',
                'app_id'        => 'yfbm70058709e2017060701',
                'sign_type'     => 'RSA2',
                'signkey_index' => '0001',
//                'sign'           => $encrypted,
                'sign'           => $ret,
                'service'       => 'suning.fosps.pls.ptdpc.savecustomerptdpc',
                'timestamp'     => $timestamp,
                'partnerNo'     => '18',
                'bizCode'       => '113',
                'productNo'     => '04',
                'intfNo'        => 'I001',
                'transNo'       => $transNo,
                'serialNo'      => $serialNo,
                'custName'      => $custName,
                'custMobile'    => $custMobile,
                'idType'        => $idType,
                'idNo'          => $idNo,
                'loanApplyAmount'   => $loanApplyAmount,
                'loanTerm'      => $loanTerm,
                'termType'      => $termType,
                'loanRate'      => $loanRate,
                'payWay'        => $payWay,
                'accountType'   => $accountType,
                'accountName'   => $accountName,
                'bankCardNum'   => $bankCardNum,
                'bankName'      => $bankName,
                ), json_encode($data));
        dd($response);
    }

    public function verifysign()
    {
        return view('VerifySign');
    }
}
