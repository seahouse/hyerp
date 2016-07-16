@extends('app')

@section('title', '待我审批')

@section('main')
<div class="apprNav">
	<div class="btn-group btn-group-justified wrapper" role="group" aria-label="...">
	    <div class="btn-group btnw" role="group">
	        <a href="/approval/reimbursements/mindexmyapproval">
	        	<div class="{{strpos($_SERVER['REQUEST_URI'], 'mindexmyapprovaled') == '' ? 'text selected' : 'text' }}">待我审批的</div>
	        </a>
	    </div>
	    <div class="btn-group btnw" role="group">
	        <a href="/approval/reimbursements/mindexmyapprovaled">
	        	<div class="{{strpos($_SERVER['REQUEST_URI'], 'mindexmyapprovaled') > 0 ? 'text selected' : 'text' }}">我已审批的</div>
	        </a>
	    </div>
	</div>
</div>
<script type="text/javascript">
</script>
<p>
@yield('mindexmyapproval_main')
</p>
@endsection
