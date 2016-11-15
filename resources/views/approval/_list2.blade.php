@if ($paymentrequests->count())
    @foreach($paymentrequests as $item)
    <div class="reimbList list-group">
        <a href="{{ url($href_pre_paymentrequest . $item->id . $href_suffix) }}" class="list-group-item">
            {{-- 以下的代码判断说明：如果用户的头像url为空，则以名字显示，否则以这个头像url来显示图片 --}}
            @if (isset($item->applicant->dtuser->avatar))
                <div class='col-xs-3 col-sm-3 headIcon'><img class="name img" src="{{ $item->applicant->dtuser->avatar }}" /></div>
            @else
                <div class='col-xs-3 col-sm-3 name headIcon'>{{ $item->applicant->name }}</div>
            @endif
{{--
            @if (null != $item->applicant->dingtalkGetUser())
                @if ($item->applicant->dingtalkGetUser()->avatar == '')
                    <div class='col-xs-2 col-sm-2 name'>{{ $item->applicant->dingtalkGetUser()->name }}</div>
                @else
                    <div class='col-xs-2 col-sm-2'><img class="name img" src="{{ $item->applicant->dingtalkGetUser()->avatar }}" /></div>
                @endif
            @else
                <div class='col-xs-2 col-sm-2 name'>{{ $item->applicant->name }}</div>
            @endif
--}}
            <div class='col-xs-6 col-sm-6 content'>
                <div title="{{ $item->applicant->name }}的付款" class="title">
                    <div class='longText'>{{ $item->paymenttype }} | {{ $item->amount }} </div>
                    <div class='longText'>@if (isset($item->supplier_hxold->name)) {{ $item->supplier_hxold->name }} @endif</div>
                    <div class='longText'>@if (isset($item->purchaseorder_hxold->custinfo_name)) {{ $item->purchaseorder_hxold->custinfo_name }} @endif</div>
                    <div class='longText'>@if (isset($item->purchaseorder_hxold->sohead_descrip)) {{ $item->purchaseorder_hxold->sohead_descrip }} @endif</div>
                </div>

            </div>
            <div class='col-xs-3 col-sm-3 time'>
                <span >{{ $item->created_at }}</span><br/>
                {{-- 以下的代码判断说明：如果审批id大于0，则显示“待审批”，否则显示“审批完成”。 --}}
                {{-- 当状态为“审批完成”时，字体为灰色 --}}
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

{{--
@if ($reimbursements->count())
    @foreach($reimbursements as $item)
    <div class="reimbList list-group">
        <a href="{{ url($href_pre . $item->id . $href_suffix) }}" class="list-group-item">
            @if (isset($item->applicant->dtuser->avatar))
                <div class='col-xs-3 col-sm-2'><img class="name img" src="{{ $item->applicant->dtuser->avatar }}" /></div>
            @else
                <div class='col-xs-3 col-sm-2 name'>{{ $item->applicant->name }}</div>
            @endif
            <div class='col-xs-6 col-sm-7 content'>
                <div title="{{ $item->applicant->name }}的报销" class="title">{{ $item->applicant->name }}的报销</div>
                @if ($item->approversetting_id > 0)
                    <div class="statusTodo">待审批</div>
                @else
                    <div class="statusDone">审批完成</div>
                @endif
            </div>
            <div class='col-xs-3 col-sm-3 time'><span >{{ $item->created_at }}</span></div>
        </a>
    </div>     
    @endforeach
@endif
--}}
