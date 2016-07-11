@extends('app')

@section('title', '报销')

@section('main')
<div class="mapproval">
	<div style="background-color: #FF953F;">
		<div class="btn-group btn-group-justified" role="group" aria-label="...">
			<div class="btn-group" role="group">
<!--
				<a href="/approval/reimbursements/mindexmyapproval"><button type="button" class="btn btn-default btn-lg">待我审批</button></a>
-->
				<a href="/approval/reimbursements/mindexmyapproval"><button type="button" class="btn btn-default btn-lg btn-menu-1">待我审批({{ Auth::user()->myapproval()->count() }})</button></a>
			</div>
			<div class="btn-group" role="group">
<!--
				<a href="/approval/reimbursements/mindexmy"><button type="button" class="btn btn-default btn-lg">我发起的</button></a>
-->	
				<a href="/approval/reimbursements/mindexmy"><button type="button" class="btn btn-default btn-lg btn-menu-1">我发起的</button></a>
			</div>
		</div>
	</div>

	<div class="menuWrapper">
		<div class="msg">“如何销假”，请看这里!</div>
		<div class="btn-group btn-group-justified" role="group" aria-label="...">
			<div class="btn-group" role="group">
				<a href="/approval/reimbursements/mcreate" class="btn btn-default btn-lg">
					<i class="icon iconfont btn-menu-2 color-orange2">&#xe691;</i>
					<span class="labble">报销</span>
<!--
					<button type="button" class="btn btn-default">报销</button>
-->
				</a>
			</div>
			<div class="btn-group" role="group">
				<a href="#" class="btn btn-default btn-lg">
					<i class="icon iconfont btn-menu-2 color-lightblue">&#xe60c;</i>
					<span class="labble">请款</span>
<!--
					<button type="button" class="btn btn-default">请款</button>
-->
				</a>
			</div>
			<div class="btn-group" role="group">
				<a href="#" class="btn btn-default btn-lg rightMenu">
					<i class="icon iconfont btn-menu-2 color-purple">&#xe7e4;</i>
					<span class="labble">请假</span>
<!--
					<button type="button" class="btn btn-default">请假</button>
-->
				</a>
			</div>
		</div>
		<div class="btn-group btn-group-justified" role="group" aria-label="...">
			<div class="btn-group" role="group">
				<a href="#" class="btn btn-default btn-lg">
					<i class="icon iconfont btn-menu-2 color-grey">&#xe65e;</i>
					<span class="labble">XXX</span>
				</a>
			</div>
			<div class="btn-group" role="group">
				<a href="#" class="btn btn-default btn-lg">
					<i class="icon iconfont btn-menu-2 color-grey">&#xe65e;</i>
					<span class="labble">XXX</span>
				</a>
			</div>
			<div class="btn-group" role="group">
				<a href="#" class="btn btn-default btn-lg rightMenu" >
					<i class="icon iconfont btn-menu-2 color-grey">&#xe65e;</i>
					<span class="labble">XXX</span>
				</a>
			</div>
		</div>
		<div class="btn-group btn-group-justified" role="group" aria-label="...">
			<div class="btn-group" role="group">
				<a href="#" class="btn btn-default btn-lg">
					<i class="icon iconfont btn-menu-2 color-grey">&#xe65e;</i>
					<span class="labble">XXX</span>
				</a>
			</div>
			<div class="btn-group" role="group">
				<a href="#" class="btn btn-default btn-lg">
					<i class="icon iconfont btn-menu-2 color-grey">&#xe65e;</i>
					<span class="labble">XXX</span>
				</a>
			</div>
			<div class="btn-group" role="group">
				<a href="#" class="btn btn-default btn-lg rightMenu">
					<i class="icon iconfont btn-menu-2 color-grey">&#xe65e;</i>
					<span class="labble">XXX</span>
				</a>
			</div>
		</div>
		<div class="btn-group btn-group-justified" role="group" aria-label="...">
			<div class="btn-group" role="group">
				<a href="#" class="btn btn-default btn-lg">
					<i class="icon iconfont btn-menu-2 color-grey">&#xe65e;</i>
					<span class="labble">XXX</span>
				</a>
			</div>
			<div class="btn-group" role="group">
				<a href="#" class="btn btn-default btn-lg">
					<i class="icon iconfont btn-menu-2 color-grey">&#xe65e;</i>
					<span class="labble">XXX</span>
				</a>
			</div>
			<div class="btn-group" role="group">
				<a href="#" class="btn btn-default btn-lg rightMenu">
					<i class="icon iconfont btn-menu-2 color-grey">&#xe65e;</i>
					<span class="labble">XXX</span>
				</a>
			</div>
		</div>

	</div>
</div>
@endsection
