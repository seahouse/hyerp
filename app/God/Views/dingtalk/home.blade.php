@extends('GOD::layouts.god')

@section('god.content')
<div class="panel panel-default">
    <div class="panel-heading">
        <label>{{$G_trans($viewTitle)}}</label>
        <label class="pull-right">
            {{ $G_trans('dingtalk.welcome', ['ding_name' => session('user.name'), 'erp_name' => session('user.username')]) }}
            &nbsp;&nbsp;
            <a href="{{action('\App\God\Controllers\DingTalk\AuthController@logout')}}">{{ $G_trans('dingtalk.logout') }}</a>
        </label>
    </div>
    <div class="panel-body">
    </div>
    <table width="100%">
        <tr>
            <td>
                <a class="btn btn-default btn-block" href="{{action($G_controller.'@requestToMe')}}" role="button">
                    <i class="fa fa-tasks fa-4x"></i><br>
                    {{ $G_trans('dingtalk.approval.requestToMe') }}
                </a>
            </td>
            <td>
                <a class="btn btn-default btn-block" href="{{action($G_controller.'@handledByMe')}}" role="button">
                    <i class="fa fa-search fa-4x"></i><br>
                    {{ $G_trans('dingtalk.approval.handledByMe') }}
                </a>
            </td>
            <td>
                <a class="btn btn-default btn-block" href="{{action($G_controller.'@requestByMe')}}" role="button">
                    <i class="fa fa-info fa-4x"></i><br>
                    {{ $G_trans('dingtalk.approval.requestByMe') }}
                </a>
            </td>
        </tr>
        <tr>
            <td>
                <a class="btn btn-default btn-block" href="{{action('\App\God\Controllers\Approval\ReimbursementController@create')}}" role="button">
                    <i class="fa fa-rmb fa-4x"></i><br>
                    {{ $G_trans('dingtalk.approval.reimburse') }}
                </a>
            </td>
            <td>
            </td>
            <td>
            </td>
        </tr>
    </table>
    <div class="panel-footer text-center">
    </div>
</div>
@stop
