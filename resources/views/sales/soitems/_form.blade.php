
<div class="form-group">
    {!! Form::label('item_id', '物料:') !!}
    {!! Form::select('item_id', $itemList, null, ['class' => 'form-control']) !!}
</div>

<div class="form-group">
    {!! Form::label('qty', '数量:') !!}
    {!! Form::text('qty', null, ['class' => 'form-control']) !!}
</div>


<div class="form-group">
    {!! Form::label('price', '单价:') !!}
    {!! Form::text('price', null, ['class' => 'form-control']) !!}
</div>

{!! Form::hidden('sohead_id', $headId, ['class' => 'form-control']) !!}

<div class="form-group">
    {!! Form::submit($submitButtonText, ['class' => 'btn btn-primary form-control']) !!}
</div>
