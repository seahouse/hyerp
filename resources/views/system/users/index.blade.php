@extends('navbarerp')

@section('main')
@can('system_user_view')
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
    {!! Form::open(['url' => '/system/users/search', 'class' => 'pull-right form-inline']) !!}
    <div class="form-group-sm">
        {{--
            {!! Form::select('paymentmethod', ['支票' => '支票', '贷记' => '贷记', '电汇' => '电汇', '汇票' => '汇票', '现金' => '现金', '银行卡' => '银行卡', '其他' => '其他'], null, ['class' => 'form-control', 'placeholder' => '--付款方式--']) !!}

            {!! Form::select('paymentstatus', ['0' => '已付款', '-1' => '未付款'], null, ['class' => 'form-control', 'placeholder' => '--付款状态--']); !!}
            {!! Form::select('approvalstatus', ['1' => '审批中', '0' => '已通过', '-2' => '未通过'], null, ['class' => 'form-control', 'placeholder' => '--审批状态--']); !!}
            --}}
        {!! Form::text('key', null, ['class' => 'form-control', 'placeholder' => '姓名']) !!}
        <div class="checkbox">
            <label>
                <input type="checkbox" name="issupplier"> 供应商用户
            </label>
        </div>
        {!! Form::submit('查找', ['class' => 'btn btn-default btn-sm']) !!}
    </div>
    {!! Form::close() !!}

    @if (Auth::user()->email == "admin@admin.com")

    {!! Form::open(['class' => 'pull-right form-inline']) !!}
    {!! Form::button('河南华星人员自动同步钉钉', ['class' => 'btn btn-default btn-sm', 'id' => 'btnBindHnhxDtuser']) !!}
    {!! Form::button('取消河南华星人员自动同步钉钉', ['class' => 'btn btn-default btn-sm', 'id' => 'btnCancelBindHnhxDtuser']) !!}
    {!! Form::close() !!}

    {!! Form::button('与钉钉取消绑定', ['class' => 'btn btn-default btn-sm pull-right', 'id' => 'btnCancelBindDT']) !!}
    {{--<a href="{{ URL::to('dingtalk/delete_call_back') }}">与钉钉取消绑定</a>--}}

    {!! Form::open(['url' => '/system/users/bingdingtalk', 'class' => 'pull-right']) !!}
    {!! Form::submit('与钉钉强绑定', ['class' => 'btn btn-default btn-sm']) !!}
    {!! Form::close() !!}

    {{--{!! Form::open(['url' => '/dingtalk/receive', 'class' => 'pull-right']) !!}--}}
    {{--{!! Form::submit('与钉钉强绑定222', ['class' => 'btn btn-default btn-sm']) !!}            --}}
    {{--{!! Form::close() !!}--}}

    {{--{!! Form::open(['url' => '/faceplusplus/detect', 'class' => 'pull-right']) !!}--}}
    {{--{!! Form::submit('人脸监测', ['class' => 'btn btn-default btn-sm']) !!}            --}}
    {{--{!! Form::close() !!}--}}

    {{--{!! Form::open(['url' => '/faceplusplus/faceset_create', 'class' => 'pull-right', 'files' => true]) !!}--}}
    {{--{!! Form::submit('人脸集合', ['class' => 'btn btn-default btn-sm']) !!}            --}}
    {{--{!! Form::close() !!}--}}

    {{--{!! Form::open(['url' => '/faceplusplus/compare', 'class' => 'pull-right']) !!}--}}
    {{--{!! Form::hidden('api_key', 'eLObusplEGW0dCfBDYceyhoAdvcEaQtk', []) !!}--}}
    {{--{!! Form::hidden('api_secret', 'bWJAjmtylVZ6A8Ik4_vC1xBO3X3cyKJT', []) !!}--}}
    {{--{!! Form::hidden('image_url1', 'http://static.dingtalk.com/media/lADOlob6ns0CgM0CgA_640_640.jpg', []) !!}--}}
    {{--{!! Form::hidden('image_url2', 'http://static.dingtalk.com/media/lADOlob7MM0CgM0CgA_640_640.jpg', []) !!}--}}
    {{--{!! Form::submit('人脸对比测试', ['class' => 'btn btn-default btn-sm']) !!}            --}}
    {{--{!! Form::close() !!}--}}



    {!! Form::open(['url' => url('/dingtalk/chat_create'), 'class' => 'pull-right']) !!}
    {!! Form::submit('聊天', ['class' => 'btn btn-default btn-sm']) !!}
    {!! Form::close() !!}

    {!! Form::open(['url' => url('/system/users/updateuseroldall'), 'class' => 'pull-right']) !!}
    {!! Form::submit('设置与老系统的对应关系', ['class' => 'btn btn-default btn-sm']) !!}
    {!! Form::close() !!}

    {!! Form::open(['url' => url('/system/roles/sync'), 'class' => 'pull-right']) !!}
    {!! Form::submit('同步钉钉角色', ['class' => 'btn btn-default btn-sm']) !!}
    {!! Form::close() !!}

    {!! Form::open(['url' => url('/dingtalk/synchronizeusers'), 'class' => 'pull-right']) !!}
    {!! Form::submit('同步钉钉人员到本地用户', ['class' => 'btn btn-default btn-sm']) !!}
    {!! Form::close() !!}


    {{--
        {!! Form::open(['url' => '/facecore/urlfacedetect', 'class' => 'pull-right']) !!}
            {!! Form::submit('人头数监测', ['class' => 'btn btn-default btn-sm']) !!}
        {!! Form::close() !!}

        {!! Form::open(['url' => '/cloudwalk/face_tool_detect', 'class' => 'pull-right']) !!}
            {!! Form::submit('云从科技', ['class' => 'btn btn-default btn-sm']) !!}
        {!! Form::close() !!}

        <form method="POST" action="http://localhost:82/dingtalk/receive" class="pull-right">
            <input class="btn btn-default btn-sm" type="submit" value="4444">            
        </form>
        --}}

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
            <th>Google Authentication</th>
            <th>供应商</th>
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
                <a href="{{ url('/system/users/' . $user->id . '/edituserold') }}" class="btn btn-default btn-sm" target="_blank">设置</a>
                @if (isset($user->userold->user_hxold))
                {{ $user->userold->user_hxold->name }}
                @endif
            </td>
            <td>
                <a href="{{ URL::to('/system/users/'.$user->id.'/roles') }}">明细</a>
                {{-- @if (isset($user->role->display_name)) {{ $user->role->display_name }} @endif --}}
            </td>
            <td>
                <a href="{{ url('/system/users/' . $user->id . '/google2fa') }}" class="btn btn-default btn-sm">设置</a>
            </td>
            <th>{{ isset($user->supplier)? $user->supplier->name : '' }}</th>
            <td>
                <a href="{{ URL::to('/system/users/'.$user->id.'/edit') }}" class="btn btn-success btn-sm pull-left">编辑</a>
                <a href="{{ URL::to('/system/users/'.$user->id.'/editpass') }}" class="btn btn-success btn-sm pull-left">修改密码</a>
                {{-- <a href="{{ URL::to('/system/users/'.$user->id.'/editrole') }}" class="btn btn-success btn-sm pull-left">编辑角色</a> --}}
                {!! Form::open(array('route' => array('system.users.destroy', $user->id), 'method' => 'delete', 'onsubmit' => 'return confirm("确定删除此记录?");')) !!}
                {!! Form::submit('删除', ['class' => 'btn btn-danger btn-sm']) !!}
                {!! Form::close() !!}
            </td>
        </tr>
        @endforeach
    </tbody>

</table>

@if (isset($key))
{!! $users->setPath('/system/users')->appends([
'key' => $inputs['key']
])->links() !!}
@else
{!! $users->setPath('/system/users')->links() !!}
@endif

@else
<div class="alert alert-warning alert-block">
    <i class="fa fa-warning"></i>
    {{'无记录', [], 'layouts'}}
</div>
@endif
@else
无权限。
@endcan
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
                    if (result.errcode == 0) {
                        alert("取消绑定成功.");
                    } else
                        alert(JSON.stringify(result));

                },
                error: function(xhr, ajaxOptions, thrownError) {
                    alert(JSON.stringify(xhr));
                }
            });
        });

        $("#btnBindHnhxDtuser").click(function() {
            $.post("{{ url('system/users/binddingtalk2') }}", {
                _token: "{{ csrf_token() }}"
            }, function(data) {
                alert(data);
            });
        });
    });

    <?php $config = DT::getconfig(); ?>
    dd.config({
        agentId: "{!! array_get($config, 'agentId') !!}", // 必填，微应用ID
        corpId: "{!! array_get($config, 'corpId') !!}", //必填，企业ID
        timeStamp: "{!! array_get($config, 'timeStamp') !!}", // 必填，生成签名的时间戳
        nonceStr: "{!! array_get($config, 'nonceStr') !!}", // 必填，生成签名的随机串
        signature: "{!! array_get($config, 'signature') !!}", // 必填，签名
        jsApiList: ['biz.util.uploadImage', 'biz.cspace.saveFile'] // 必填，需要使用的jsapi列表
    });

    dd.ready(function() {});

    dd.error(function(error) {
        alert('dd.error: ' + JSON.stringify(error));
    });
</script>
@endsection