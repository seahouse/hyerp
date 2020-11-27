<div class="reimb">
    <div class="form-d">
        <div class="form-group">
            {!! Form::label('number', '编号:', ['class' => 'col-xs-4 col-sm-2 control-label']) !!}
            <div class='col-xs-8 col-sm-10'>
                {!! Form::text('number', null, ['class' => 'form-control', $attr, 'readonly']) !!}
            </div>
        </div>

        <div class="form-group">
            {!! Form::label('applicant_name', '申请人:', ['class' => 'col-xs-4 col-sm-2 control-label']) !!}
            <div class='col-xs-8 col-sm-10'>
                {!! Form::text('applicant_name', null, ['class' => 'form-control', $attr, 'readonly']) !!}
            </div>
        </div>

        <div class="form-group">
            {!! Form::label('sohead_number', '对应项目:', ['class' => 'col-xs-4 col-sm-2 control-label']) !!}
            <div class='col-xs-8 col-sm-10'>
                {!! Form::text('sohead_number', null . '!' . $prhead->sohead->descrip, ['class' => 'form-control', $attr, 'readonly']) !!}

            </div>
        </div>

        <div class="form-group">
            {!! Form::label('type', '类型:', ['class' => 'col-xs-4 col-sm-2 control-label']) !!}
            <div class='col-xs-8 col-sm-10'>
                {!! Form::text('type', null, ['class' => 'form-control', $attr, 'readonly']) !!}
            </div>
        </div>

        <div class="form-group">
            {!! Form::label('business_id', '对应审批编号:', ['class' => 'col-xs-4 col-sm-2 control-label']) !!}
            <div class='col-xs-8 col-sm-10'>
                {!! Form::text('business_id', null, ['class' => 'form-control', $attr, 'readonly']) !!}
            </div>
        </div>

        <label for="btnAdd" class="col-xs-4 col-sm-2 control-label">供应商</label>
        <div class='col-xs-8 col-sm-10'>
            <button type="button" id="btnAdd" class="btn btn-sm" data-toggle="modal" data-target="#selectModalSupplier">+</button>

            <table class="table table-striped table-bordered table-hover table-condensed">
                <thead>
                    <tr>
                        <th width="45px"></th>
                        <th>名称 (打勾表示圈定)</th>
                    </tr>
                </thead>
                <tbody id="tblBody">
                    @foreach($prhead->suppliers as $s)
                    <tr>
                        <td>
                            <a href='javascript:void(0);' onclick='window.removeitemClick(this);'>删除</a>
                        </td>
                        <td>
                            <label><input type='checkbox' onclick='window.markSelected(this);' @if($s->selected) checked @endif> {{ $s->item->name }}</label>
                            <input type='hidden' name='suppliers[]' value='{{ $s->supplier_id }}'>
                            <input type="hidden" name="chk_suppliers[]" class="chk" value="{{ $s->selected }}">
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class=" form-group">
            <div class="col-sm-offset-2 col-sm-10">
                {!! Form::submit($submitButtonText, ['class' => $btnclass, 'id' => 'btnSubmit']) !!}
            </div>
        </div>
    </div>
</div>