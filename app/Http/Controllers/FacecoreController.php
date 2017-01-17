<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\util\HttpFacecore;

class FacecoreController extends Controller
{
    //
    public function urlfacedetect(Request $request)
    {
        // $data = [
        //     'url'   => 'http://static.dingtalk.com/media/lADOngciz80DIM0CWA_600_800.jpg'
        // ];
        // $response = HttpFacecore::post("/api/urlfacedetect",
        //     ['appkey' => '599a301d049287ace32a880feb06e474'], json_encode($data));
        // dd($response);
        // return $response;

    	// $image = imagecreatefromstring(file_get_contents('images/20161226_pic_540_779.jpg'));
    	// $exif = exif_read_data('images/20161226_pic_540_779.jpg');
    	// dd($exif);

        $data = [
            'faceimage'   => base64_encode(file_get_contents('images/20161226_pic_540_779.jpg'))
        ];
        $response = HttpFacecore::post("/api/facedetectcount",
            ['appkey' => '599a301d049287ace32a880feb06e474'], json_encode($data));
        dd($response);
        return $response;
    }
}
