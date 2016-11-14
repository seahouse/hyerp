<form class="navbar-form reimbListSearch" role="search">
  <div class="form-group col-sm-8 col-xs-8">
    <input type="text" name="keyword" class="form-control" style="width:100%" placeholder="输入关键字">
  </div>
  <div class='col-sm-4 col-xs-4'><button type="submit" class="btn btn-default">搜索</button></div>
</form>
@if ($paymentrequests->count())
    @foreach($paymentrequests as $item)
    <div class="reimbList list-group">       
        <a href="{{ url($href_pre_paymentrequest . $item->id . $href_suffix) }}" class="list-group-item">
            {{-- 以下的代码判断说明：如果用户的头像url为空，则以名字显示，否则以这个头像url来显示图片 --}}  
            @if (isset($dtuser->avatar))
                <div class='col-xs-3 col-sm-3 headIcon'><img class="name img" src="{{ $dtuser->avatar }}" /></div>
            @else
                <div class='col-xs-3 col-sm-3 name headIcon'>{{ $dtuser->name }}</div>
            @endif
{{--
            @if ($dduser->avatar == '')
                <div class='col-xs-3 col-sm-3 name headIcon'>{{ $dduser->name }}</div>
            @else
                <div class='col-xs-3 col-sm-3 headIcon' ><img class="name img" src="{{ $dduser->avatar }}" /></div>
            @endif
--}}
            <div class='col-xs-6 col-sm-6 content'>
                <div title="{{ $item->applicant_name }}的付款" class="title">
                    <div class='longText'>{{ $item->paymenttype }} | {{ $item->amount }}</div>
                    {{-- 示例：山东奥博环保科技有限公司 --}}
                    {{-- @if (isset($item->supplier_hxold->name)) {{ $item->supplier_hxold->name }} @endif --}}
                    <div class='longText'>@if (isset($item->supplier_hxold->name)) {{ $item->supplier_hxold->name }} @endif</div>
                    {{-- 示例：浙江锦润机电成套设备有限公司 --}}
	                {{-- @if (isset($item->purchaseorder_hxold->custinfo_name)) {{ $item->purchaseorder_hxold->custinfo_name }} @endif --}}
	                <div class='longText'>@if (isset($item->purchaseorder_hxold->custinfo_name)) {{ $item->purchaseorder_hxold->custinfo_name }} @endif</div>
	                {{-- 示例：高密垃圾焚烧发电项目1#2#炉烟气脱硫净化装置系统工程 --}}
	                {{-- @if (isset($item->purchaseorder_hxold->sohead_descrip)) {{ $item->purchaseorder_hxold->sohead_descrip }} @endif--}}
	                <div class='longText'>@if (isset($item->purchaseorder_hxold->sohead_descrip)) {{ $item->purchaseorder_hxold->sohead_descrip }} @endif</div>
                </div>
                
                
            </div>
            <div class='col-xs-3 col-sm-3 time'>
            	<span >{{ $item->created_at }}</span><br/>
            	@if ($item->approversetting_id > 0)
                    <div class="statusTodo">待审批</div>
                @else
                    <div class="statusDone">审批完成</div>      {{-- 此时，字体要修改为灰色 --}}
                @endif
            </div>
        </a>
    </div>
    @endforeach
@endif
