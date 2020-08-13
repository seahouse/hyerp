@extends('navbarerp')

@section('main')
    {!! Form::model($purchaseorder, ['class' => 'form-horizontal']) !!}
    @include('purchase.purchaseorders._form_hx',
        [
            'submitButtonText' => '提交',
            'supplier_name' => null,
            'pohead_number' => null,
            'datepay' => null,
            'date' => null,
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
    {!! Form::close() !!}
@endsection
