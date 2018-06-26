@extends('approval.mindexmy_nav')

@section('title', '我发起的')

@section('mindexmy_main')
    
    {!! Form::open(['url' => '/approval/mindexmyed/search', 'method' => 'post', 'role' => 'search']) !!}
        <div class="container-fluid search-area">
            <div class="row">
                <div class="ctrl1">
                    {!! Form::select('approvaltype', array('供应商付款' => '供应商付款', '下发图纸' => '下发图纸'), null, ['class' => 'form-control ctrl1', 'id' => 'approvaltype']) !!}
                </div>
            </div>
            <div class="row">
                <div class="input-group">
                    {!! Form::text('key', null, ['class' => 'form-control', 'placeholder' => '支付对象、对应项目名称']) !!}
                    <span class="input-group-btn">
                        {!! Form::submit('搜索', ['class' => 'btn btn-primary search']) !!}
{{--
                        {!! Form::submit('查找', ['class' => 'btn btn-default']) !!}
--}}
                    </span>
                </div>
            </div>
        </div>
    {!! Form::close() !!}

    @include('approval._list',
        [
            'href_pre' => '/approval/reimbursements/mshow/', 'href_suffix' => '',
            'href_pre_paymentrequest' => '/approval/paymentrequests/mshow/',
            'href_pre_issuedrawing' => '/approval/issuedrawing/mshow/'
        ])

    @if (isset($key))
        {!! $items->setPath('/approval/mindexmyed')->appends(['key' => $key])->links() !!}
    @else
        {!! $items->links() !!}
    @endif
        

@endsection
