<?php

namespace App\Http\Controllers\Approval;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Models\Approval\Paymentrequest;
use DB;

class ApprovalreportsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function paymentrequest()
    {
        //
        // $request = request();
        // if ($request->has('key'))
        //     $paymentrequests = $this->search2($request->input('key'));
        // else
        //     $paymentrequests = Paymentrequest::latest('created_at')->paginate(10);

        // if ($request->has('key'))
        // {
        //     $key = $request->input('key');
        //     return view('approval.paymentrequests.index', compact('paymentrequests', 'key'));
        // }
        // else
        //     return view('approval.paymentrequests.index', compact('paymentrequests'));

// select extract(month from created_at) as m,sum (amount)  from  paymentrequests 
// -- where  to_timestamp(start_time_of_date::bigint)  between  '2010-01-01'   and    '2010-12-12'   
// group by m

// select to_char(created_at, 'MM') as m,sum (amount)  from  paymentrequests 
// -- where  to_timestamp(start_time_of_date::bigint)  between  '2010-01-01'   and    '2010-12-12'   
// group by m
// order by m

        // pgsql
//        $paymentrequests = DB::table('paymentrequests')
//        	->select(DB::raw('to_char(created_at, \'MM\') as m, sum (amount)'))
//        	->groupBy('m')
//        	->orderBy('m')
//        	->get();

        // sqlsrv
//        $paymentrequests = DB::connection('sqlsrv2')->select(DB::raw('select month(created_at) as m, sum (amount) as sum from paymentrequests group by month(created_at) order by month(created_at) asc'));
//            ->groupByRaw('month(created_at)')
//            ->orderBy('month(created_at)')
//            ->get();
//        $paymentrequests = DB::table('paymentrequests')
//            ->select(DB::raw('month(created_at) as m, sum (amount) from paymentrequests group by month(created_at) order by month(created_at) asc'))
////            ->groupByRaw('month(created_at)')
////            ->orderBy('month(created_at)')
//            ->get();

        // dd($paymentrequests);
        // dd(serialize(array_pluck($paymentrequests, 'm')));
        	// dd(implode(',',array_pluck($paymentrequests, 'm')));

        // dd(array_get(array_pluck($paymentrequests, 'm'), ''));

// select users.name,sum (paymentrequests.amount)  from  paymentrequests 
// left join users on users.id=paymentrequests.applicant_id
// -- where  to_timestamp(start_time_of_date::bigint)  between  '2010-01-01'   and    '2010-12-12'   
// group by users.name
        $paymentrequests_user = DB::table('paymentrequests')
        	->leftJoin('users', 'users.id', '=', 'paymentrequests.applicant_id')
        	->select(DB::raw('users.name, sum(paymentrequests.amount)'))
        	->groupBy('users.name')
        	->get();
        	// dd($paymentrequests_user);
        	// dd("'" . implode("','",array_pluck($paymentrequests_user, 'name')) . "'");

        return view('approval.reports.paymentrequest', compact('paymentrequests', 'paymentrequests_user'));
    }
}
