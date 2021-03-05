<div class="form-group">
    {!! Form::label('voucher_no', '凭证号:') !!}
    {!! Form::text('voucher_no', null, ['class' => 'form-control', 'required']) !!}
</div>

<div class="form-group">
    {!! Form::label('amount', '金额:') !!}
    <input type="number" name="amount" id="amount" class="form-control" required min="0" step="0.01" value="{{ isset($voucher->amount)?$voucher->amount:old('amount') }}">
</div>

<div class="form-group">
    {!! Form::label('post_date', '到账日期:') !!}
    <input type="date" name="post_date" id="post_date" class="form-control" required value="{{ isset($voucher->post_date)?$voucher->post_date:(old('post_date')?old('post_date'):date('Y-m-d')) }}">
</div>

<div class="form-group">
    {!! Form::label('remark', '备注:') !!}
    {!! Form::text('remark', null, ['class' => 'form-control']) !!}
</div>

<div class="form-group">
    {!! Form::submit($submitButtonText, ['class' => 'btn btn-primary form-control']) !!}
</div>