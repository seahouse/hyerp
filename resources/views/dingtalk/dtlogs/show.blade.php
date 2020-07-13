@extends('navbarerp')

@section('main')
    {!! Form::model($dtlog, ['class' => 'form-horizontal']) !!}
    @include('dingtalk.dtlogs._form',
        [
            'submitButtonText' => '提交',
            'datepay' => null,
            'requestdeliverydate' => null,
            'customer_name' => null,
            'customer_id' => null,
            'amount' => null,
            'order_number' => null,
            'order_id' => null,
            'datego' => null,
            'dateback' => null,
            'mealamount' => null,
            'ticketamount' => null,
            'amountAirfares' => null,
            'amountTrain' => null,
            'amountTaxi' => null,
            'amountOtherTicket' => null,
            'stayamount' => null,
            'otheramount' => null,
            'attr' => 'readonly',
            'attrdisable' => 'disabled',
            'btnclass' => 'hidden',
        ])
    @if ($dtlog->template_name == '项目经理施工日志')
        <div class="form-group">
        {!! Form::label('number', '关联订单编号', ['class' => 'col-xs-4 col-sm-2  control-label']) !!}
            <div class='col-xs-8 col-sm-10'>
        {!! Form::text('number', $sohead_number, ['class' => 'form-control','readonly']) !!}
            </div>
        </div>
    @endif
    @foreach($dtlog->dtlogitems as $dtlogitem)
        <div class="form-group">
            {!! Form::label($dtlogitem->key, $dtlogitem->key, ['class' => 'col-xs-4 col-sm-2 control-label']) !!}
            <div class='col-xs-8 col-sm-10'>
                {!! Form::text($dtlogitem->key, $dtlogitem->value, ['class' => 'form-control', 'readonly']) !!}
            </div>
        </div>
    @endforeach

    @if ($dtlog->dtlogcomments->count())
        <p class="bannerTitle">评论</p>

        @foreach ($dtlog->dtlogcomments as $dtlogcomment)

            <div class="form-group">
                {!! Form::label('content', '评论内容:', ['class' => 'col-xs-4 col-sm-2 control-label']) !!}
                <div class='col-sm-10 col-xs-8'>
                    {!! Form::text('content', $dtlogcomment->content, ['class' => 'form-control', 'readonly']) !!}
                </div>
            </div>

            <div class="form-group">
                {!! Form::label('create_time', '评论时间:', ['class' => 'col-xs-4 col-sm-2 control-label']) !!}
                <div class='col-sm-10 col-xs-8'>
                    {!! Form::text('create_time', $dtlogcomment->create_time, ['class' => 'form-control', 'readonly']) !!}
                </div>
            </div>

            <div class="form-group">
                {!! Form::label('comment_people', '评论人:', ['class' => 'col-xs-4 col-sm-2 control-label']) !!}
                <div class='col-sm-10 col-xs-8'>
                    @if (isset($dtlogcomment->user))
                    {!! Form::text('comment_people', $dtlogcomment->user->name, ['class' => 'form-control', 'readonly']) !!}
                        @else
                        {!! Form::text('comment_people', '', ['class' => 'form-control', 'readonly']) !!}
                        @endif
                </div>
            </div>
            <hr>
        @endforeach
    @endif
    {!! Form::close() !!}



@endsection