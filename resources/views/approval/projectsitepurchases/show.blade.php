@extends('navbarerp')

@can('approval_projectsitepurchase_view')
@include('approval.projectsitepurchases._show')
@else
    无权限。
@endcan
