@extends('navbarerp')

@section('main')
    {!! Form::model($dtlog, ['class' => 'form-horizontal']) !!}
    @include('dingtalk.dtlogs._form',
        [
            'submitButtonText' => '提交',
            'supplier_name' => null,
            'pohead_number' => null,
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

    @foreach($dtlog->dtlogitems as $dtlogitem)
        <div class="form-group">
            {!! Form::label($dtlogitem->key, $dtlogitem->key, ['class' => 'col-xs-4 col-sm-2 control-label']) !!}
            <div class='col-xs-8 col-sm-10'>
                {!! Form::text($dtlogitem->key, $dtlogitem->value, ['class' => 'form-control', 'readonly']) !!}
            </div>
        </div>
    @endforeach
    {!! Form::close() !!}



@endsection