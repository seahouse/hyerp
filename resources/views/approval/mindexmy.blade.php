@extends('app')

@section('title', '我发起的')

@section('main')
    
    {!! Form::open(['url' => '/approval/mindexmy/search', 'method' => 'post', 'role' => 'search']) !!}
        <div class="container-fluid">
            <div class="row">
                <div class="input-group">
                    {!! Form::text('key', null, ['class' => 'form-control', 'placeholder' => '支付对象、对应项目名称']) !!}
                    <span class="input-group-btn">
                        {!! Form::button('查找', ['class' => 'btn btn-default']) !!}
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
            'href_pre_paymentrequest' => '/approval/paymentrequests/'
        ])

    @if (isset($key))
        {!! $paymentrequests->setPath('/approval/mindexmy')->appends(['key' => $key])->links() !!}
    @else
        {!! $paymentrequests->setPath('/approval/mindexmy')->links() !!}
    @endif


        

@endsection
