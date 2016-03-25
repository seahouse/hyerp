
{!! Form::hidden('applicant_id', null, ['class' => 'form-control']) !!}

<div class="form-group">
    <div class="col-sm-offset-2 col-sm-10">
    {!! Form::button($acceptButtonText, ['class' => 'btn btn-sm', 'data-toggle' => 'modal', 'data-target' => '#acceptModal']) !!}
    {!! Form::button($rejectButtonText, ['class' => 'btn btn-sm', 'data-toggle' => 'modal', 'data-target' => '#rejectModal']) !!}
    </div>
</div>

<div class="modal fade" id="acceptModal" tabindex="-1" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">请输入同意理由（非必填）</h4>                
            </div>
            <div class="modal-body">
                {!! Form::text('description', null, ['class' => 'form-control']) !!}
            </div>
            <div class="modal-footer">
                {!! Form::button('取消', ['class' => 'btn btn-sm', 'data-dismiss' => 'modal']) !!}
                {!! Form::button('确定', ['class' => 'btn btn-sm']) !!}
            </div>
        </div>
    </div>
</div>



