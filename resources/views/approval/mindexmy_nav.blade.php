@extends('app')

@section('title', '我发起的')

@section('main')
{{--
<div class="apprNav">
	<div class="btn-group btn-group-justified wrapper" role="group" aria-label="...">
	    <div class="btn-group btnw" role="group">
	        <a href="/approval/mindexmying">
	        	<div class="{{strpos($_SERVER['REQUEST_URI'], 'mindexmyed') == '' ? 'text selected' : 'text' }}">正在审批的</div>
	        </a>
	    </div>
	    <div class="btn-group btnw" role="group">
	        <a href="/approval/mindexmyed">
	        	<div class="{{strpos($_SERVER['REQUEST_URI'], 'mindexmyed') > 0 ? 'text selected' : 'text' }}">审批完成的</div>
	        </a>
	    </div>
	</div>
</div>
--}}

<p>
@yield('mindexmy_main')
</p>
@endsection
