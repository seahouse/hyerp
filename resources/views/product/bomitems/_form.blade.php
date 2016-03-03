<div class="form-group">
    {!! Form::label('parent_item_id', '主物料:') !!}
    {!! Form::select('parent_item_id', $itemList, $parentSelected, ['class' => 'form-control']) !!}
</div>

<div class="form-group">
    {!! Form::label('item_id', '物料编号:') !!}
    {!! Form::select('item_id', $itemList, null, ['class' => 'form-control']) !!}
</div>

<div class="form-group">
    {!! Form::label('qtyper', '数量:') !!}
    {!! Form::text('qtyper', null, ['class' => 'form-control']) !!}
</div>

<!-- <div class="form-group"> -->
<!--     {!! Form::label('item_descrip', '描述:') !!} -->
<!--     {!! Form::text('item_descrip', null, ['class' => 'form-control']) !!} -->
<!-- </div> -->



<!-- <div class="form-group"> -->
<!--     {!! Form::label('marketprice', '价格:') !!} -->
<!--     {!! Form::text('marketprice', '0.0', ['class' => 'form-control']) !!} -->
<!-- </div> -->

<div class="form-group">
    {!! Form::submit($submitButtonText, ['class' => 'btn btn-primary form-control']) !!}
</div>
