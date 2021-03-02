<div class="form-group">
    {!! Form::label('voucher_no', '凭证号:') !!}
    {!! Form::text('voucher_no', null, ['class' => 'form-control']) !!}
</div>

<div class="form-group">
    {!! Form::label('amount', '金额:') !!}
    {!! Form::text('amount', null, ['class' => 'form-control']) !!}
</div>

<div class="form-group">
    {!! Form::label('post_date', '到账日期:') !!}
    <input type="date" name="post_date" id="post_date" class="form-control">
</div>

<div class="form-group">
    {!! Form::label('remark', '备注:') !!}
    {!! Form::text('remark', null, ['class' => 'form-control']) !!}
</div>

<div class="form-group">
    {!! Form::submit($submitButtonText, ['class' => 'btn btn-primary form-control']) !!}
</div>