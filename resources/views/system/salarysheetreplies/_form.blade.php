
{!! Form::hidden('salarysheet_id', null, ['class' => 'form-control']) !!}

<div class="form-group">
    <div style="padding-top:5px;" class="col-sm-offset-2 col-sm-10">
    {!! Form::button($acceptButtonText, ['class' => 'btn btn-primary', 'data-toggle' => 'modal', 'data-target' => '#acceptModal']) !!}
    {!! Form::button($rejectButtonText, ['class' => 'btn btn-primary', 'data-toggle' => 'modal', 'data-target' => '#rejectModal']) !!}
    </div>
</div>

@if (Auth::user()->email == "admin@admin.com")
{{--<div class="form-group">--}}
    {{--<div class="col-sm-offset-2 col-sm-10">--}}
    {{--{!! Form::open(['url' => url('/dingtalk/chat_create')]) !!}--}}
        {{--{!! Form::submit('聊天', ['class' => 'btn btn-primary']) !!}--}}
    {{--{!! Form::close() !!}--}}
    {{--</div>--}}
{{--</div>--}}
@endif

<div class="modal fade" id="acceptModal" tabindex="-1" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">准确无误</h4>
            </div>
            <div class="modal-body">
                <form id="formAccept">
                    {!! csrf_field() !!}
                    {!! Form::text('message', null, ['class' => 'form-control', 'placeholder' => '还想说点什么？']) !!}
                    {!! Form::hidden('salarysheet_id', $salarysheet->id, ['class' => 'form-control']) !!}
                    {!! Form::hidden('status', 0, ['class' => 'form-control']) !!}
                </form>                
            </div>
            <div class="modal-footer">
                {!! Form::button('取消', ['class' => 'btn btn-sm', 'data-dismiss' => 'modal']) !!}
                {!! Form::button('确定', ['class' => 'btn btn-sm', 'id' => 'btnAccept']) !!}
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="rejectModal" tabindex="-1" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">请输入异议原因</h4>
            </div>
            <div class="modal-body">
                <form id="formReject">
                    {!! csrf_field() !!}
                    {!! Form::text('message', null, ['class' => 'form-control']) !!}
                    {!! Form::hidden('salarysheet_id', $salarysheet->id, ['class' => 'form-control']) !!}
                    {!! Form::hidden('status', -1, ['class' => 'form-control']) !!}
                </form>                
            </div>
            <div class="modal-footer">
                {!! Form::button('取消', ['class' => 'btn btn-sm', 'data-dismiss' => 'modal']) !!}
                {!! Form::button('确定', ['class' => 'btn btn-sm', 'id' => 'btnReject']) !!}
            </div>
        </div>
    </div>
</div>

