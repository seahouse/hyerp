@extends('app')

@section('title')
身份验证
@endsection

@section('main')
	<p>
		<h4>身份验证中，请稍后....</h4>		
	</p>
<!-- 	<p>
		<div class="btn-group btn-group-justified" role="group" aria-label="...">
			<div class="btn-group" role="group">
				<a href="#"><button type="button" class="btn btn-default">待我审批</button></a>
			</div>
			<div class="btn-group" role="group">
				<a href="#"><button type="button" class="btn btn-default">我发起的</button></a>
			</div>
		</div>
	</p>

	<p>
		<div class="btn-group btn-group-justified" role="group" aria-label="...">
			<div class="btn-group" role="group">
				<a href="/approval/reimbursements/mcreate"><button type="button" class="btn btn-default">报销</button></a>
			</div>
			<div class="btn-group" role="group">
				<a href="#"><button type="button" class="btn btn-default">请款</button></a>
			</div>
			<div class="btn-group" role="group">
				<a href="#"><button type="button" class="btn btn-default">请假</button></a>
			</div>
		</div>
	</p> -->

    <a href="dingtalk://dingtalkclient/action/switchtab?index=2&name=work&scene=1&corpid={{config('custom.dingtalk.corpid')}}&appid=1288" class="btn btn-success btn-sm">视频</a>



{{--
	@foreach ($config as $key => $value)
		{!! Form::hidden($key, $value, ['id' => $key]) !!}
    @endforeach
--}}

    {{--     $agent->is('Windows'): {{ $agent->is('Windows') }}		<br>
        $agent->is('Firefox'): {{ $agent->is('Firefox') }}		<br>
        $agent->is('iPhone'): {{ $agent->is('iPhone') }}		<br>
        $agent->is('OS X'): {{ $agent->is('OS X') }}			<br>
        $agent->isAndroidOS(): {{ $agent->isAndroidOS() }}		<br>
        $agent->isNexus(): {{ $agent->isNexus() }}				<br>
        $agent->isSafari(): {{ $agent->isSafari() }}			<br>
        $agent->isMobile(): {{ $agent->isMobile() }}			<br>
        $agent->isTablet(): {{ $agent->isTablet() }}			<br>
        $agent->device(): {{ $agent->device() }}				<br>
        $agent->platform(): {{ $agent->platform() }}			<br>
        $agent->browser(): {{ $agent->browser() }}				<br>
        $agent->isDesktop(): {{ $agent->isDesktop() }}			<br>
        $agent->isPhone(): {{ $agent->isPhone() }}				<br>
        $agent->isRobot(): {{ $agent->isRobot() }}				<br>
        $agent->robot(): {{ $agent->robot() }}					<br>
        $agent->isPhone(): {{ $agent->isPhone() }}				<br>
        --}}

        <!-- can not display array value -->
    {{--
             $agent->languages():			<br>
             --}}


    <!--
        <a href="http://www.baidu.com" target="_blank" class="btn btn-default btn-sm" id="t1">百度</a>
    -->
    @endsection

