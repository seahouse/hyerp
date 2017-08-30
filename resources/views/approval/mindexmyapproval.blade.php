@extends('approval.mindexmyapproval_nav')

@section('title', '待我审批的')

@section('mindexmyapproval_main')


    {!! Form::open(['url' => '/approval/mindexmyapproval/search', 'method' => 'post', 'role' => 'search', 'class' => 'form-horizontal']) !!}
    <div class="container-fluid">
        <div class="row">
            {!! Form::select('approvaltype', array('供应商付款' => '供应商付款', '供应商付款撤回' => '供应商付款撤回'), null, ['class' => 'form-control', 'id' => 'approvaltype']) !!}
        </div>
        <div class="row">
            {!! Form::text('key', null, ['class' => 'form-control', 'placeholder' => '支付对象、对应项目名称、商品名称']) !!}
        </div>
        <div class="row">
            {!! Form::submit('查找', ['class' => 'btn btn-default']) !!}
        </div>
    </div>
    {{--
    <div class="container-fluid">
        <div class="row">
            <div class="input-group">
                {!! Form::select('approvaltype', array('供应商付款' => '供应商付款', '供应商付款撤回' => '供应商付款撤回'), null, ['class' => 'form-control', 'id' => 'approvaltype']) !!}
                {!! Form::text('key', null, ['class' => 'form-control', 'placeholder' => '支付对象、对应项目名称、商品名称']) !!}
                <span class="input-group-btn">
                    {!! Form::submit('查找', ['class' => 'btn btn-default']) !!}
                    </span>
            </div>
        </div>
    </div>
    --}}
    {!! Form::close() !!}

    @include('approval._list',
        [
            'href_pre' => '/approval/reimbursementapprovals/', 'href_suffix' => '/mcreate',
            'href_pre_paymentrequest' => '/approval/paymentrequestapprovals/',
            'href_pre_paymentrequestretract' => '/approval/paymentrequestretractapproval/'
        ])


    {{--
        {!! $paymentrequests->links() !!}
    --}}
        @if (isset($key))
            {!! $paymentrequests->setPath('/approval/mindexmyapproval')->appends([
                'key' => $key,
                'approvaltype', $inputs['approvaltype']
            ])->links() !!}
        @else
            {!! $paymentrequests->setPath('/approval/mindexmyapproval')->links() !!}
        @endif

@endsection

@section('script')
    <script type="text/javascript">
        jQuery(document).ready(function(e) {
            @if (isset($inputs['approvaltype']))
                $('#approvaltype').val("{{ $inputs['approvaltype'] }}");
            @endif


















        });
    </script>
@endsection