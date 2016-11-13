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
                <div class='col-xs-2 col-sm-2'><img class="name img" src="{{ $dtuser->avatar }}" /></div>
            @else
                <div class='col-xs-2 col-sm-2 name'>{{ $dtuser->name }}</div>
            @endif
{{--
            @if ($dduser->avatar == '')
                <div class='col-xs-2 col-sm-2 name'>{{ $dduser->name }}</div>
            @else
                <div class='col-xs-2 col-sm-2'><img class="name img" src="{{ $dduser->avatar }}" /></div>
            @endif
--}}
            <div class='col-xs-7 col-sm-7 content'>
                <div title="{{ $item->applicant_name }}的付款" class="title">
                    <div class='longText'>{{ $item->applicant->name }}的付款 | {{ $item->amount }} | 江苏华能建设工程集团有限公司</div>

{{--

                     | {{ $item->amount }} | 
                    @if (isset($item->supplier_hxold->name)) {{ str_limit($item->supplier_hxold->name, 6) }} @endif |
                    @if (isset($item->purchaseorder_hxold->sohead_descrip)) {{ $item->purchaseorder_hxold->custinfo_name }} | {{ $item->purchaseorder_hxold->sohead_descrip }} @endif
--}}
                </div>
                <div class='longText'>青岛东方铁塔股份有限公司 | 广元市生活垃圾焚烧厂烟气净化设备</div>
                {{-- 以下的代码判断说明：如果审批id大于0，则显示“待审批”，否则显示“审批完成”。 --}}
                {{-- 当状态为“审批完成”时，字体为灰色 --}}
                @if ($item->approversetting_id > 0)
                    <div class="statusTodo">待审批</div>
                @else
                    <div class="statusDone">审批完成</div>      {{-- 此时，字体要修改为灰色 --}}
                @endif
            </div>
            <div class='col-xs-3 col-sm-3 time'><span >{{ $item->created_at }}</span></div>
        </a>
    </div>
    @endforeach
@endif
