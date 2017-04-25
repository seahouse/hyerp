@extends('navbarerp')

@section('main')
    <h1>设置老系统用户</h1>
    <hr/>
    
    {!! Form::model($user, ['method' => 'POST', 'action' => ['System\UsersController@updategoogle2fa', $user->id], 'class' => 'form-horizontal',
        'id' => 'formMain']) !!}
        @include('system.users._form_editgoogle2fa', ['submitButtonText' => '保存'])
    {!! Form::close() !!}
    
    @include('errors.list')

    <div class="modal fade" id="submitModal" tabindex="-1" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">提交确认</h4>
                </div>
                <div class="modal-body">
                    <p>
                    此用户可能有重复设置，是否继续提交？
                    </p>
                    <form id="formAccept">

                    </form>
                </div>
                <div class="modal-footer">
                    {!! Form::button('取消', ['class' => 'btn btn-sm', 'data-dismiss' => 'modal']) !!}
                    {!! Form::button('继续提交', ['class' => 'btn btn-sm', 'id' => 'btnSubmitContinue']) !!}
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script type="text/javascript">
        jQuery(document).ready(function(e) {
            $("#btnGenerateSecretKey").click(function() {
                $.get("{{ url('google2fa/generatesecretkey') }}", function (data) {
                    $("#google2fa_secret").val(data);
//                    if (data > 0)
//                        $('#submitModal').modal('toggle');
//                    else
//                        $("form#formMain").submit();
                });
            });

            {{--$("#btnSubmit").click(function() {--}}
                {{--$.get("{{ url('system/userold/hasrepeatoldid/') }}" +  "/" + $('#user_hxold_id').val(), function (data) {--}}
                    {{--if (data > 0)--}}
                        {{--$('#submitModal').modal('toggle');--}}
                    {{--else--}}
                        {{--$("form#formMain").submit();--}}
                {{--});--}}
                {{--return false;--}}
            {{--});--}}

            {{--$("#btnSubmitContinue").click(function() {--}}
                {{--$("form#formMain").submit();--}}
            {{--});--}}
        });
    </script>
@endsection