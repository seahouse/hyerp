@extends('app')

@section('title', '报销')

@section('main')
<div class="mapproval">
	<div style="background-color: #FF953F;">
		<div class="btn-group btn-group-justified" role="group" aria-label="...">
			<div class="btn-group menu" role="group">
				<a href="/approval/mindexmyapproval">
					<i class="icon iconfont btnMenuIcon color-orange2 icon-dengdaishenpi">
					@if (Auth::user()->myapproval()->count() > 0 or Auth::user()->myapproval_paymentrequest()->count() > 0)
						@if (Auth::user()->myapproval()->count() + Auth::user()->myapproval_paymentrequest()->count() > 10)
	                    <span class="pcount"><span class="count">>10</span></span>
	                    @else
	                    <span class="pcount"><span class="count">{{ Auth::user()->myapproval()->count() + Auth::user()->myapproval_paymentrequest()->count() }}</span></span>
	                    @endif
	                @endif
					</i>
					<div type="button" class="btnMenu">待我审批</div>
				</a>
			</div>
			<div class="btn-group menu" role="group">
{{--
				<a href="/approval/mindexmy">
					<i class="icon iconfont btnMenuIcon color-orange2 icon-daibanshixiang"></i>
					<div type="button" class="btnMenu">我发起的</div>
				</a>
--}}
				<a href="/approval/mindexmying">
					<i class="icon iconfont btnMenuIcon color-orange2 icon-daibanshixiang"></i>
					<div type="button" class="btnMenu">我发起的</div>
				</a>

			</div>
		</div>
	</div>

	<div class="menuWrapper">
		<div class="msg">“如何销假”，请看这里!</div>
		<div class="btn-group btn-group-justified" role="group" aria-label="...">
			<div class="btn-group" role="group">
				<a href="/approval/reimbursements/mcreate" class="btn btn-default btn-lg">
					<i class="icon iconfont btn-menu-2 color-orange2 icon-money"></i>
					<span class="labble">报销</span>
<!--
					<button type="button" class="btn btn-default">报销</button>
-->
				</a>
			</div>
			<div class="btn-group" role="group">
				<a href="/approval/paymentrequests/mcreate" class="btn btn-default btn-lg">
					<i class="icon iconfont btn-menu-2 color-lightblue icon-money1"></i>
					<span class="labble">供应商付款</span>
<!--
					<button type="button" class="btn btn-default">请款</button>
-->
				</a>
			</div>
			<div class="btn-group" role="group">
				<a href="{{ url('/approval/issuedrawing/mcreate') }}" class="btn btn-default btn-lg rightMenu">
					<i class="icon iconfont btn-menu-2 color-purple icon-time"></i>
					<span class="labble">下发图纸</span>
<!--
					<button type="button" class="btn btn-default">请假</button>
-->
				</a>
			</div>
		</div>
		<div class="btn-group btn-group-justified" role="group" aria-label="...">
			<div class="btn-group" role="group">
				<a href="{{ url('/approval/mcitempurchase/mcreate') }}" class="btn btn-default btn-lg">
					<i class="icon iconfont btn-menu-2 color-grey icon-dingding"></i>
					<span class="labble">生产采购</span>
				</a>
			</div>
			<div class="btn-group" role="group">
				<a href="{{ url('/approval/pppayment/mcreate') }}" class="btn btn-default btn-lg">
					<i class="icon iconfont btn-menu-2 color-grey icon-dingding"></i>
					<span class="labble">生产加工单结算付款</span>
				</a>
			</div>
			<div class="btn-group" role="group">
				<a href="{{ url('/approval/projectsitepurchases/mcreate') }}" class="btn btn-default btn-lg rightMenu" >
					<i class="icon iconfont btn-menu-2 color-grey icon-dingding"></i>
					<span class="labble">工程采购</span>
				</a>
			</div>
		</div>
		<div class="btn-group btn-group-justified" role="group" aria-label="...">
			<div class="btn-group" role="group">
				<a href="{{ url('/approval/vendordeductions/mcreate') }}" class="btn btn-default btn-lg">
					<i class="icon iconfont btn-menu-2 color-grey icon-dingding"></i>
					<span class="labble">扣款-供应商扣款</span>
				</a>
			</div>
			<div class="btn-group" role="group">
				<a href="{{ url('/approval/techpurchase/mcreate') }}" class="btn btn-default btn-lg">
					<i class="icon iconfont btn-menu-2 color-grey icon-dingding"></i>
					<span class="labble">技术采购</span>
				</a>
			</div>
			<div class="btn-group" role="group">
				<a href="{{ url('/approval/corporatepayment/mcreate') }}" class="btn btn-default btn-lg rightMenu">
					<i class="icon iconfont btn-menu-2 color-grey icon-dingding"></i>
					<span class="labble">付款-对公帐户付款</span>
				</a>
			</div>
		</div>
		<div class="btn-group btn-group-justified" role="group" aria-label="...">
			<div class="btn-group" role="group">
				<a href="#" class="btn btn-default btn-lg">
					<i class="icon iconfont btn-menu-2 color-grey icon-dingding"></i>
					<span class="labble">XXX</span>
				</a>
			</div>
			<div class="btn-group" role="group">
				<a href="#" class="btn btn-default btn-lg">
					<i class="icon iconfont btn-menu-2 color-grey icon-dingding"></i>
					<span class="labble">XXX</span>
				</a>
			</div>
			<div class="btn-group" role="group">
				<a href="#" class="btn btn-default btn-lg rightMenu">
					<i class="icon iconfont btn-menu-2 color-grey icon-dingding"></i>
					<span class="labble">XXX</span>
				</a>
			</div>
		</div>

	</div>
</div>
@endsection
