<div class="form-group">
    {!! Form::label('number', '编号:') !!}
    {!! Form::text('number', null, ['class' => 'form-control']) !!}
</div>

<div class="form-group">
    {!! Form::label('orderdate', '订单日期:') !!}
    {!! Form::input('date', 'orderdate', null, ['class' => 'form-control']) !!}
</div>

<div class="form-group">
    {!! Form::label('descrip', '工程名称:') !!}
    {!! Form::text('descrip', null, ['class' => 'form-control']) !!}
</div>

<div class="form-group">
    {!! Form::label('status', '状态:') !!}
    {!! Form::select('status', array(0 => '打开', 1 => '关闭'), null, ['class' => 'form-control']) !!}
</div>

<div class="form-group">
    {!! Form::label('vendinfo_id', '供应商:') !!}
    {!! Form::select('vendinfo_id', $vendinfoList, null, ['class' => 'form-control']) !!}
</div>

{{--
<div class="form-group">
    {!! Form::label('fob', '承运商:') !!}
    {!! Form::text('fob', null, ['class' => 'form-control']) !!}
</div> --}}

<div class="form-group">
    {!! Form::label('shipvia', '承运商:') !!}
    {!! Form::text('shipvia', null, ['class' => 'form-control']) !!}
</div>

<div class="form-group">
    {!! Form::label('comments', '备注:') !!}
    {!! Form::text('comments', null, ['class' => 'form-control']) !!}
</div>

<div class="form-group">
    {!! Form::label('freight', '运费:') !!}
    {!! Form::text('freight', '0.0', ['class' => 'form-control']) !!}
</div>

<div class="form-group">
    {!! Form::label('term_id', '付款方式:') !!}
    {!! Form::select('term_id', $termList, null, ['class' => 'form-control']) !!}
</div>


<div class="form-group">
    {!! Form::label('vend_contact_id', '供应商联系人:') !!}
    {!! Form::select('vend_contact_id', $contactList, null, ['class' => 'form-control']) !!}
</div>

<div class="form-group">
    {!! Form::label('vendaddress', '供应商地址:') !!}
    {!! Form::text('vendaddress', null, ['class' => 'form-control']) !!}
</div>

<div class="form-group">
    {!! Form::label('shipto_account_id', '收货联系人:') !!}
    {!! Form::select('shipto_account_id', $contactList, null, ['class' => 'form-control']) !!}
</div>

<div class="form-group">
    {!! Form::label('shiptoaddress', '收货地址:') !!}
    {!! Form::text('shiptoaddress', null, ['class' => 'form-control']) !!}
</div>

<div class="form-group">
    {!! Form::label('sohead_id', '销售订单:') !!}
    {!! Form::select('sohead_id', $soheadList, null, ['class' => 'form-control']) !!}
</div>

<div class="form-group">
    {!! Form::submit($submitButtonText, ['class' => 'btn btn-primary form-control']) !!}
</div>
