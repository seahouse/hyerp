@extends('app')
@section('title', '奖金条')

@if (Auth::user()->id == $annualbonussheet->user_id)
    @include('system.annualbonussheets._show')
@else
    您无权查看他人的奖金条。
@endif
