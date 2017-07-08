@extends('app')

@section('title', '销售订单金额数据统计')

@section('main')
	@include('sales.salesorders._statistics')
@endsection


@section('script')
	<script type="text/javascript">
	    var tempNav = "tab1";
	    var temp = "tabid1";
	    var scrollPosMap = {tab1 : 0, tab2 : 0};
	    var nav1 = document.getElementById('tab1'), 
	    	nav2 = document.getElementById('tab2'),
	    	content1 = document.getElementById('tabid1'),
	    	content2 = document.getElementById('tabid2');
	    function changeTab(n){
			if (n == 1) {
				scrollPosMap['tab2'] = window.pageYOffset || document.documentElement.scrollTop;
				nav1.className="text selected";
				content1.style.display="block";
				nav2.className="text";
				content2.style.display="none";
				window.scrollTo(0, scrollPosMap['tab1']);
				console.log(scrollPosMap)
			}
			else {
				scrollPosMap['tab1'] = window.pageYOffset || document.documentElement.scrollTop;
				nav2.className="text selected";
				content2.style.display="block";
				nav1.className="text";
				content1.style.display="none";
				window.scrollTo(0, scrollPosMap['tab2']);
				console.log(scrollPosMap)
			}
			return false;
	    };
	</script>
@endsection

