@extends('app')

@section('title', '入库价格明细')

@section('main')
	@include('approval.paymentrequests._recvdetail4')
@endsection

@section('script')
	<script type="text/javascript">
	    var tempNav = "tab1";
	    var temp = "tabid1";
	    function changeTab(n){
	        var tabnav = "tab"+n;
	        var tabid = "tabid"+n;
	        if(temp != tabid){
	            clearDislay(temp);
	            temp =tabid;
	        }
	        if(tempNav != tabnav){
	            document.getElementById(tempNav).className="text";
	            tempNav = tabnav;
	        }
	        document.getElementById(tabnav).className="text selected";
	        document.getElementById(tabid).style.display="block";
	    };
	    function clearDislay(tab){
	        var clearTabid = tab;
	        document.getElementById(clearTabid).style.display = "none";
	    }
	</script>
@endsection
