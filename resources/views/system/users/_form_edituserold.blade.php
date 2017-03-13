

<div class="form-group">
    {!! Form::label('user_hxold_id', '姓名:', ['class' => 'col-sm-2 control-label']) !!}
    <div class='col-sm-10'>
        @if (isset($user->userold->user_hxold_id))
            {!! Form::select('user_hxold_id', $userList_hxold, $user->userold->user_hxold_id, ['class' => 'form-control']) !!}
        @else
            {!! Form::select('user_hxold_id', $userList_hxold, null, ['class' => 'form-control']) !!}
        @endif
    </div>
</div>


<div class="form-group">
    <div class="col-sm-offset-2 col-sm-10">
        {!! Form::submit($submitButtonText, ['class' => 'btn btn-primary', 'id' => 'btnSubmit']) !!}
    </div>
</div>

