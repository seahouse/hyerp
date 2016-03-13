@extends('GOD::layouts.god')

<?php
    $features = [
        'system' => [
            'user index page' => '\App\God\Controllers\System\UserController@index',
            'role index page' => '\App\God\Controllers\System\RoleController@index',
            'permission index page' => '\App\God\Controllers\System\PermissionController@index',
            'permission_role index page' => '\App\God\Controllers\System\PermissionRoleController@index',
            'role_user index page' => '\App\God\Controllers\System\RoleUserController@index',
        ],
        'approval' => [
            'reimbursement index page' => '\App\God\Controllers\Approval\ReimbursementController@index',
            'reimbursementtype index page' => '\App\God\Controllers\Approval\ReimbursementTypeController@index',
        ],
        'dingtalk' => [
            //'login page' => '\App\God\Controllers\DingTalk\AuthController@login',
            //'logout page' => '\App\God\Controllers\DingTalk\AuthController@logout',
            'approval home page' => '\App\God\Controllers\DingTalk\ApprovalController@home',
            'requesttome page' => '\App\God\Controllers\DingTalk\ApprovalController@requestToMe',
            'handledbyme page' => '\App\God\Controllers\DingTalk\ApprovalController@handledByMe',
            'requestbyme page' => '\App\God\Controllers\DingTalk\ApprovalController@requestByMe',
        ],
    ];
?>

@section('god.content')
<div class="panel panel-default">
    <div class="panel-heading text-center">
        Contents
    </div>
    <div class="panel-body">
        @foreach ($features as $key => $values)
        <ul>
            <li>{{$key}}
                <ul>
                    @foreach($values as $name => $action)
                    <li><a href="{{action($action)}}">{{$name}}</a></li>
                    @endforeach
                </ul>
            </li>
        </ul>
        @endforeach
    </div>
    <div class="panel-footer">
    </div>
</div>
@stop
