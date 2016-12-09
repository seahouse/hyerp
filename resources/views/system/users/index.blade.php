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
        <a href="{{ URL::to('dingtalk/delete_call_back') }}">与钉钉取消绑定</a>

        {!! Form::open(['url' => '/system/users/bingdingtalk', 'class' => 'pull-right']) !!}
            {!! Form::submit('与钉钉强绑定', ['class' => 'btn btn-default btn-sm']) !!}            
        {!! Form::close() !!}
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
        });
    </script>
@endsection