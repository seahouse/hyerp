@extends('approval.mindexmyapproval_nav')

@section('title', '待我审批的')

@section('mindexmyapproval_main')
   


    @include('approval._list',
        [
            'href_pre' => '/approval/reimbursementapprovals/', 'href_suffix' => '/mcreate',
            'href_pre_paymentrequest' => '/approval/paymentrequestapprovals/'
        ])


    {!! $paymentrequests->links() !!}
{{--
    @if (isset($key))
        {!! $paymentrequests->setPath('/approval/mindexmyapproval')->appends(['key' => $key])->links() !!}
    @else
        {!! $paymentrequests->setPath('/approval/mindexmyapproval')->links() !!}
    @endif
--}}


@endsection
