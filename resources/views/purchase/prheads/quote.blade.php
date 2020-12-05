@extends('navbarerp')

@section('main')
<h1>报价</h1>
<hr />
@if(Auth::user()->supplier_id && in_array(Auth::user()->supplier_id, $prhead->suppliers->pluck('supplier_id')->toArray()))
{!! Form::model($prhead, ['method' => 'POST', 'action' => ['Purchase\PrheadController@updatequote', $prhead->id], 'class' => 'form-horizontal', 'files' => true]) !!}
<div class="reimb">
    <div class="form-d">
        <div class="form-group">
            {!! Form::label('number', '编号:', ['class' => 'col-xs-4 col-sm-2 control-label']) !!}
            <div class='col-xs-8 col-sm-10'>
                {!! Form::text('number', null, ['class' => 'form-control', 'readonly']) !!}
            </div>
        </div>

        <div class="form-group">
            {!! Form::label('applicant_name', '联系人:', ['class' => 'col-xs-4 col-sm-2 control-label']) !!}
            <div class='col-xs-8 col-sm-10'>
                {!! Form::text('applicant_name', null, ['class' => 'form-control', 'readonly']) !!}
            </div>
        </div>

        <div class="form-group">
            {!! Form::label('sohead_number', '对应项目:', ['class' => 'col-xs-4 col-sm-2 control-label']) !!}
            <div class='col-xs-8 col-sm-10'>
                {!! Form::text('sohead_number', $prhead->sohead->number . '!' . $prhead->sohead->descrip, ['class' => 'form-control', 'readonly']) !!}
            </div>
        </div>

        <div class="form-group">
            {!! Form::label('amount', '总价:', ['class' => 'col-xs-4 col-sm-2 control-label']) !!}
            <div class='col-xs-8 col-sm-10'>
                <div class="input-group">
                    <span class="input-group-addon">&#165;</span>
                    <input type="number" class="form-control text-input" name="amount" id="amount" min="0" required step="0.01" value="{{ $prhead->amount}}">
                    <span class="input-group-addon">元</span>
                </div>
            </div>
        </div>

        <div class="form-group">
            {!! Form::label('files', '附件:', ['class' => 'col-xs-4 col-sm-2 control-label']) !!}

            <div class='col-xs-8 col-sm-10'>
                <input type="file" name="files[]" multiple id="files">
            </div>
        </div>

        <div class="form-group">
            <div class="col-sm-offset-2 col-sm-10">
                {!! Form::submit('保存', ['class' => 'btn btn-primary', 'id' => 'btnSubmit']) !!}
            </div>
        </div>
    </div>
</div>
{!! Form::close() !!}
@include('errors.list')
@else
无权限
@endif
@endsection