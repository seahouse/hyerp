@extends('app')
@section('title', '工资条')

@if (Auth::user()->id == $salarysheet->user_id)
    @include('system.salarysheets._show')
@else
    您无权查看他人的工资单。
@endif
