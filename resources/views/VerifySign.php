<?php
/**
 * User: Dennis
 */
header("Content-type: text/html; charset=utf-8");

/*传入签名和签名参数sign;param参与签名的参数需md5,配置下公钥即可*/

/*
 * 例子
$sign = 'bNCuHd9kpCgR4mPT2/QtEU1t/W6MU1bl0T90tx5PG83c4NzLsa1s9m9v3kzLI59aQplOvyMAaXuNpLuxfv4GqAhEpdUbVYOYrzVUaFEfxjRq6h248boo6KiqlmIo+MtTknew+AZnGkBUzbLRKPdIxf2EOZ2/YgRxFTApf4c7j98=';
$param = 'CE3029669A365816B54E839C55BBB2B1';
*/
$sign = 'jEe4hiQe3VHAplDMTJC+f6+J5ycW23bmRyg0RGqfbWMz8wEix6u2ba/n0QG8ayxtm8wZjCsAamcdOYp354nF8YSvmIMC7/v2bjhzc/EUH4aWENx0/qLyegwm2bJtgfz5pz1RW5mAJJkX0V8mt9Nqb7jZ01B+lxW/6tWTazr13qM=';
$param = '4D85EE79E5E79660B916B3417E755834';

$verifySign = new VerifySign();
$result = $verifySign->check($sign, $param);
if (!$result) {
    echo '验签失败';
} else {
    echo '验签成功';
}


class VerifySign
{
    private $public_key;

    function __construct()
    {

        //配置下公钥
        $this->public_key = 'MIGfMA0GCSqGSIb3DQEBAQUAA4GNADCBiQKBgQDpbpdWvLvMzfYB8gDoBSxtJ72ew0HOa6zgSd1MC+EtE2MnsKilZzg6SZQ69Uq4Y0YEakkXUtKgZJQ0NSo8jBG3f88O8zAgpG/5ylzauCDxEhE+1GgYz5EWJ5tV+KOT8AjFJdQ8cDJjbc9C5dZKAkkreNmQdS4q46YXoo0CDuYehwIDAQAB';
        //例子
        //$this->public_key = 'MIGfMA0GCSqGSIb3DQEBAQUAA4GNADCBiQKBgQCzL+D+aS0nS/C1NPcs9QiT/a5g9ka0XVnNfh6bmziYvZ8nv1ExPbinP1Yv2RG5sWL7JS12I38wLKWJSCO2GWdPjkb0/FrfT8IrAlZO5gJFMFodADxKvOguKu1r28QXHI0BqrBcBYy420kMi/0cm/3dW6i/248Q1qg71tRZPT+LwwIDAQAB';
    }

    /**
     * 验签方法
     * @param $arr
     * @return boolean
     */
    public function check($sign, $param)
    {
        $sign = $this->urlsafe_b64decode($sign);
        $public_key = $this->urlsafe_b64($this->public_key);
        $public_key = $this->format_secret_key($public_key);
        //调用openssl内置方法验签，返回bool值
        try {
            $result = openssl_verify($param, $sign, $public_key);
//            var_dump($result);exit;
            if (!$result) {
                echo "error: " . openssl_error_string();
                exit;
            }

            return $result;
        } catch (Exception $e) {
            print $e->getMessage();
            exit();
        }
    }

    protected function urlsafe_b64decode($string)
    {
        $data = str_replace(array('-', '_'), array('+', '/'), $string);
        $mod4 = strlen($data) % 4;
        if ($mod4) {
            $data .= substr('====', $mod4);
        }
        return base64_decode($data);
    }


    protected function urlsafe_b64($string)
    {
        $data = str_replace(array('-', '_'), array('+', '/'), $string);
        $mod4 = strlen($data) % 4;
        if ($mod4) {
            $data .= substr('====', $mod4);
        }
        return $data;
    }

    protected function format_secret_key($secret_key)
    {
        //64个英文字符后接换行符"\n",最后再接换行符"\n"
        $key = (wordwrap($secret_key, 64, "\n", true)) . "\n";
        //添加pem格式头和尾
        $pem_key = "-----BEGIN PUBLIC KEY-----\n$key-----END PUBLIC KEY-----\n";
        return $pem_key;
    }

}

?>