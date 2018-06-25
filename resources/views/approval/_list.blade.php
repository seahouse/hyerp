
@if ($inputs['approvaltype'] == '供应商付款' && isset($items) && $items->count())
    @foreach($items as $item)
    <div class="reimbList list-group">
        <a href="{{ url($href_pre_paymentrequest . $item->id . $href_suffix) }}@if (isset($inputs))?{{ http_build_query($inputs) }}@endif" class="list-group-item">
            {{-- 以下的代码判断说明：如果用户的头像url为空，则以名字显示，否则以这个头像url来显示图片 --}}  
            @if (isset($item->applicant->dtuser->avatar))
                <div class='col-xs-3 col-sm-3 headIcon'><img class="name img" src="{{ $item->applicant->dtuser->avatar }}" /></div>
            @else
                <div class='col-xs-3 col-sm-3 name headIcon'>{{ $item->applicant->name }}</div>
            @endif
{{--
            @if ($dduser->avatar == '')
                <div class='col-xs-3 col-sm-3 name headIcon'>{{ $dduser->name }}</div>
            @else
                <div class='col-xs-3 col-sm-3 headIcon' ><img class="name img" src="{{ $dduser->avatar }}" /></div>
            @endif
--}}
            <div class='col-xs-6 col-sm-6 content'
                 {{-- 如果是垫资，则用特殊颜色显示。垫资：销售订单的收款金额小于此销售订单对应的采购订单的付款总额。--}}
                @if (isset($item->purchaseorder_hxold->sohead) and $item->purchaseorder_hxold->sohead->receiptpayments->sum('amount') * 10000 < $item->purchaseorder_hxold->sohead->payments->sum('amount'))  style="color: #FF3300;") @endif >
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
	                <div class='longText'>
                        @if (isset($item->purchaseorder_hxold->sohead->projectjc))
                            {{ $item->purchaseorder_hxold->sohead->projectjc }}
                        @elseif (isset($item->purchaseorder_hxold->sohead_descrip))
                            {{ $item->purchaseorder_hxold->sohead_descrip }}
                        @endif
                    </div>
                </div>
                
                
            </div>

            <div class='col-xs-3 col-sm-3 time'>
                @if (isset($item->purchaseorder_hxold->productname))
                    <div class='product'>{{ $item->purchaseorder_hxold->productname }}</div>
                @endif

            	<span >{{ $item->created_at }}</span><br/>
            	@if ($item->approversetting_id > 0)
                    <div class="statusTodo">待审批
                        @if (isset($item->purchaseorder_hxold->arrival))
                            {{-- mb_substr: 中文截取 --}}
                            {{ mb_substr($item->purchaseorder_hxold->arrival, 0, 2) }}
                        @endif
                    </div>
                @elseif ($item->approversetting_id == 0)
                    <div class="statusDone">已通过
                        @if (isset($item->purchaseorder_hxold->arrival))
                            {{-- mb_substr: 中文截取 --}}
                            {{ mb_substr($item->purchaseorder_hxold->arrival, 0, 2) }}
                        @endif
                    </div>      {{-- 此时，字体要修改为灰色 --}}
                @elseif ($item->approversetting_id == -3)
                    <div class="statusTodo">撤回中
                        @if (isset($item->purchaseorder_hxold->arrival))
                            {{-- mb_substr: 中文截取 --}}
                            {{ mb_substr($item->purchaseorder_hxold->arrival, 0, 2) }}
                        @endif
                    </div>
                @elseif ($item->approversetting_id == -4)
                    <div class="statusDoneNotPass">已撤回
                        @if (isset($item->purchaseorder_hxold->arrival))
                            {{-- mb_substr: 中文截取 --}}
                            {{ mb_substr($item->purchaseorder_hxold->arrival, 0, 2) }}
                        @endif
                    </div>
                @else
                    <div class="statusDoneNotPass">未通过
                        @if (isset($item->purchaseorder_hxold->arrival))
                            {{-- mb_substr: 中文截取 --}}
                            {{ mb_substr($item->purchaseorder_hxold->arrival, 0, 2) }}
                        @endif
                    </div>
                @endif

                @if (isset($item->purchaseorder_hxold->amount_ticketed) and isset($item->purchaseorder_hxold->amount) and $item->purchaseorder_hxold->amount > 0.0 and $item->purchaseorder_hxold->amount_ticketed / $item->purchaseorder_hxold->amount > 0.9999)
                    票
                @endif
                @if (isset($item->purchaseorder_hxold->arrival_percent) and $item->purchaseorder_hxold->arrival_percent >= 0.9999)
                    货
                @endif

                    {{-- 已付款比例 --}}
                    @if (isset($item->purchaseorder_hxold->amount_paid))
                        {{ number_format($item->purchaseorder_hxold->amount_paid / $item->purchaseorder_hxold->amount * 100.0, 2, '.', '') . '%' }}
                    @endif
            </div>
        </a>
    </div>
    @endforeach
@elseif ($inputs['approvaltype'] == '供应商付款撤回' && isset($items) && $items->count())
    @foreach($items as $item)
        <div class="reimbList list-group">
            <a href="{{ url($href_pre_paymentrequestretract . $item->id . $href_suffix) }}" class="list-group-item">
                {{-- 以下的代码判断说明：如果用户的头像url为空，则以名字显示，否则以这个头像url来显示图片 --}}
                @if (isset($item->applicant->dtuser->avatar))
                    <div class='col-xs-3 col-sm-3 headIcon'><img class="name img" src="{{ $item->applicant->dtuser->avatar }}" /></div>
                @else
                    <div class='col-xs-3 col-sm-3 name headIcon'>{{ $item->applicant->name }}</div>
                @endif
                <?php $item = $item->paymentrequest; ?>
                <div class='col-xs-6 col-sm-6 content'
                     {{-- 如果是垫资，则用特殊颜色显示。垫资：销售订单的收款金额小于（此销售订单对应的采购订单的付款总额 + 公摊金额 + 税差）--}}
                     <?php $sohead = $item->purchaseorder_hxold->sohead; ?>
                     <?php $poheadAmountBy7550 = isset($sohead) ? array_first($sohead->getPoheadAmountBy7550())->poheadAmountBy7550 : 0.0; ?>
                     <?php $sohead_taxamount = isset($sohead->temTaxamountstatistics->sohead_taxamount) ? $sohead->temTaxamountstatistics->sohead_taxamount : 0; ?>
                     <?php $sohead_poheadtaxamount = isset($sohead->temTaxamountstatistics->sohead_poheadtaxamount) ? $sohead->temTaxamountstatistics->sohead_poheadtaxamount : 0.0; ?>
                     @if (isset($item->purchaseorder_hxold->sohead) and $item->purchaseorder_hxold->sohead->receiptpayments->sum('amount') * 10000 < ($item->purchaseorder_hxold->sohead->payments->sum('amount') + $poheadAmountBy7550))  style="color: #FF3300;") @endif >
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
                        <div class='longText'>
                            @if (isset($item->purchaseorder_hxold->sohead->projectjc))
                                {{ $item->purchaseorder_hxold->sohead->projectjc }}
                            @elseif (isset($item->purchaseorder_hxold->sohead_descrip))
                                {{ $item->purchaseorder_hxold->sohead_descrip }}
                            @endif
                        </div>
                    </div>


                </div>

                <div class='col-xs-3 col-sm-3 time'>
                    @if (isset($item->purchaseorder_hxold->productname))
                        <div class='product'>{{ $item->purchaseorder_hxold->productname }}</div>
                    @endif

                    <span >{{ $item->created_at }}</span><br/>
                    @if ($item->approversetting_id > 0)
                        <div class="statusTodo">待审批
                            @if (isset($item->purchaseorder_hxold->arrival))
                                {{-- mb_substr: 中文截取 --}}
                                {{ mb_substr($item->purchaseorder_hxold->arrival, 0, 2) }}
                            @endif
                        </div>
                    @elseif ($item->approversetting_id == 0)
                        <div class="statusDone">已通过
                            @if (isset($item->purchaseorder_hxold->arrival))
                                {{-- mb_substr: 中文截取 --}}
                                {{ mb_substr($item->purchaseorder_hxold->arrival, 0, 2) }}
                            @endif
                        </div>      {{-- 此时，字体要修改为灰色 --}}
                    @elseif ($item->approversetting_id == -3)
                        <div class="statusTodo">撤回中
                            @if (isset($item->purchaseorder_hxold->arrival))
                                {{-- mb_substr: 中文截取 --}}
                                {{ mb_substr($item->purchaseorder_hxold->arrival, 0, 2) }}
                            @endif
                        </div>
                    @elseif ($item->approversetting_id == -4)
                        <div class="statusDoneNotPass">已撤回
                            @if (isset($item->purchaseorder_hxold->arrival))
                                {{-- mb_substr: 中文截取 --}}
                                {{ mb_substr($item->purchaseorder_hxold->arrival, 0, 2) }}
                            @endif
                        </div>
                    @else
                        <div class="statusDoneNotPass">未通过
                            @if (isset($item->purchaseorder_hxold->arrival))
                                {{-- mb_substr: 中文截取 --}}
                                {{ mb_substr($item->purchaseorder_hxold->arrival, 0, 2) }}
                            @endif
                        </div>
                    @endif

                    @if (isset($item->purchaseorder_hxold->amount_ticketed) and isset($item->purchaseorder_hxold->amount) and $item->purchaseorder_hxold->amount > 0.0 and $item->purchaseorder_hxold->amount_ticketed / $item->purchaseorder_hxold->amount > 0.9999)
                        票
                    @endif
                    @if (isset($item->purchaseorder_hxold->arrival_percent) and $item->purchaseorder_hxold->arrival_percent >= 0.9999)
                        货
                    @endif

                    {{-- 已付款比例 --}}
                    @if (isset($item->purchaseorder_hxold->amount_paid))
                        {{ number_format($item->purchaseorder_hxold->amount_paid / $item->purchaseorder_hxold->amount * 100.0, 2, '.', '') . '%' }}
                    @endif
                </div>
            </a>
        </div>
    @endforeach
@elseif ($inputs['approvaltype'] == '下发图纸' && isset($items) && $items->count())
    @foreach($items as $item)
        <div class="reimbList list-group">
            <a href="{{ url($href_pre_issuedrawing . $item->id . $href_suffix) }}" class="list-group-item">
                {{-- 以下的代码判断说明：如果用户的头像url为空，则以名字显示，否则以这个头像url来显示图片 --}}
                @if (isset($item->applicant->dtuser->avatar))
                    <div class='col-xs-3 col-sm-3 headIcon'><img class="name img" src="{{ $item->applicant->dtuser->avatar }}" /></div>
                @else
                    <div class='col-xs-3 col-sm-3 name headIcon'>{{ $item->applicant->name }}</div>
                @endif
                <div class='col-xs-6 col-sm-6 content'>
                    <div title="{{ $item->applicant->name }}的下发图纸" class="title">
                        <div class='longText'>{{ $item->business_id }} | {{ $item->tonnage }}</div>
                        {{-- 示例：山东奥博环保科技有限公司 --}}
                        {{-- @if (isset($item->supplier_hxold->name)) {{ $item->supplier_hxold->name }} @endif --}}
                        <div class='longText'>@if (isset($item->supplier_hxold->name)) {{ $item->supplier_hxold->name }} @endif</div>
                        {{-- 示例：浙江锦润机电成套设备有限公司 --}}
                        {{-- @if (isset($item->purchaseorder_hxold->custinfo_name)) {{ $item->purchaseorder_hxold->custinfo_name }} @endif --}}
                        <div class='longText'>@if (isset($item->purchaseorder_hxold->custinfo_name)) {{ $item->purchaseorder_hxold->custinfo_name }} @endif</div>
                        {{-- 示例：高密垃圾焚烧发电项目1#2#炉烟气脱硫净化装置系统工程 --}}
                        {{-- @if (isset($item->purchaseorder_hxold->sohead_descrip)) {{ $item->purchaseorder_hxold->sohead_descrip }} @endif--}}
                        <div class='longText'>
                            @if (isset($item->sohead_hxold->projectjc))
                                {{ $item->sohead_hxold->projectjc }}
                            @elseif (isset($item->sohead_hxold->descrip))
                                {{ $item->sohead_hxold->descrip }}
                            @endif
                        </div>
                    </div>


                </div>

                <div class='col-xs-3 col-sm-3 time'>
                    @if (isset($item->purchaseorder_hxold->productname))
                        <div class='product'>{{ $item->purchaseorder_hxold->productname }}</div>
                    @endif

                    <span >{{ $item->created_at }}</span><br/>
                    @if ($item->status == 1)
                        <div class="statusTodo">审批中
                            @if (isset($item->purchaseorder_hxold->arrival))
                                {{-- mb_substr: 中文截取 --}}
                                {{ mb_substr($item->purchaseorder_hxold->arrival, 0, 2) }}
                            @endif
                        </div>
                    @elseif ($item->status == 0)
                        <div class="statusDone">已通过
                            @if (isset($item->purchaseorder_hxold->arrival))
                                {{-- mb_substr: 中文截取 --}}
                                {{ mb_substr($item->purchaseorder_hxold->arrival, 0, 2) }}
                            @endif
                        </div>      {{-- 此时，字体要修改为灰色 --}}
                    @elseif ($item->status == -1)
                        <div class="statusTodo">已拒绝
                            @if (isset($item->purchaseorder_hxold->arrival))
                                {{-- mb_substr: 中文截取 --}}
                                {{ mb_substr($item->purchaseorder_hxold->arrival, 0, 2) }}
                            @endif
                        </div>
                    @elseif ($item->status == -2)
                        <div class="statusDoneNotPass">已撤回
                            @if (isset($item->purchaseorder_hxold->arrival))
                                {{-- mb_substr: 中文截取 --}}
                                {{ mb_substr($item->purchaseorder_hxold->arrival, 0, 2) }}
                            @endif
                        </div>
                    @else
                        <div class="statusDoneNotPass">未通过
                            @if (isset($item->purchaseorder_hxold->arrival))
                                {{-- mb_substr: 中文截取 --}}
                                {{ mb_substr($item->purchaseorder_hxold->arrival, 0, 2) }}
                            @endif
                        </div>
                    @endif

                    @if (isset($item->purchaseorder_hxold->amount_ticketed) and isset($item->purchaseorder_hxold->amount) and $item->purchaseorder_hxold->amount > 0.0 and $item->purchaseorder_hxold->amount_ticketed / $item->purchaseorder_hxold->amount > 0.9999)
                        票
                    @endif
                    @if (isset($item->purchaseorder_hxold->arrival_percent) and $item->purchaseorder_hxold->arrival_percent >= 0.9999)
                        货
                    @endif

                    {{-- 已付款比例 --}}
                    @if (isset($item->purchaseorder_hxold->amount_paid))
                        {{ number_format($item->purchaseorder_hxold->amount_paid / $item->purchaseorder_hxold->amount * 100.0, 2, '.', '') . '%' }}
                    @endif
                </div>
            </a>
        </div>
    @endforeach
@endif

@if (isset($paymentrequestretracts) && $paymentrequestretracts->count())

@endif
