<div class="reimb"><div class="form-d">




        @if (isset($issuedrawing))

            <div class="form-group">
                {!! Form::label('oldtonnage', '原吨位（吨）:', ['class' => 'col-xs-4 col-sm-2 control-label']) !!}
                <div class='col-xs-8 col-sm-10'>
                    {!! Form::text('oldtonnage', $issuedrawing->tonnage, ['class' => 'form-control', $attr]) !!}
                </div>
            </div>

            <div class="form-group">
                {!! Form::label('tonnage', '新吨位（吨）:', ['class' => 'col-xs-4 col-sm-2 control-label']) !!}
                <div class='col-xs-8 col-sm-10'>
                    {!! Form::text('tonnage', null, ['class' => 'form-control']) !!}
                </div>
            </div>

            <div class="form-group">
                {!! Form::label('reason', '修改原因:', ['class' => 'col-xs-4 col-sm-2 control-label']) !!}
                <div class='col-xs-8 col-sm-10'>
                    {!! Form::text('reason', null, ['class' => 'form-control']) !!}
                </div>
            </div>
        @else








        @endif













        <div class="form-group">
            <div class="col-sm-offset-2 col-sm-10">
                {!! Form::submit($submitButtonText, ['class' => $btnclass, 'id' => 'btnSubmit']) !!}
            </div>
        </div>
    </div>
</div>



