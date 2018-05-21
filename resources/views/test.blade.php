@extends('app')

@section('title')
APP跳转
@endsection

@section('main')

<html>


<body style="margin: 0;overflow: hidden">
<div id="tmPlayer" class="tmPlayer" style="height: 557px; width: 100%; height: 100%"></div>


</body>
</html>


    @endsection

@section('script')
    <script src="https://g.alicdn.com/dingding/open-develop/1.9.0/dingtalk.js"></script>
    <script type="text/javascript">
        jQuery(document).ready(function () {
            var appname = '{{ config('custom.dingtalk.thirdapps.aishu.android') }}';
            if ({{ Agent::is('iPhone') }})
                appname = '{{ config('custom.dingtalk.thirdapps.aishu.ios') }}';

            dd.config({
                agentId: '{!! array_get($config, 'agentId') !!}', // 必填，微应用ID
                corpId: '{!! array_get($config, 'corpId') !!}',//必填，企业ID
                timeStamp: {!! array_get($config, 'timeStamp') !!}, // 必填，生成签名的时间戳
                nonceStr: "{!! array_get($config, 'nonceStr') !!}", // 必填，生成签名的随机串
                signature: "{!! array_get($config, 'signature') !!}", // 必填，签名
                jsApiList: ['runtime.info',
                    'device.launcher.launchApp',
                    'device.notification.confirm',
                    'biz.navigation.close'] // 必填，需要使用的jsapi列表
            });
        });

        dd.ready(function() {

//            // if the page is not first history.back, exit it.
//            if (history.length > 1)
//            {
//                dd.biz.navigation.close({
//                    onSuccess : function(result) {
//                        /*result结构
//                         {}
//                         */
//                    },
//                    onFail : function(err) {}
//                });
//            }


            dd.device.launcher.launchApp({
                app: appname, //iOS:应用scheme;Android:应用包名
//                activity :'DetailActivity', //仅限Android，打开指定Activity，可不传。如果为空，就打开App的Main入口Activity
                onSuccess : function(data) {
                    /*
                     {
                     result: true //true 唤起成功 false 唤起失败
                     }
                     */
                },
                onFail : function(err) {
                    alert('打开app失败.');
                }
            });
        });

        dd.error(function(error) {
            alert('dd.error: ' + JSON.stringify(error));
            {{ Cache::flush() }}       // add by seahouse, 2018/1/10
            {{--
            $.ajax({
                type:"GET",
                url:"{{ url('dingtalk/cacheflush') }}",
                error:function(xhr, ajaxOptions, thrownError){
                    alert('error');
                    alert(xhr.status);
                    alert(xhr.responseText);
                    alert(ajaxOptions);
                    alert(thrownError);
                },
                success:function(){
                    {{ Log::info('cacheflush') }}
                },
            });
            --}}
        });

    </script>

@endsection
