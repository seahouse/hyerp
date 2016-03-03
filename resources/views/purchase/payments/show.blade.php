@extends('navbarerp')

@section('main')
    <h1>{{ $item->item_number }}</h1>
    <p>
        Name: {{ $item->item_name }}
    </p>
    <p>
        Description: {{ $item->item_descrip }}
    </p>
    <p>
        Item class: {{ $itemclass->name }}
    </p>
    <p>
        Item Type: {{ $itemtype->name }}
    </p>
    <p>
        Marke Price: {{ $item->marketprice }}
    </p>
@stop
