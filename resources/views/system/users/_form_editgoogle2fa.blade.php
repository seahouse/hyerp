
<div class="form-group">
    {!! Form::label('name', '姓名:', ['class' => 'col-sm-2 control-label']) !!}
    <div class='col-sm-10'>
        {!! Form::text('name', $user->name, ['class' => 'form-control', 'readonly' => true]) !!}
        {{--
        @if (isset($user->userold->user_hxold_id))
            {!! Form::text('name', $userList_hxold, $user->name, ['class' => 'form-control']) !!}
        @else
            {!! Form::select('user_hxold_id', $userList_hxold, null, ['class' => 'form-control']) !!}
        @endif
        --}}
    </div>
</div>

<div class="form-group">
    {!! Form::label('google2fa_secret', 'Google Authentication SecretKey:', ['class' => 'col-sm-2 control-label']) !!}
    <div class='col-sm-10'>
        {!! Form::text('google2fa_secret', $user->google2fa_secret, ['class' => 'form-control', 'readonly' => true]) !!}
    </div>
</div>

<div class="form-group">
    {!! Form::label('google2fa_url', 'google2fa_url:', ['class' => 'col-sm-2 control-label']) !!}
    <div class='col-sm-10'>
        {{  $google2fa_url = Google2FA::getQRCodeGoogleUrl('haiya', $user->email, $user->google2fa_secret) }}
        {{--
        {!! Form::text('google2fa_url', $google2fa_url, ['class' => 'form-control', 'readonly' => true]) !!}
        {!! Html::image($google2fa_url) !!}
        --}}
    </div>
</div>

<div class="form-group">
    <div class="col-sm-offset-2 col-sm-10">
        {!! Form::button('生成SecretKey', ['class' => 'btn btn-primary', 'id' => 'btnGenerateSecretKey']) !!}
        {!! Form::submit($submitButtonText, ['class' => 'btn btn-primary', 'id' => 'btnSubmit']) !!}
    </div>
</div>

