@extends('app')

@section('title', '待我审批')

@section('main')
<p>
	<div class="btn-group btn-group-justified" role="group" aria-label="...">
	    <div class="btn-group" role="group">
	        <a href="/approval/reimbursements/mindexmyapproval"><button type="button" class="btn btn-default">待我审批的</button></a>
	    </div>
	    <div class="btn-group" role="group">
	        <a href="/approval/reimbursements/mindexmyapprovaled"><button type="button" class="btn btn-default">我已审批的</button></a>
	    </div>
	</div>
</p>
<p>
@yield('mindexmyapproval_main')
</p>
@endsection
