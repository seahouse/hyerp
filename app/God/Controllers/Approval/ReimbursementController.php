<?php

namespace App\God\Controllers\Approval;

use Illuminate\Http\Request;
use DB, Auth;

class ReimbursementController extends \App\God\Controllers\GodController
{
    public function __construct()
    {
        $this->table = 'reimbursements';
        $this->viewTitle = trans('approval.reimbursement.title');
        $this->rowStatus = [
            'id' => 'status',
            'values' => [
                '0' => 'warning',
                '1' => 'info',
                '2' => 'info',
                '10' => 'success',
                '-1' => 'danger',
                '-2' => 'danger',
                '-10' => 'danger',
            ],
        ];
        $this->fields_all = [
            'id' => [
                'show' => trans('approval.reimbursement.id'),
            ],
            'reimbursementtype_id' => [
                'show' => trans('approval.reimbursement.reimbursementtype_id'),
                'foreign_values' => DB::table('reimbursementtypes')->lists('name', 'id'),
            ],
            'date' => [
                'show' => trans('approval.reimbursement.date'),
            ],
            'number' => [
                'show' => trans('approval.reimbursement.number'),
                'search' => "number like '%%%s%%'",
            ],
            'amount' => [
                'show' => trans('approval.reimbursement.amount'),
            ],
            'customer_id' => [
                'show' => trans('approval.reimbursement.customer_id'),
            ],
            'contacts' => [
                'show' => trans('approval.reimbursement.contacts'),
            ],
            'contactspost' => [
                'show' => trans('approval.reimbursement.contactspost'),
            ],
            'order_id' => [
                'show' => trans('approval.reimbursement.order_id'),
            ],
            'status' => [
                'show' => trans('approval.reimbursement.status'),
                'foreign_values' => [
                    '0' => trans('approval.reimbursement.status_initial'),
                    '1' => trans('approval.reimbursement.status_1st_pass'),
                    '2' => trans('approval.reimbursement.status_2nd_pass'),
                    '10' => trans('approval.reimbursement.status_3rd_pass'),
                    '-1' => trans('approval.reimbursement.status_1st_fail'),
                    '-2' => trans('approval.reimbursement.status_2st_fail'),
                    '-10'=> trans('approval.reimbursement.status_3st_fail'),
                ],
                'search' => "status like '%%%s%%'",
            ],
            'statusdescrip' => [
                'show' => trans('approval.reimbursement.statusdescrip'),
            ],
            'descrip' => [
                'show' => trans('approval.reimbursement.descrip'),
            ],
            'datego' => [
                'show' => trans('approval.reimbursement.datego'),
            ],
            'dateback' => [
                'show' => trans('approval.reimbursement.dateback'),
            ],
            'mealamount' => [
                'show' => trans('approval.reimbursement.mealamount'),
            ],
            'ticketamount' => [
                'show' => trans('approval.reimbursement.ticketamount'),
            ],
            'stayamount' => [
                'show' => trans('approval.reimbursement.stayamount'),
            ],
            'otheramount' => [
                'show' => trans('approval.reimbursement.otheramount'),
            ],
            'approvaler1_id' => [
                'show' => trans('approval.reimbursement.approvaler1_name'),
                'foreign_values' => DB::table('users')->lists('name', 'id'),
                'search' => "users.id from users where users.name like '%%%s%%'",
            ],
            'approvaldate1' => [
                'show' => trans('approval.reimbursement.approvaldate1'),
            ],
            'approvaler2_id' => [
                'show' => trans('approval.reimbursement.approvaler2_name'),
                'foreign_values' => DB::table('users')->lists('name', 'id'),
                'search' => "users.id from users where users.name like '%%%s%%'",
            ],
            'approvaldate2' => [
                'show' => trans('approval.reimbursement.approvaldate2'),
            ],
            'approvaler3_id' => [
                'show' => trans('approval.reimbursement.approvaler3_name'),
                'foreign_values' => DB::table('users')->lists('name', 'id'),
                'search' => "users.id from users where users.name like '%%%s%%'",
            ],
            'approvaldate3' => [
                'show' => trans('approval.reimbursement.approvaldate3'),
            ],
            'applicant_id' => [
                'show' => trans('approval.reimbursement.applicant_name'),
                'foreign_values' => DB::table('users')->lists('name', 'id'),
                'search' => "users.id from users where users.name like '%%%s%%'",
            ],
            'created_at' => [
                'show' => trans('approval.reimbursement.created_at'),
            ],
            'updated_at' => [
                'show' => trans('approval.reimbursement.updated_at'),
            ],
        ];
        $this->fields_index = ['reimbursementtype_id', 'date', 'number', 'amount', 'customer_id', 'order_id', 'status', 'applicant_id'];
        $this->fields_show  = ['id', 'reimbursementtype_id', 'date', 'number', 'amount', 'customer_id', 'contacts', 'contactspost', 'order_id', 'status', 'statusdescrip', 'descrip', 'datego', 'dateback', 'mealamount', 'ticketamount', 'stayamount', 'otheramount', 'approvaler1_id', 'approvaldate1', 'approvaler2_id', 'approvaldate2', 'approvaler3_id', 'approvaldate3', 'applicant_id', 'created_at', 'updated_at'];
        $this->fields_create= ['reimbursementtype_id', 'number', 'amount', 'customer_id', 'contacts', 'contactspost', 'order_id', 'descrip', 'datego', 'dateback', 'mealamount', 'ticketamount', 'stayamount', 'otheramount'];
        $this->fields_edit  = $this->fields_create;
        parent::__construct();
    }

    public function isAllow($action, $id = null)
    {
        $isAllow = parent::isAllow($action, $id);

        $isApprover = function($id) {
            $current_user = Auth::user()->id;
            $status = DB::table('reimbursements')->where('id', '=', $id)->value('status');
            $approvaler1 = DB::table('reimbursements')->where('id', '=', $id)->value('approvaler1_id');
            $approvaler2 = DB::table('reimbursements')->where('id', '=', $id)->value('approvaler2_id');
            $approvaler3 = DB::table('reimbursements')->where('id', '=', $id)->value('approvaler3_id');
            if ( ($status == 0 && $current_user == $approvaler1) ||
                 ($status == 1 && $current_user == $approvaler2) ||
                 ($status == 2 && $current_user == $approvaler3) ) {
                return true;
            }
            else {
                return false;
            }
        };

        if ($id != null) {
            switch ($action) {
            case parent::AUTH_CREATE:
                break;
            case parent::AUTH_RETRIEVE:
                break;
            case parent::AUTH_UPDATE:
            case parent::AUTH_DELETE:
                if (!$isAllow) break;
                $isSuperAdmin= Auth::user()->isSuperAdmin();
                $isCreatedByMe = function($id) {
                    $current_user = Auth::user()->id;
                    $created_user = DB::table($this->table)->where('id', '=', $id)->value('applicant_id');
                    return $created_user == $current_user;
                };
                if (!$isSuperAdmin && !$isCreatedByMe($id)) {
                    $isAllow = false;
                }
                if ($isApprover($id) && $action == parent::AUTH_UPDATE) {
                    $isAllow = true;
                }
                break;
            case parent::AUTH_APPROVE:
                $isAllow = $isApprover($id);
                break;
            default:
                break;
            }
        }

        return $isAllow;
    }

    public function store(Request $request)
    {
        $request->request->set('date', date('Y-m-d'));
        $request->request->set('status', '0');
        $request->request->set('statusdescrip', urldecode(http_build_query($this->fields_all['status']['foreign_values'])));
        $request->request->set('approvaler1_id', DB::table('users')->where('name', '=', 'weimaomin')->value('id'));
        $request->request->set('approvaler2_id', DB::table('users')->where('name', '=', 'liangyi')->value('id'));
        $request->request->set('approvaler3_id', DB::table('users')->where('name', '=', 'chenxiaomin')->value('id'));
        $request->request->set('approvaldate1', date('Y-m-d'));
        $request->request->set('approvaldate2', date('Y-m-d'));
        $request->request->set('approvaldate3', date('Y-m-d'));
        $request->request->set('applicant_id', Auth::user()->id);
        return parent::store($request);
    }

    public function approve(Request $request, $id)
    {
        $response = self::VIEW_PREFIX.__FUNCTION__;

        try {
            DB::beginTransaction();

            $result = false;
            if (!$request->has('result')) {
                throw new \Exception("No approve result!");
            }

            $result = $request->get('result');
            $status = DB::table('reimbursements')->where('id', '=', $id)->value('status');
            if (++$status > 2) $status = 10;
            if (!$result) $status *= -1;

            $values['status'] = $status;
            if (Auth::user()->id == DB::table('reimbursements')->where('id', '=', $id)->value('approvaler1_id')) {
                $values['approvaldate1'] = date('Y-m-d');
            }
            else if (Auth::user()->id == DB::table('reimbursements')->where('id', '=', $id)->value('approvaler2_id')) {
                $values['approvaldate2'] = date('Y-m-d');
            }
            else if (Auth::user()->id == DB::table('reimbursements')->where('id', '=', $id)->value('approvaler3_id')) {
                $values['approvaldate3'] = date('Y-m-d');
            }
            if (array_key_exists('updated_at', $this->fields_all)) {
                $values['updated_at'] = date('Y-m-d H:i:s');
            }
            DB::table($this->table)->where('id', '=', $id)->update($values);

            $response = redirect()->action($this->controller.'@index')
                        ->with('god.success', trans('god.update_success'));
            DB::commit();
        }
        catch (\Exception $e) {
            DB::rollBack();
            $response = redirect()->back()
                        ->with('god.failure', 'Exception: '.$e->getMessage().', See '.$e->getFile().':'.$e->getLine())
                        ->withInput();
        }
        finally {
            ;
        }
        return $response;
    }
}
