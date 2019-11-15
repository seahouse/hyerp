<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use Auth, Log;

class HelperController extends Controller
{
    //

	/**
     * skip empty string value from the given pair array.
     *
     * @param  $items
     * @return $items
     */
    public static function skipEmptyValue($items)
    {
        //
		$items = array_where($items, function ($key, $value) {
			if ($value != "")
				return true;
		});
        
        return $items;
    }

    public function changeuser()
    {
        return view('changeuser');
//        if (!Auth::check())
//        {
//            Auth::loginUsingId(config('custom.changeuser_id'));
//        }
//        else
//        {
//            Auth::logout();
//            Auth::loginUsingId(config('custom.changeuser_id'));
//        }
//
//        return redirect('/');
    }

    public function changeuser_store(Request $request)
    {
        $user_id = 0;
        if ($request->has('user_id'))
            $user_id = $request->input('user_id');
        if ($user_id > 0)
        {
            Auth::logout();
            Auth::loginUsingId($user_id);
        }
        else
            dd('切换失败。');

        return redirect('/');
    }

    /**
     * 根据汉字获取首字母
     *
     * @param  $items
     * @return $items
     */
    public static function pinyin_long($zh){
        $ret = "";
        $s1 = iconv("UTF-8","gb2312", $zh);
        $s2 = iconv("gb2312","UTF-8", $s1);
        if($s2 == $zh){$zh = $s1;}
        for($i = 0; $i < strlen($zh); $i++){
            $s1 = substr($zh,$i,1);
            $p = ord($s1);
            if($p > 160){
                $s2 = substr($zh,$i++,2);
                $ret .= self::getfirstchar($s2);
            }else{
                $ret .= $s1;
            }
        }
        return $ret;
    }

    public static function getfirstchar($s0){
        $fchar = ord($s0{0});
        if($fchar >= ord("A") and $fchar <= ord("z") )return strtoupper($s0{0});

        // 使用iconv方法时，报iconv(): Detected an illegal character in input string错误。修改为mb_convert_encoding方法
        $s1 = mb_convert_encoding($s0, "GBK", "UTF-8");
//        $s1 = iconv("UTF-8","GBK", $s0);

        $s2 = iconv("gb2312","UTF-8", $s1);
        if($s2 == $s0){$s = $s1;}else{$s = $s0;}
        $asc = ord($s{0}) * 256 + ord($s{1}) - 65536;
        if($asc >= -20319 and $asc <= -20284) return "A";
        if($asc >= -20283 and $asc <= -19776) return "B";
        if($asc >= -19775 and $asc <= -19219) return "C";
        if($asc >= -19218 and $asc <= -18711) return "D";
        if($asc >= -18710 and $asc <= -18527) return "E";
        if($asc >= -18526 and $asc <= -18240) return "F";
        if($asc >= -18239 and $asc <= -17923) return "G";
        if($asc >= -17922 and $asc <= -17418) return "H";
        if($asc >= -17922 and $asc <= -17418) return "I";
        if($asc >= -17417 and $asc <= -16475) return "J";
        if($asc >= -16474 and $asc <= -16213) return "K";
        if($asc >= -16212 and $asc <= -15641) return "L";
        if($asc >= -15640 and $asc <= -15166) return "M";
        if($asc >= -15165 and $asc <= -14923) return "N";
        if($asc >= -14922 and $asc <= -14915) return "O";
        if($asc >= -14914 and $asc <= -14631) return "P";
        if($asc >= -14630 and $asc <= -14150) return "Q";
        if($asc >= -14149 and $asc <= -14091) return "R";
        if($asc >= -14090 and $asc <= -13319) return "S";
        if($asc >= -13318 and $asc <= -12839) return "T";
        if($asc >= -12838 and $asc <= -12557) return "W";
        if($asc >= -12556 and $asc <= -11848) return "X";
        if($asc >= -11847 and $asc <= -11056) return "Y";
        if($asc >= -11055 and $asc <= -10247) return "Z";
        return NULL;
    }
}
