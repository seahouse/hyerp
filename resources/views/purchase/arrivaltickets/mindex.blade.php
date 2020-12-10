@extends('app')

@section('title', '到票记录')

@section('main')
<h1>到票记录</h1>
<hr />

@if (count($tickets))
<table class="table table-striped table-hover table-condensed">
    <thead>
        <tr>
            <th>发票号码</th>
            <th>到票金额</th>
            <th>到票日期</th>
            <th>经办人</th>
            <th>备注</th>
        </tr>
    </thead>
    <tbody>
        @foreach($tickets as $ticket)
        <tr>
            <td>
                {{ $ticket->no }}
            </td>
            <td>
                {{ $ticket->amount }}
            </td>
            <td>
                {{ \Carbon\Carbon::parse($ticket->date)->toDateString() }}
            </td>
            <td>
                {{ $ticket->recipient }}
            </td>
            <td>
                {{ $ticket->remark }}
            </td>
        </tr>
        @endforeach
    </tbody>
</table>
@else
<div class="alert alert-warning alert-block">
    <i class="fa fa-warning"></i>
    {{'无记录', [], 'layouts'}}
</div>
@endif

@endsection