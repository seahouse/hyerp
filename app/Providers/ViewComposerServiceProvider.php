<?php

namespace App\Providers;

use App\Models\System\Userold;
use Illuminate\Support\ServiceProvider;
use DB, Auth;

class ViewComposerServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        // itemList
        view()->composer(array('product.bomitems.createitem', 'product.bomitems.edit', 'sales.soitems.create', 'sales.soitems.edit'), function($view) {
            $view->with('itemList', \App\Models\Product\Item::orderby('id', 'asc')->lists('item_number', 'id'));
        });
        
        // itemclassList
        view()->composer(array('items.create', 'items.edit', 'product.items.create', 'product.items.edit'), function($view) {
            $view->with('itemclassList', \App\Models\Product\Itemclass::orderby('id', 'asc')->lists('name', 'id'));
        });
        
        // itemtypeList
        view()->composer(array('items.create', 'items.edit', 'product.items.create', 'product.items.edit'), function($view) {
            $view->with('itemtypeList', \App\Models\Product\Itemtype::orderby('id', 'asc')->lists('name', 'id'));
        });
        
        // provinceList
        view()->composer(array('addr.citys.create', 'addr.citys.edit', 'addr.addrs.create', 'addr.addrs.edit'), function($view) {
            $view->with('provinceList', \App\Models\Crm\Province::orderby('id', 'asc')->lists('name', 'id'));
        });
        
        // cityList
        view()->composer(array('addr.addrs.create', 'addr.addrs.edit'), function($view) {
            $view->with('cityList', \App\Models\Crm\City::orderby('id', 'asc')->lists('name', 'id'));
        });
        
        // addrList
        view()->composer(array('crm.contacts.create', 'crm.contacts.edit', 'inventory.warehouses.create', 'inventory.warehouses.edit',
            'purchase.vendinfos.create', 'purchase.vendinfos.edit'), function($view) {
            $view->with('addrList', \App\Models\Crm\Addr::orderby('id', 'asc')->lists('line1', 'id'));
        });
        
        // contactList
        view()->composer(array('sales.custinfos.create', 'sales.custinfos.edit', 'inventory.warehouses.create', 'inventory.warehouses.edit',
            'purchase.vendinfos.create', 'purchase.vendinfos.edit', 'purchase.purchaseorders.create', 'purchase.purchaseorders.edit',
            'purchase.purchaseorders.create_hx', 'purchase.purchaseorders.edit_hx'), function($view) {
            $view->with('contactList', \App\Models\Crm\Contact::orderby('id', 'asc')->lists('name', 'id'));
        });      
        
        
        // custinfoList
        view()->composer(array('sales.salesorders.create', 'sales.salesorders.edit', 'approval.reimbursements.mcreate', 'approval.reimbursements.medit',
                'approval.reimbursementapprovals.mcreate', 'approval.reimbursements.mshow'), function($view) {
            $view->with('custinfoList', \App\Models\Sales\Custinfo::orderby('id', 'asc')->lists('name', 'id'));
        });
        
        // salesrepList
        view()->composer(array('sales.salesorders.create', 'sales.salesorders.edit'), function($view) {
            $view->with('salesrepList', \App\Models\Sales\Salesrep::orderby('id', 'asc')->lists('name', 'id'));
        });
        
        // termList
        view()->composer(array('purchase.vendinfos.create', 'purchase.vendinfos.edit', 'purchase.purchaseorders.create', 'purchase.purchaseorders.edit',
            'purchase.purchaseorders.create_hx', 'purchase.purchaseorders.edit_hx'), function($view) {
            $view->with('termList', \App\Models\Sales\Term::orderby('id', 'asc')->lists('code', 'id'));
        });
        
        // vendtypeList
        view()->composer(array('purchase.vendinfos.create', 'purchase.vendinfos.edit'), function($view) {
            $view->with('vendtypeList', \App\Models\Purchase\Vendtype::orderby('id', 'asc')->lists('code', 'id'));
        });
        
        // vendinfoList
        view()->composer(array('purchase.purchaseorders.create', 'purchase.purchaseorders.edit',
            'purchase.purchaseorders.create_hx', 'purchase.purchaseorders.edit_hx'), function($view) {
            $view->with('vendinfoList', \App\Models\Purchase\Vendinfo::orderby('id', 'asc')->lists('number', 'id'));
        });
        
        // soheadList
        view()->composer(array('purchase.purchaseorders.create', 'purchase.purchaseorders.edit',
            'purchase.purchaseorders.create_hx', 'purchase.purchaseorders.edit_hx'), function($view) {
            $view->with('soheadList', \App\Models\Sales\Salesorder::orderby('id', 'asc')->lists('number', 'id'));
        });
        
        // itemsiteList
        view()->composer(array('purchase.poitems.create', 'purchase.poitems.edit'), function($view) {
//             $items = \App\Inventory\Itemsite::orderby('itemsites.id', 'asc')->leftJoin('items', 'itemsites.item_id', '=', 'items.id')->select('item_number', 'itemsites.id')->get();
            $view->with('itemsiteList', \App\Models\Inventory\Itemsite::orderby('itemsites.id', 'asc')->leftJoin('items', 'itemsites.item_id', '=', 'items.id')->select('item_number', 'itemsites.id')->lists('item_number', 'id'));
//             $view->with('itemsiteList', DB::table('itemsites')->leftJoin('items', 'itemsites.item_id', '=', 'items.id')->select('item_number', 'itemsites.id')->lists('item_number', 'itemsites.id'));
//             $view->with('itemsiteList', \App\Inventory\Itemsite::orderby('itemsites.id', 'asc')->lists('item_id', 'id'));
        });
        
        // roleList
        view()->composer(array('system.users.editrole'), function($view) {
            $view->with('roleList', \App\Role::orderby('id', 'asc')->lists('name', 'id'));
        });
        
        // charIList: item char list
        view()->composer(array('items.create', 'items.edit', 'product.items.create', 'product.items.edit'), function($view) {
            $view->with('charIList', \App\Models\Product\Characteristic::orderby('id', 'asc')->where('bitems', true)->lists('name', 'id'));
        });

        // reimbursementtypeList
        view()->composer(array('approval.reimbursements.mcreate', 'approval.reimbursements.medit', 'approval.reimbursements.show', 'approval.reimbursements.mshow',
                'approval.reimbursementapprovals.mcreate'), function($view) {
            $view->with('reimbursementtypeList', \App\Models\Approval\Reimbursementtype::orderby('id', 'asc')->lists('name', 'id'));
        });

        // approvaltypeList
        view()->composer(array('approval.approversettings.create', 'approval.approversettings.edit'), function($view) {
            $view->with('approvaltypeList', \App\Models\Approval\Approvaltype::orderby('id', 'asc')->lists('name', 'id'));
        });

        // deptList
        view()->composer(array('system.employees.create', 'system.employees.edit',
            'system.users.create', 'system.users.edit', 'approval.approversettings.create', 'approval.approversettings.edit'), function($view) {
            $view->with('deptList', \App\Models\System\Dept::orderby('id', 'asc')->lists('name', 'id'));
        });
        
        // imageList
        view()->composer(array('system.employees.create', 'system.employees.edit'), function($view) {
            $view->with('imageList', \App\Models\System\Image::orderby('id', 'asc')->lists('name', 'id'));
        });

        // userList
        view()->composer(array('approval.approversettings.create', 'approval.approversettings.edit', 
            'teaching.teachingadministrator.create', 'teaching.teachingadministrator.edit', 'purchase.payments.create_hxold',
            'changeuser'), function($view) {
            $view->with('userList', \App\Models\System\User::where('email', '<>', 'admin@admin.com')->orderby('name', 'asc')->lists('name', 'id'));
        });

        // teachingpointList
        view()->composer(array('teaching.teachingadministrator.create', 'teaching.teachingadministrator.edit',
            'teaching.teachingstudentimage.create', 'teaching.teachingstudentimage.edit'), function($view) {
            $view->with('teachingpointList', \App\Models\Teaching\Teachingpoint::orderby('id', 'asc')->lists('name', 'id'));
        });

        // payerList_hxold, 管理层 和 采购部
        view()->composer(array('purchase.payments.create_hxold', 'purchase.payments.edit_hxold'), function($view) {
            $view->with('payerList_hxold', \App\Models\System\Employee_hxold::orderby('id', 'asc')->where('dept_id', 10)->orWhere('dept_id', 11)->lists('name', 'id'));
        });

        // userList_hxold
        view()->composer(array('system.users.edituserold'), function($view) {
            $view->with('userList_hxold', \App\Models\System\Employee_hxold::where('status', '<>', -1)->orderby('name', 'asc')->lists('name', 'id'));
        });

        // soheadList_hxold
        view()->composer(array('system.report.statisticsindex', 'approval.reports2.issuedrawingpurchasedetail',
            'purchase.purchaseorders.index_hx', 'dingtalk.dtlogs.index'), function($view) {
            $view->with('soheadList_hxold', \App\Models\Sales\Salesorder_hxold::where('status', 0)->orderby('id', 'asc')->lists('projectjc', 'id'));
        });

         // poheadOrderDateyearList_hxold
        view()->composer(array('system.report.statisticsindex'), function($view) {
            $view->with('poheadOrderDateyearList_hxold', \App\Models\Sales\Salesorder_hxold::select(DB::raw('datepart(year, orderdate) as dateyear'))->orderby('dateyear', 'asc')->lists('dateyear', 'dateyear'));
//            $view->with('poheadOrderDateyearList_hxold', DB::connection('sqlsrv')->select(DB::raw('select distinct datepart(year, orderdate) from vorder'))->lists('projectjc', 'id'));
        });

        // soheadOrderDateyearList_hxold
        view()->composer(array('system.report.statisticsindex'), function($view) {
            $view->with('soheadOrderDateyearList_hxold', \App\Models\Sales\Salesorder_hxold::select(DB::raw('datepart(year, orderdate) as dateyear'))->orderby('dateyear', 'asc')->lists('dateyear', 'dateyear'));
        });

        // approvers_paymentrequest
        view()->composer(array('approval.paymentrequests.index'), function($view) {
            $ids = \App\Models\Approval\Paymentrequestapproval::select('approver_id')->distinct()->get();
            $view->with('approvers_paymentrequest', \App\Models\System\User::whereIn('id', $ids)->lists('name', 'id'));
        });

        // unitList_hxold
        view()->composer(array('approval.mcitempurchases.mcreate', 'approval.projectsitepurchases.mcreate'), function($view) {
            $view->with('unitList_hxold', \App\Models\Product\Unit_hxold::orderby('id', 'asc')->lists('name', 'id'));
        });

        // salesmanagerList_hxold
        view()->composer(array('my.bonus.index_byorder', 'my.bonus.index_bonusbysalesmanager', 'system.report.statisticsindex'), function($view) {
            $view->with('salesmanagerList_hxold', \App\Models\Sales\Salesorder_hxold::orderby('id', 'asc')->lists('salesmanager', 'salesmanager'));
        });

        // salesmanagerList2
        view()->composer(array('system.report.statisticsindex'), function($view) {
            $view->with('salesmanagerList2', \App\Models\Sales\Salesorder_hxold::orderby('id', 'asc')->lists('salesmanager', 'salesmanager_id'));
        });

        // projectList
        view()->composer(array('system.report.statisticsindex', 'approval.reports2.issuedrawingpurchasedetail', 'dingtalk.dtlogs.index'), function($view) {
            $view->with('projectList', \App\Models\Sales\Project_hxold::orderby('id', 'asc')->lists('name', 'id'));
//            $view->with('poheadOrderDateyearList_hxold', DB::connection('sqlsrv')->select(DB::raw('select distinct datepart(year, orderdate) from vorder'))->lists('projectjc', 'id'));
        });

        // myprojectListByProjectengineer
        view()->composer(array('system.report.statisticsindex'), function($view) {
            $projectengineer_id = 0;
            $user = Auth::user();
            if ($user)
            {
                $userold = Userold::where('user_id', $user->id)->first();
                if ($userold)
                    $projectengineer_id = $userold->user_hxold_id;
            }
            $view->with('myprojectListByProjectengineer', \App\Models\Sales\Salesorder_hxold::where('id', '<>', 7550)
                ->where(function ($query) use ($projectengineer_id) {
                    // SongJH special handler, can view all order except 7550
                    // and WuHL too.
                    if ($projectengineer_id <> 128 and $projectengineer_id <> 8)
                        $query->where('projectengineer_id', $projectengineer_id);
                })
                ->orderby('id', 'asc')->lists('projectjc', 'id'));
//            $view->with('poheadOrderDateyearList_hxold', DB::connection('sqlsrv')->select(DB::raw('select distinct datepart(year, orderdate) from vorder'))->lists('projectjc', 'id'));
        });

        // approvaltypes
        view()->composer(array('approval.synchronize.index'), function($view) {
            $approvaltypes = config('custom.dingtalk.approval_type');
            $view->with('approvaltypes', $approvaltypes);
//            $view->with('poheadOrderDateyearList_hxold', DB::connection('sqlsrv')->select(DB::raw('select distinct datepart(year, orderdate) from vorder'))->lists('projectjc', 'id'));
        });

        // dtlog_creatornames
        view()->composer(array('dingtalk.dtlogs.index'), function($view) {
            $view->with('dtlog_creatornames', \App\Models\Dingtalk\Dtlog::select('creator_name')->distinct()->lists('creator_name', 'creator_name'));
        });

        // dtlog_templatenames
        view()->composer(array('dingtalk.dtlogs.index'), function($view) {
            $dtlog_templatenames = config('custom.dingtalk.dtlogs.template_names');
            $view->with('dtlog_templatenames', $dtlog_templatenames);
//            $view->with('poheadOrderDateyearList_hxold', DB::connection('sqlsrv')->select(DB::raw('select distinct datepart(year, orderdate) from vorder'))->lists('projectjc', 'id'));
        });
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
