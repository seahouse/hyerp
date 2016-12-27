@extends('app')

@section('title', '入库价格明细')

@section('main')
	@include('approval.paymentrequests._test2')
@endsection

@section('script')
	<script type="text/javascript">
		var offset = 80;

		$('.navbar li a').click(function(event) {
		    event.preventDefault();
		    $($(this).attr('href'))[0].scrollIntoView();
		    var navOffset = $('#navbar').height();
		    scrollBy(0, -offset);
		});
	</script>
@endsection

