@extends('navbarerp')

@section('title', '供应商付款')

@section('main')
@can('approval_synchronize')
    <div class="panel-heading">
        <div class="panel-title">审批 -- 同步

        </div>
    </div>
    
    <div class="panel-body">
        {!! Form::open(['url' => '/approval/synchronize/synchronize', 'class' => 'form-inline', 'id' => 'frmSynchronize']) !!}
            {!! Form::select('approvaltype', $approvaltypes, null, ['class' => 'form-control']) !!}
            {!! Form::text('business_id', null, ['class' => 'form-control', 'placeholder' => '钉钉审批单编号']) !!}
            {{--{!! Form::button('查询与验证', ['class' => 'btn btn-default btn-sm']) !!}--}}
            {!! Form::button('同步到华星审批', ['class' => 'btn btn-default btn-sm', 'id' => 'btnSynchronize']) !!}
            {{--{!! Form::label('message_issuedrawing', '处理信息: ', ['class' => 'control-label']) !!}--}}
        {!! Form::close() !!}
    </div>





@else
无权限
@endcan
@endsection

@section('script')
    <script type="text/javascript">
        jQuery(document).ready(function(e) {
            $("#btnSynchronize").click(function() {
                $.ajax({
                    type: "POST",
                    url: "{{ url('approval/synchronize/synchronize') }}",
                     data: $("form#frmSynchronize").serialize(),
                    // dataType: "json",
                    error:function(xhr, ajaxOptions, thrownError){
                        alert('error');
                    },
                    success:function(result){
                        alert("同步结果:" + result);
                    },
                }); 
            });

        });
    </script>
@endsection