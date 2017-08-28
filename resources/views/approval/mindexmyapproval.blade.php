@extends('approval.mindexmyapproval_nav')

@section('title', '待我审批的')

@section('mindexmyapproval_main')

    {!! Form::open(['url' => '/approval/mindexmyapproval/search', 'method' => 'post', 'role' => 'search']) !!}
    <div class="container-fluid">
        <div class="row">
            <div class="input-group">
                {!! Form::text('key', null, ['class' => 'form-control', 'placeholder' => '支付对象、对应项目名称、商品名称']) !!}
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

    @include('approval._list',
        [
            'href_pre' => '/approval/reimbursementapprovals/', 'href_suffix' => '/mcreate',
            'href_pre_paymentrequest' => '/approval/paymentrequestapprovals/'
        ])


    {!! $paymentrequests->links() !!}
{{--
    @if (isset($key))
        {!! $paymentrequests->setPath('/approval/mindexmyapproval')->appends(['key' => $key])->links() !!}
    @else
        {!! $paymentrequests->setPath('/approval/mindexmyapproval')->links() !!}
    @endif
--}}


@endsection
