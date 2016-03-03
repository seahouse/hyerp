
<div class="form-group">
    {!! Form::label('itemsite_id', '物料:') !!}
    {!! Form::select('itemsite_id', $itemsiteList, null, ['class' => 'form-control']) !!}
</div>

<div class="form-group">
    {!! Form::label('duedate', '到期日:') !!}
    {!! Form::input('date', 'duedate', null, ['class' => 'form-control']) !!}
</div>


<div class="form-group">
    {!! Form::label('qty_ordered', '订购数量:') !!}
    {!! Form::text('qty_ordered', '0.0', ['class' => 'form-control']) !!}
</div>

<div class="form-group">
    {!! Form::label('unitprice', '单价:') !!}
    {!! Form::text('unitprice', '0.0', ['class' => 'form-control']) !!}
</div>

<div class="form-group">
    {!! Form::label('comments', '备注:') !!}
    {!! Form::text('comments', null, ['class' => 'form-control']) !!}
</div>

<div class="form-group">
    {!! Form::label('freight', '运费:') !!}
    {!! Form::text('freight', '0.0', ['class' => 'form-control']) !!}
</div>

{!! Form::hidden('pohead_id', $headId, ['class' => 'form-control']) !!}

<div class="form-group">
    {!! Form::submit($submitButtonText, ['class' => 'btn btn-primary form-control']) !!}
</div>
