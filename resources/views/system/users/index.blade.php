@extends('navbarerp')

@section('main')
    <div class="panel-heading">
        @if (Auth::user()->can('system_user_maintain'))
            <a href="users/create" class="btn btn-sm btn-success">新建</a>
        @endif
        <div class="pull-right" style="padding-top: 4px;">
            <a href="{{ URL::to('system/roles') }}" class="btn btn-sm btn-success"><i class="fa fa-plus"></i> {{'角色管理', [], 'layouts'}}</a>
{{--
            <a href="{{ URL::to('system/images') }}" class="btn btn-sm btn-success"><i class="fa fa-plus"></i> {{'与钉钉强绑定', [], 'layouts'}}</a>
--}}
        </div> 
    </div>

    <div class="panel-body">
@if (Auth::user()->email == "admin@admin.com")
        {!! Form::button('与钉钉取消绑定', ['class' => 'btn btn-default btn-sm pull-right', 'id' => 'btnCancelBindDT']) !!}
        {!! Form::button('3333', ['class' => 'btn btn-default btn-sm pull-right', 'id' => 'btnTest']) !!}
            {!! Form::button('聊天2', ['class' => 'btn btn-default btn-sm pull-right', 'id' => 'btnPickConversation']) !!}
        <a href="{{ URL::to('dingtalk/delete_call_back') }}">与钉钉取消绑定</a>

        {!! Form::open(['url' => '/system/users/bingdingtalk', 'class' => 'pull-right']) !!}
            {!! Form::submit('与钉钉强绑定', ['class' => 'btn btn-default btn-sm']) !!}            
        {!! Form::close() !!}

        {!! Form::open(['url' => '/dingtalk/receive', 'class' => 'pull-right']) !!}
            {!! Form::submit('与钉钉强绑定222', ['class' => 'btn btn-default btn-sm']) !!}            
        {!! Form::close() !!}

        {!! Form::open(['url' => '/faceplusplus/detect', 'class' => 'pull-right']) !!}
            {!! Form::submit('人脸监测', ['class' => 'btn btn-default btn-sm']) !!}            
        {!! Form::close() !!}

        {!! Form::open(['url' => '/faceplusplus/faceset_create', 'class' => 'pull-right', 'files' => true]) !!}
            {!! Form::submit('人脸集合', ['class' => 'btn btn-default btn-sm']) !!}            
        {!! Form::close() !!}

        {!! Form::open(['url' => '/faceplusplus/compare', 'class' => 'pull-right']) !!}
            {!! Form::hidden('api_key', 'eLObusplEGW0dCfBDYceyhoAdvcEaQtk', []) !!}
            {!! Form::hidden('api_secret', 'bWJAjmtylVZ6A8Ik4_vC1xBO3X3cyKJT', []) !!}
            {!! Form::hidden('image_url1', 'http://static.dingtalk.com/media/lADOlob6ns0CgM0CgA_640_640.jpg', []) !!}
            {!! Form::hidden('image_url2', 'http://static.dingtalk.com/media/lADOlob7MM0CgM0CgA_640_640.jpg', []) !!}
            {!! Form::submit('人脸对比测试', ['class' => 'btn btn-default btn-sm']) !!}            
        {!! Form::close() !!}

        {!! Form::open(['url' => '/facecore/urlfacedetect', 'class' => 'pull-right']) !!}
            {!! Form::submit('人头数监测', ['class' => 'btn btn-default btn-sm']) !!}            
        {!! Form::close() !!}

        {!! Form::open(['url' => '/cloudwalk/face_tool_detect', 'class' => 'pull-right']) !!}
            {!! Form::submit('云从科技', ['class' => 'btn btn-default btn-sm']) !!}            
        {!! Form::close() !!}


        {!! Form::open(['url' => url('/dingtalk/chat_create'), 'class' => 'pull-right']) !!}
            {!! Form::submit('聊天', ['class' => 'btn btn-default btn-sm']) !!}
        {!! Form::close() !!}


        <form method="POST" action="http://localhost:82/dingtalk/receive" class="pull-right">
            <input class="btn btn-default btn-sm" type="submit" value="4444">            
        </form>

@endif
    </div>    

    @if ($users->count())
    <table class="table table-striped table-hover table-condensed">
        <thead>
            <tr>
                <th>姓名</th>
                <th>邮箱</th>
                <th>钉钉员工号</th>
                <th>部门</th>
                <th>职位</th>
                <th>老系统姓名</th>
                <th>角色</th>
                <th>操作</th>
            </tr>
        </thead>
        <tbody>
            @foreach($users as $user)
                <tr>
                    <td>
                        {{ $user->name }}
                    </td>
                    <td>
                        {{ $user->email }}
                    </td>
                    <td>
                        {{ $user->dtuserid }}
                    </td>
                    <td>
                        @if (isset($user->dept->name)) {{ $user->dept->name }} @endif
                    </td>
                    <td>
                        {{ $user->position }}
                    </td>
                    <td>
                        @if (isset($user->userold->user_hxold))
                            {{ $user->userold->user_hxold->name }}
                        @endif
                        <a href="{{ url('/system/users/' . $user->id . '/edituserold') }}" class="btn btn-default btn-sm" target="_blank">设置</a>
                    </td>
                    <td>
                        <a href="{{ URL::to('/system/users/'.$user->id.'/roles') }}">明细</a>
{{--                        @if (isset($user->role->display_name)) {{ $user->role->display_name }} @endif --}}
                    </td>
                    <td>
                        <a href="{{ URL::to('/system/users/'.$user->id.'/edit') }}" class="btn btn-success btn-sm pull-left">编辑</a>
                        <a href="{{ URL::to('/system/users/'.$user->id.'/editpass') }}" class="btn btn-success btn-sm pull-left">修改密码</a>
{{--                        <a href="{{ URL::to('/system/users/'.$user->id.'/editrole') }}" class="btn btn-success btn-sm pull-left">编辑角色</a> --}}
                        {!! Form::open(array('route' => array('system.users.destroy', $user->id), 'method' => 'delete', 'onsubmit' => 'return confirm("确定删除此记录?");')) !!}
                            {!! Form::submit('删除', ['class' => 'btn btn-danger btn-sm']) !!}
                        {!! Form::close() !!}
                    </td>
                </tr>
            @endforeach
        </tbody>

    </table>
    {!! $users->render() !!}
    @else
    <div class="alert alert-warning alert-block">
        <i class="fa fa-warning"></i>
        {{'无记录', [], 'layouts'}}
    </div>
    @endif    

@endsection

@section('script')
    <script src="https://g.alicdn.com/ilw/ding/0.7.5/scripts/dingtalk.js"></script>

    <script type="text/javascript">
        jQuery(document).ready(function(e) {
            $("#btnCancelBindDT").click(function() {
                $.ajax({
                    type: "GET",
                    url: "{!! url('dingtalk/delete_call_back') !!}",
                    success: function(result) {
                        // alert(result);
                        // alert(result.errmsg);
                        if (result.errcode == 0)
                        {
                            alert("取消绑定成功.");
                        }
                        else
                            alert(JSON.stringify(result));

                    },
                    error: function(xhr, ajaxOptions, thrownError) {
                        alert(JSON.stringify(xhr));
                    }
                });
            });

            $("#btnTest").click(function() {
                alert('btnTest');
                $.ajax({
                    type: "POST",
                    url: "{!! url('dingtalk/receive') !!}",
                    success: function(result) {
                        // alert(result);
                        // alert(result.errmsg);
                        if (result.errcode == 0)
                        {
                            alert("取消绑定成功.");
                        }
                        else
                            alert(JSON.stringify(result));

                    },
                    error: function(xhr, ajaxOptions, thrownError) {
                        alert(JSON.stringify(xhr));
                    }
                });
            });
        });

        <?php $config = DT::getconfig(); ?>
        dd.config({
            agentId: '{!! array_get($config, 'agentId') !!}', // 必填，微应用ID
            corpId: '{!! array_get($config, 'corpId') !!}',//必填，企业ID
            timeStamp: {!! array_get($config, 'timeStamp') !!}, // 必填，生成签名的时间戳
            nonceStr: '{!! array_get($config, 'nonceStr') !!}', // 必填，生成签名的随机串
            signature: '{!! array_get($config, 'signature') !!}', // 必填，签名
            jsApiList: ['biz.util.uploadImage', 'biz.cspace.saveFile'] // 必填，需要使用的jsapi列表
        });

        dd.ready(function() {
            $("#btnPickConversation").click(function() {
                dd.biz.util.uploadImage({
                    multiple: true,
                    max: 5,
                    onSuccess: function(result) {
                        var images = result;	// result.split(',');
                        var imageHtml = '';
                        for (var i in images) {
                            imageHtml += '<div class="col-xs-6 col-md-3">';
                            imageHtml += '<div class="thumbnail">';
                            imageHtml += '<img src=' + images[i] + ' />';
                            imageHtml += '<input name="image_' + String(i) + '" value=' + images[i] + ' type="hidden">';
                            imageHtml += '</div>';
                            imageHtml += '</div>';
                        }
                        $("#previewimage").empty().append(imageHtml);
                    },
                    onFail: function(err) {
                        alert('select image failed: ' + JSON.stringify(err));
                    }
                });
            });

        });

        dd.error(function(error) {
            alert('dd.error: ' + JSON.stringify(error));
        });

    </script>
@endsection