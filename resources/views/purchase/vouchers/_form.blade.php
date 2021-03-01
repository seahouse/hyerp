<div class="form-group">
    {!! Form::label('number', '编号:') !!}
    {!! Form::text('number', null, ['class' => 'form-control']) !!}
</div>

<div class="form-group">
    {!! Form::label('name', '名称:') !!}
    {!! Form::text('name', null, ['class' => 'form-control']) !!}
</div>

<div class="form-group">
    {!! Form::label('term_id', '付款方式:') !!}
    {!! Form::select('term_id', $termList, null, ['class' => 'form-control']) !!}
</div>

<div class="form-group">
    {!! Form::label('shipvia', '承运商:') !!}
    {!! Form::text('shipvia', null, ['class' => 'form-control']) !!}
</div>

<div class="form-group">
    {!! Form::label('vendtype_id', '供应商类型:') !!}
    {!! Form::select('vendtype_id', $vendtypeList, null, ['class' => 'form-control']) !!}
</div>

<div class="form-group">
    {!! Form::label('contact1_id', '联系人1:') !!}
    {!! Form::select('contact1_id', $contactList, null, ['class' => 'form-control']) !!}
</div>

<div class="form-group">
    {!! Form::label('contact2_id', '联系人2:') !!}
    {!! Form::select('contact2_id', $contactList, null, ['class' => 'form-control']) !!}
</div>

<div class="form-group">
    {!! Form::label('addr_id', '地址:') !!}
    {!! Form::select('addr_id', $addrList, null, ['class' => 'form-control']) !!}
</div>

<div class="form-group">
    {!! Form::submit($submitButtonText, ['class' => 'btn btn-primary form-control']) !!}
</div>
