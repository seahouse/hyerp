@extends('approval.mindexmyapproval_nav')

@section('title', '我已审批的')

@section('mindexmyapproval_main')
   
    {!! Form::open(['url' => '/approval/mindexmyapprovaled/search', 'method' => 'post', 'role' => 'search']) !!}
        <div class="container-fluid">
            <div class="row">
                <div class="input-group">
                    {!! Form::text('key', null, ['class' => 'form-control', 'placeholder' => '支付对象、对应项目名称']) !!}
                    <span class="input-group-btn">
{{--
                        {!! Form::button('查找', ['class' => 'btn btn-default']) !!}
--}}
                        {!! Form::submit('查找', ['class' => 'btn btn-default']) !!}

                    </span>
                </div>
            </div>
        </div>
    {!! Form::close() !!}

    @if (Agent::isDesktop() && (Auth::user()->email == "wangai@huaxing-east.com" || Auth::user()->email == "shenhaixia@huaxing-east.com"))
        @include('approval._list',
            [
                'href_pre' => '/approval/reimbursements/mshow/', 'href_suffix' => '/printpage',
                'href_pre_paymentrequest' => '/approval/paymentrequests/'
            ])
    @else
        @include('approval._list',
            [
                'href_pre' => '/approval/reimbursements/mshow/', 'href_suffix' => '',
                'href_pre_paymentrequest' => '/approval/paymentrequests/mshow/'
            ])
    @endif
{{--
    {!! $paymentrequests->links() !!}
--}}
    @if (isset($key))
        {!! $paymentrequests->setPath('/approval/mindexmyapprovaled')->appends(['key' => $key])->links() !!}
    @else
        {!! $paymentrequests->setPath('/approval/mindexmyapprovaled')->links() !!}
    @endif


@endsection
