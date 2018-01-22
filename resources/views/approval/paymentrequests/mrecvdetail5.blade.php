@extends('app')

@section('title', '入库价格明细')

@section('main')
	@include('approval.paymentrequests._recvdetail5')
@endsection


@section('script')
	{{--<script type="text/javascript" src="/DataTables/datatables.js"></script>--}}
	<script type="text/javascript" src="/DataTables/DataTables-1.10.16/js/jquery.dataTables.min.js"></script>
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

        jQuery(document).ready(function(e) {
            @foreach ($itemps as $itemp)
				$('#table_item_{{$itemp->goods_id}}').DataTable({
					"processing": true,
					"serverSide": true,
					"ajax": "{{ url('approval/paymentrequests/mrecvdetail5data/' . $itemp->goods_id . '/' . $receiptid) }}",
					"columns": [
						{"data": "0", "name": "quantity"},
                        {"data": "1", name: "unitprice", "searchable": false},
                        {"data": "2", name: "vgoods.goods_unit_name"},
                        {"data": "3", name: "price", "searchable": false},
                        {"data": "4", name: "material"},
                        {"data": "5", name: "size"},
                        {"data": "6", name: "vwarehouse.name"},
                        {"data": "7", name: "vsupplier.name"},
                        {"data": "8", name: "vpurchaseorder.orderdate"},
                        {"data": "9", name: "vorder.projectjc"},
                        {"data": "10", name: "out_sohead_name"},
                        {"data": "11", name: "vreceiptitem.record_at"},
					],
					'order': [["11", "desc"]]
				});
            @endforeach

        });
	</script>
@endsection

