@extends('approval.mindexmy_nav')

@section('title', '我发起的')

@section('mindexmy_main')
    
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
            'href_pre_paymentrequest' => '/approval/paymentrequests/mshow/'
        ])

    @if (isset($key))
        {!! $items->setPath('/approval/mindexmyed')->appends(['key' => $key])->links() !!}
    @else
        {!! $items->links() !!}
    @endif
        

@endsection
