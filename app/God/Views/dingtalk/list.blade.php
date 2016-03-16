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
    <table class="table table-hover">
        @forelse ($models as $model)
        <tr onClick="location.href='{{action('God\Approval\ReimbursementController@show', $model['id'])}}'">
            <td>
                <div class="pull-left" style="color:gray">
                    <i class="fa fa-rmb fa-3x fa-pull-left"></i>
                    <strong>{{$model['applicant']}}的{{$model['approvaltype']}}</strong>
                    <br>
                    <small>{{$model['status']}}</small>
                </div>
                <div class="pull-right">
                    <small>{{$model['date']}}</small>
                </div>
            </td>
        </tr>
        @empty
        <tr>
            <td class="text-center">
                {{ $G_trans('god.noRecord') }}
            </td>
        </tr>
        @endforelse
    </table>
    <div class="panel-footer text-center">
    </div>
</div>
@stop
