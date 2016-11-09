@extends('approval.mindexmyapproval_nav')

@section('title', '待我审批的')

@section('mindexmyapproval_main')
   
{{--    <div class="panel-body">
        <a href="{{ URL::to('approval/items/create') }}" class="btn btn-sm btn-success">新建</a>
        <form class="pull-right" action="/approval/items/search" method="post">
            {!! csrf_field() !!}
            <div class="pull-right">
                <button type="submit" class="btn btn-default btn-sm">查找</button>
            </div>
            <div class="pull-right input-group-sm">
                <input type="text" class="form-control" name="key" placeholder="Search">    
            </div>
        </form>

    </div> --}}

    @include('approval._list2',
        [
            'href_pre' => '/approval/reimbursementapprovals/', 'href_suffix' => '/mcreate',
            'href_pre_paymentrequest' => '/approval/paymentrequestapprovals/'
        ])

    {!! $paymentrequests->links() !!}

{{--
    @if ($reimbursements->count())
        @foreach($reimbursements as $reimbursement)
        <div class="list-group">
            <a href="{{ url('/approval/reimbursementapprovals/' . $reimbursement->id . '/mcreate') }}" class="list-group-item">
                <span class="badge">{{ $reimbursement->created_at }}</span>
                {{ $reimbursement->applicant->name }}的报销
            </a>
        </div>     
        @endforeach
    @endif
--}}


@endsection
