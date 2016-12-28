<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\util\HttpFaceplusplus;
use App\Models\System\Image;

class FaceplusplusController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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

    public function detect(Request $request)
    {
        $data = [
            'api_key'   => 'eLObusplEGW0dCfBDYceyhoAdvcEaQtk',
            'api_secret'    => 'bWJAjmtylVZ6A8Ik4_vC1xBO3X3cyKJT',
            'image_url'    => 'http://static.dingtalk.com/media/lADOlob6ns0CgM0CgA_640_640.jpg'
        ];
        // $str = "api_key=eLObusplEGW0dCfBDYceyhoAdvcEaQtk&api_secret=bWJAjmtylVZ6A8Ik4_vC1xBO3X3cyKJT&image_url1=http://static.dingtalk.com/media/lADOlob6ns0CgM0CgA_640_640.jpg&image_url2=http://static.dingtalk.com/media/lADOlob7MM0CgM0CgA_640_640.jpg";
        $response = HttpFaceplusplus::post("/detect",
            $data, "");
        dd($response);
        return $response;
    }

    public function compare(Request $request)
    {
        // dd($request->all());
        $data = [
            'api_key'   => 'eLObusplEGW0dCfBDYceyhoAdvcEaQtk',
            'api_secret'    => 'bWJAjmtylVZ6A8Ik4_vC1xBO3X3cyKJT',
            'image_url1'    => 'http://static.dingtalk.com/media/lADOlob6ns0CgM0CgA_640_640.jpg',
            'image_url2'    => 'http://static.dingtalk.com/media/lADOlob7MM0CgM0CgA_640_640.jpg'
        ];
        $str = "api_key=eLObusplEGW0dCfBDYceyhoAdvcEaQtk&api_secret=bWJAjmtylVZ6A8Ik4_vC1xBO3X3cyKJT&image_url1=http://static.dingtalk.com/media/lADOlob6ns0CgM0CgA_640_640.jpg&image_url2=http://static.dingtalk.com/media/lADOlob7MM0CgM0CgA_640_640.jpg";
        $response = HttpFaceplusplus::post("/compare",
            $data, "");
        dd($response);
        return $response;
    }

    public function search(Request $request)
    {
        $data = [
            'api_key'   => 'eLObusplEGW0dCfBDYceyhoAdvcEaQtk',
            'api_secret'    => 'bWJAjmtylVZ6A8Ik4_vC1xBO3X3cyKJT',
            'face_token'    => 'cdc46592057b91a696a9e72ac59d2bae'
        ];
        // $str = "api_key=eLObusplEGW0dCfBDYceyhoAdvcEaQtk&api_secret=bWJAjmtylVZ6A8Ik4_vC1xBO3X3cyKJT&image_url1=http://static.dingtalk.com/media/lADOlob6ns0CgM0CgA_640_640.jpg&image_url2=http://static.dingtalk.com/media/lADOlob7MM0CgM0CgA_640_640.jpg";
        $response = HttpFaceplusplus::post("/search",
            $data, "");
        dd($response);
        return $response;
    }

    public function faceset_create(Request $request)
    {
        $images = Image::get();
        foreach ($images as $image) {
            # code...
            // dd(file($image->path));
            $data = [
                'api_key'   => 'eLObusplEGW0dCfBDYceyhoAdvcEaQtk',
                'api_secret'    => 'bWJAjmtylVZ6A8Ik4_vC1xBO3X3cyKJT',
                // 'image_file'    => fread(fopen($image->path, "rb"), filesize($image->path))
                'image_url' => url($image->path)
            ];

            $response = HttpFaceplusplus::post("/detect",
                array(), $data);
            dd($response);
        }

        $data = [
            'api_key'   => 'eLObusplEGW0dCfBDYceyhoAdvcEaQtk',
            'api_secret'    => 'bWJAjmtylVZ6A8Ik4_vC1xBO3X3cyKJT'
        ];
        // $str = "api_key=eLObusplEGW0dCfBDYceyhoAdvcEaQtk&api_secret=bWJAjmtylVZ6A8Ik4_vC1xBO3X3cyKJT&image_url1=http://static.dingtalk.com/media/lADOlob6ns0CgM0CgA_640_640.jpg&image_url2=http://static.dingtalk.com/media/lADOlob7MM0CgM0CgA_640_640.jpg";
        $response = HttpFaceplusplus::post("/faceset/create",
            $data, "");
        dd($response);
        return $response;
    }
}
