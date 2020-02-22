@extends('navbarerp')

@section('main')
    {!! Form::model($biddinginformation, ['class' => 'form-horizontal']) !!}
    @include('basic.biddinginformations._form',
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

    @foreach($biddinginformation->biddinginformationitems as $biddinginformationitem)
        <div class="form-group">
            {!! Form::label($biddinginformationitem->key, $biddinginformationitem->key, ['class' => 'col-xs-4 col-sm-2 control-label']) !!}
            <div class='col-xs-4 col-sm-6'>
                {!! Form::text($biddinginformationitem->key, $biddinginformationitem->value, ['class' => 'form-control', 'readonly']) !!}
            </div>
            <div class='col-xs-4 col-sm-4'>
                @if (strlen($biddinginformationitem->remark) > 0)
                    {!! Form::textarea($biddinginformationitem->key, $biddinginformationitem->remark, ['class' => 'form-control', 'readonly', 'rows' => 3]) !!}
                @else
                    {!! Form::text($biddinginformationitem->key, $biddinginformationitem->remark, ['class' => 'form-control', 'readonly']) !!}
                @endif
            </div>
        </div>
    @endforeach
    {!! Form::close() !!}

@endsection
