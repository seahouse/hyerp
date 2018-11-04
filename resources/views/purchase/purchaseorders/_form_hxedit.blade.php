@if (isset($purchaseorder))
    <div class="form-group">
        {!! Form::label('project_name', '所属项目:', ['class' => 'col-xs-4 col-sm-2 control-label']) !!}
        <div class='col-xs-8 col-sm-10'>
            {!! Form::text('project_name', $purchaseorder->sohead->descrip, ['class' => 'form-control', $attr, 'data-toggle' => 'modal', 'data-target' => '#selectProjectModal', 'data-name' => 'project_name', 'data-id' => '对应项目ID']) !!}
            {!! Form::hidden('id', null, ['class' => 'btn btn-sm', 'id' => 'pohead_id']) !!}
        </div>
    </div>

    <div class="form-group">
        {!! Form::label('sohead_number', '项目编号:', ['class' => 'col-xs-4 col-sm-2 control-label']) !!}
        <div class='col-xs-8 col-sm-10'>
            {!! Form::text('sohead_number', $purchaseorder->sohead->number, ['class' => 'form-control', 'readonly', $attr]) !!}
        </div>
    </div>
@endif

<div class="form-group">
    {!! Form::label('amount', '采购订单金额:', ['class' => 'col-xs-4 col-sm-2 control-label']) !!}
    <div class='col-xs-8 col-sm-10'>
        {!! Form::text('amount', null, ['class' => 'form-control', $attr]) !!}
    </div>
</div>

<div class="form-group">
    {!! Form::label('status', '采购订单状态:', ['class' => 'col-xs-4 col-sm-2 control-label']) !!}
    <div class='col-xs-8 col-sm-10'>
        {!! Form::select('status', array('10' => '普通订单', '20' => '额外成本'), null, ['class' => 'form-control', 'placeholder' => '--请选择--', $attr, $attrdisable]) !!}
    </div>
</div>

<div class="form-group">
    {!! Form::label('taxrate', '税率:', ['class' => 'col-xs-4 col-sm-2 control-label']) !!}
    <div class='col-xs-8 col-sm-10'>
        {!! Form::text('poheadtaxrateasses', $purchaseorder->poheadtaxrate_str, ['class' => 'form-control', $attr, 'readonly']) !!}
        {!! Form::button("编辑", ['class' => 'btn btn-sm btn-info', 'id' => 'btnEditTaxrate', 'data-toggle' => 'modal', 'data-target' => '#editTaxrateModal', 'data-pohead_id' => $purchaseorder->id]) !!}
    </div>
</div>

{{--
<div class="form-group">
    <div class="col-sm-offset-2 col-sm-10">
        {!! Form::submit($submitButtonText, ['class' => $btnclass, 'id' => 'btnSubmit']) !!}
    </div>
</div>
--}}
