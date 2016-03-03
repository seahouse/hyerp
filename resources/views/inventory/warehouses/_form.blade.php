<div class="form-group">
    {!! Form::label('code', '代码:') !!}
    {!! Form::text('code', null, ['class' => 'form-control']) !!}
</div>

<div class="form-group">
    {!! Form::label('descrip', '描述:') !!}
    {!! Form::text('descrip', null, ['class' => 'form-control']) !!}
</div>

<div class="form-group">
    {!! Form::label('shipping_commission', '发货佣金:') !!}
    {!! Form::text('shipping_commission', '0.0', ['class' => 'form-control']) !!}
</div>

<div class="form-group">
    {!! Form::label('contact_id', '联系人:') !!}
    {!! Form::select('contact_id', $contactList, null, ['class' => 'form-control']) !!}
</div>

<div class="form-group">
    {!! Form::label('addr_id', '地址:') !!}
    {!! Form::select('addr_id', $addrList, null, ['class' => 'form-control']) !!}
</div>

<div class="form-group">
    {!! Form::submit($submitButtonText, ['class' => 'btn btn-primary form-control']) !!}
</div>
