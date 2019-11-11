<?php

/*
|--------------------------------------------------------------------------
| Routes File
|--------------------------------------------------------------------------
|
| Here is where you will register all of the routes in an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

// Route::get('/', function () {
//     // return view('welcome');
//     return view('navbarerp');
// });

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| This route group applies the "web" middleware group to every route
| it contains. The "web" middleware group is defined in your HTTP
| kernel and includes session state, CSRF protection, and more.
|
*/

Route::get('app', function() {
    return view('app');
});

Route::get('app2', function() {
    return view('app2');
});


Route::post('dingtalk/receive', 'DingTalkController@receive');
Route::get('dingtalk/receive', 'DingTalkController@receive');
Route::post('dingtalk/receive2', 'DingTalkController@receive2');
Route::any('dingtalk/receivebpms', 'DingTalkController@receivebpms');
// Route::post('dingtalk/receive', function() {
//     return '';
// });

Route::post('faceplusplus/detect', 'FaceplusplusController@detect');
Route::post('faceplusplus/compare', 'FaceplusplusController@compare');
Route::post('faceplusplus/search', 'FaceplusplusController@search');
Route::post('faceplusplus/faceset_create', 'FaceplusplusController@faceset_create');

Route::post('facecore/urlfacedetect', 'FacecoreController@urlfacedetect');

Route::post('cloudwalk/face_tool_detect', 'CloudwalkController@face_tool_detect');

// erp, send dingtalk message
// because not need cref, so not in middle web
Route::any('dingtalk/message/send_erp/{strJson}',  'DingTalkController@send_erp');
Route::any('sales/salesorder/message/afteredit/{strJson}',  'Sales\SalesOrdersController@sendmessage_afteredit_hxold');

Route::post('approval/mcitempurchase/uploadparseexcel', 'Approval\McitempurchaseController@uploadparseexcel');

Route::group(['middleware' => ['web']], function () {
    // Route::get('mddauth', function() { return view('mddauth'); });
    Route::get('mddauth/{appname?}/{url?}', 'DingTalkController@mddauth');
    Route::get('mddauth2/{agentid?}/{url?}', 'DingTalkController@mddauth2');
//    Route::get('test', 'DingTalkController@test');
    Route::get('ddauth/{appname?}/{url?}', 'DingTalkController@ddauth');

	Route::get('dingtalk/getuserinfo/{code}', 'DingTalkController@getuserinfo');
    Route::get('dingtalk/getuserinfoByScancode/{code}', 'DingTalkController@getuserinfoByScancode');
    Route::get('dingtalk/getuserinfoByScancode_hxold/{code}', 'DingTalkController@getuserinfoByScancode_hxold');
    Route::get('dingtalk/getconfig', 'DingTalkController@getconfig');
    Route::post('dingtalk/register_call_back', 'DingTalkController@register_call_back');
    Route::get('dingtalk/delete_call_back', 'DingTalkController@delete_call_back');
    Route::post('dingtalk/synchronizeusers', 'DingTalkController@synchronizeusers');
    Route::get('dingtalk/cacheflush', 'DingTalkController@cacheflush');

    // chat
    Route::post('dingtalk/chat_create', 'DingTalkController@chat_create');
    Route::post('dingtalk/send_to_conversation', 'DingTalkController@send_to_conversation');

    // google authenticator
    Route::get('dingtalk/googleauthenticator ', 'DingTalkController@googleauthenticator');
    Route::get('google2fa/generatesecretkey', 'Google2FAController@generatesecretkey');
    Route::get('google2fa/test/{secret}', 'Google2FAController@test');
    Route::post('google2fa/login', 'Google2FAController@login');
    Route::get('google2fa/login', function () {
        return view('auth.login_google2fa');
    });

    // approval
    Route::get('dingtalk/router/rest', 'DingTalkController@routerrest');
    Route::get('dingtalk/processinstance/list', 'DingTalkController@processinstance_list');

    // run .bat shell command to run git pull.
    Route::get('gitpullbybat', function() { return view('gitpullbybat'); });

    Route::get('test', 'TestController@test');
    Route::get('testoracle', 'TestController@testoracle');

    // test page
    Route::get('test1', function() {
        return view('approval.paymentrequests.test1');
    });
    Route::get('test2', function() {
        return view('approval.paymentrequests.test2');
    });

    // face plus plus
    Route::get('facepp/demo/msearch', 'FaceplusplusController@msearch');

    // suning
    Route::group(['prefix' => 'suning'], function() {
        Route::get('gateway', 'SuningController@gateway');
        Route::get('verifysign', 'SuningController@verifysign');
    });
});

Route::group(['middleware' => ['web', 'auth']], function () {
    //
    Route::get('/', function() { return view('navbarerp'); });
	Route::get('/home', function() { return view('navbarerp'); });
    Route::get('mapproval', function() { return view('mapproval'); });


    Route::get('api/dropdown', 'Addr\ProvincesController@getIndex');

    Route::get('dingtalk/issuedrawing', 'DingTalkController@issuedrawing');

    Route::get('pdfjs/viewer', function ()
    {
        return view('pdfjs/viewer');
    });
    Route::get('uploads/approval/issuedrawing/{id}/drawingattachments/{filename}', function ($id, $filename) {
        $filename = str_replace("_", ".", $filename);
        return redirect(url("uploads/approval/issuedrawing/$id/drawingattachments/$filename"));
    });
    Route::get('uploads/approval/mcitempurchase/{id}/files/{filename}', function ($id, $filename) {
        $filename = str_replace("_", ".", $filename);
        return redirect(url("uploads/approval/mcitempurchase/$id/files/$filename"));
    });
    Route::get('uploads/approval/{approvaltype}/{id}/files/{filename}', function ($approvaltype, $id, $filename) {
        $filename = str_replace("_", ".", $filename);
        return redirect(url("uploads/approval/$approvaltype/$id/files/$filename"));
    });

    Route::get('changeuser', 'HelperController@changeuser');
    Route::post('changeuser_store', 'HelperController@changeuser_store');
});


Route::group(['prefix' => 'addr', 'namespace' => 'Addr', 'middleware' => ['web', 'auth']], function() {
    Route::resource('addrs', 'AddrsController');
    Route::resource('provinces', 'ProvincesController');
    Route::resource('citys', 'CitysController');
});

Route::group(['prefix' => 'inventory', 'namespace' => 'Inventory', 'middleware' => ['web', 'auth']], function() {
    Route::resource('warehouses', 'WarehousesController');
    Route::get('inventoryAvailability', 'InventoryAvailabilityController@listByItems');
    Route::get('inventoryAvailabilityBySalesorder', 'InventoryAvailabilityController@listBySalesorder');
    Route::group(['prefix' => 'rwrecord/{id}'], function () {
        Route::get('receiptitems', 'RwrecordController@receiptitems');
        Route::get('receiptitems_hx', 'RwrecordController@receiptitems_hx');
    });
    Route::resource('rwrecord', 'RwrecordController');
    Route::get('report', '\App\Http\Controllers\System\ReportController@indexinventory');
});

Route::group(['prefix' => 'product', 'namespace' => 'Product', 'middleware' => ['web', 'auth']], function() {
    Route::resource('itemclasses', 'ItemclassesController');
    Route::post('items/search', 'ItemsController@search');
    Route::group(['prefix' => 'items'], function() {
        Route::get('mmindex', 'ItemsController@mmindex');
        Route::get('mindex', 'ItemsController@mindex');
        Route::get('getitemsbykey/{key}', 'ItemsController@getitemsbykey');
    });
    Route::group(['prefix' => 'items/{id}'], function() {
        Route::get('receiptitems', 'ItemsController@receiptitems');
    });

    // hxold itemp
    Route::get('indexp_hxold', 'ItemsController@indexp_hxold');
    Route::group(['prefix' => 'indexp_hxold'], function() {
        Route::post('search', 'ItemsController@itemp_hxold_search');
        Route::get('{id}/sethxold2', 'ItemsController@sethxold2');
        Route::get('{id}/msethxold2', 'ItemsController@msethxold2');
        Route::post('{id}/sethxold2/{id2}', 'ItemsController@sethxold2update');
        Route::post('resetitempnumber', 'ItemsController@resetitempnumber');
        Route::get('{id}/topdm', 'ItemsController@topdm');
        Route::get('itemstopdm', 'ItemsController@itemstopdm');
        Route::get('bomstopdm', 'ItemsController@bomstopdm');
    });

    Route::resource('items', 'ItemsController');
    Route::resource('boms', 'BomsController');
    Route::get('bomitems/{id}/createitem', 'BomitemsController@createitem');
    Route::resource('bomitems', 'BomitemsController');
    Route::resource('characteristics', 'CharacteristicsController');
    Route::group(['prefix' => 'charasses'], function() {
        Route::post('addrecord', 'CharassesController@addrecord');
        Route::get('removerecord/{id}', 'CharassesController@removerecord');
        Route::get('getCharassesByTargetId/{targetType}/{targetId}', 'CharassesController@getCharassesByTargetId');
        Route::resource('/', 'CharassesController');
    });

    Route::group(['prefix' => 'pdms'], function() {
        Route::get('k3topdm', 'PdmController@k3topdm');
    });
    Route::resource('pdms', 'PdmController');
});

Route::group(['prefix' => 'sales', 'namespace' => 'Sales', 'middleware' => ['web']], function() {
    Route::group(['prefix' => 'salesorders'], function() {
        Route::get('getitemsbykey/{key}/{customerid?}', 'SalesordersController@getitemsbykey');
    });
});

Route::group(['prefix' => 'sales', 'namespace' => 'Sales', 'middleware' => ['web', 'auth']], function() {
    Route::group(['prefix' => 'groups'], function() {
        Route::get('{id}/mstatistics', 'GroupController@mstatistics');
    });
    Route::resource('groups', 'GroupController');
    Route::group(['prefix' => 'projects'], function() {
        Route::get('{id}/mstatistics', 'ProjectController@mstatistics');
    });
    Route::resource('projects', 'ProjectController');
    Route::get('salesorders/{id}/ship', 'SalesordersController@ship');
    Route::post('salesorders/search', 'SalesordersController@search');
    Route::group(['prefix' => 'salesorders'], function() {
        Route::get('mindex', 'SalesordersController@mindex');
//        Route::get('getitemsbykey/{key}/{customerid?}', 'SalesordersController@getitemsbykey');
        Route::get('getitembyid/{id}', 'SalesordersController@getitembyid');
        Route::get('getsohx', 'SalesordersController@getsohx');
        Route::get('{id}/mstatistics', 'SalesordersController@mstatistics');
        Route::get('{id}/setpurchasereminderactive/{active}', 'SalesordersController@setpurchasereminderactive');
    });
    Route::resource('salesorders', 'SalesordersController');
    Route::group(['prefix' => 'salesorderhx'], function() {
        Route::post('search', 'SalesorderhxController@search');
        Route::get('{id}/checktaxrateinput', 'SalesorderhxController@checktaxrateinput');
        Route::get('{id}/dwgbom', 'SalesorderhxController@dwgbom');
        Route::get('dwgbomjson', 'SalesorderhxController@dwgbomjson');
        Route::get('dwgbomjsondetail/{id?}', 'SalesorderhxController@dwgbomjsondetail');
    });
    Route::resource('salesorderhx', 'SalesorderhxController');
    Route::group(['prefix' => 'salesorders/{salesorder}/receiptpayments'], function () {
        Route::get('/', 'ReceiptpaymentsController@index');
        Route::get('create', 'ReceiptpaymentsController@create');
        Route::post('store', 'ReceiptpaymentsController@store');
        Route::delete('destroy/{receiptpayment}', 'ReceiptpaymentsController@destroy');
    });
    Route::resource('salesreps', 'SalesrepsController');
    Route::resource('terms', 'TermsController');
    Route::get('soitems/{headId}/list', 'SoitemsController@listBySoheadId');
    Route::get('soitems/{headId}/create', 'SoitemsController@createBySoheadId');
    Route::resource('soitems', 'SoitemsController');
    Route::group(['prefix' => 'custinfos'], function() {
        Route::get('getitemsbykey/{key}', 'CustinfosController@getitemsbykey');
    });
    Route::resource('custinfos', 'CustinfosController');
    Route::get('report', '\App\Http\Controllers\System\ReportController@indexsales');
    Route::group(['prefix' => 'report2'], function() {
        Route::get('bonusbysalesmanager', '\App\Http\Controllers\My\MyController@bonusbysalesmanager');
        Route::get('bonusbytechdept', '\App\Http\Controllers\My\MyController@bonusbytechdept');
    });
//    Route::any('', 'MyController@bonusbyorder');
    Route::group(['prefix' => '{sohead_id}/bonuspayment'], function () {
//        Route::get('/', 'ReceiptpaymentsController@index');
        Route::get('create', 'BonuspaymentController@create');
        Route::post('store', 'BonuspaymentController@store');
//        Route::delete('destroy/{receiptpayment}', 'ReceiptpaymentsController@destroy');
    });
});

Route::group(['prefix' => 'sales', 'namespace' => 'Sales'], function() {
    Route::group(['prefix' => 'salesorders/receiptpayments'], function () {
        Route::post('storebync', 'ReceiptpaymentsController@storebync');
    });
});

Route::group(['prefix' => 'purchase', 'namespace' => 'Purchase', 'middleware' => ['web', 'auth']], function() {
    Route::group(['prefix' => 'vendinfos'], function() {
        Route::get('getitemsbykey/{key}', 'VendinfosController@getitemsbykey');
    });
    Route::resource('vendinfos', 'VendinfosController');
    Route::resource('vendtypes', 'VendtypesController');
    Route::group(['prefix' => 'vendbank'], function() {
        Route::get('getitemsbyvendid/{vendid}', 'VendbankController@getitemsbyvendid');
    });
    Route::resource('vendbank', 'VendbankController');
    // Route::get('purchaseorders/{id}/detail', 'PurchaseordersController@detail');
    // Route::get('purchaseorders/{id}/receiving', 'PurchaseordersController@receiving');
    // Route::get('purchaseorders/{id}/receiptorders', 'PurchaseordersController@receiptorders');
    Route::group(['prefix' => 'purchaseorders/{id}'], function () {
        Route::get('detail', 'PurchaseordersController@detail');
        Route::get('detail_hxold', 'PurchaseordersController@detail_hxold');
        Route::get('receiving', 'PurchaseordersController@receiving');
        Route::get('receiptorders', 'PurchaseordersController@receiptorders');
        Route::get('poitems', 'PurchaseordersController@poitems');
        Route::get('receiptorders_hx', 'PurchaseordersController@receiptorders_hx');
        Route::get('edit_hx', 'PurchaseordersController@edit_hx');
        Route::patch('update_hx', 'PurchaseordersController@update_hx');
        Route::get('getpoheadtaxrateass_hx', 'PurchaseordersController@getpoheadtaxrateass_hx');
    });
    Route::group(['prefix' => 'purchaseorders/{purchaseorder}/payments'], function () {
        Route::get('/', 'PaymentsController@index');
        Route::get('create', 'PaymentsController@create');
        Route::post('store', 'PaymentsController@store');
        Route::delete('destroy/{payment}', 'PaymentsController@destroy');

        Route::get('create_hxold', 'PaymentsController@create_hxold');
        Route::post('store_hxold', 'PaymentsController@store_hxold');
    });
    Route::group(['prefix' => 'purchaseorders'], function() {
        Route::get('getitemsbyorderkey/{key}/{supplierid?}', 'PurchaseordersController@getitemsbyorderkey');
        Route::get('getitemsbyorderkey_simple/{key}/{supplierid?}', 'PurchaseordersController@getitemsbyorderkey_simple');
        Route::get('index_hx', 'PurchaseordersController@index_hx');
        Route::get('create_hx', 'PurchaseordersController@create_hx');
        Route::post('store_hx', 'PurchaseordersController@store_hx');
        Route::post('search_hx', 'PurchaseordersController@search_hx');
    });
    Route::group(['prefix' => 'poheadtaxrateass'], function() {
        Route::get('destorybyid/{id}', 'PoheadtaxrateassController@destorybyid');       // use get for page opt.
    });
    Route::resource('purchaseorders', 'PurchaseordersController');
    Route::resource('poheadtaxrateass', 'PoheadtaxrateassController');
    Route::get('poitems/{headId}/create', 'PoitemsController@createByPoheadId');
    Route::group(['prefix' => 'poitems/hxold'], function() {
        Route::get('', 'PoitemsController@index_hxold');
    });
    Route::resource('poitems', 'PoitemsController');
    Route::get('report', '\App\Http\Controllers\System\ReportController@indexpurchase');
});

Route::group(['prefix' => 'purchase', 'namespace' => 'Purchase'], function() {
    Route::group(['prefix' => 'purchaseorders/payments'], function () {
        Route::post('storebync', 'PaymentsController@storebync');
    });
});

Route::group(['prefix' => 'purchaseorderc', 'namespace' => 'Purchaseorderc', 'middleware' => ['web', 'auth']], function() {
    Route::group(['prefix' => 'purchaseordercs/{id}'], function () {
        Route::get('detail', 'PurchaseordercController@detail');
        Route::get('detailjson', 'PurchaseordercController@detailjson');
        Route::get('packing', 'PurchaseordercController@packing');
//        Route::get('receiving', 'PurchaseordersController@receiving');
//        Route::get('receiptorders', 'PurchaseordersController@receiptorders');
//        Route::get('poitems', 'PurchaseordersController@poitems');
//        Route::get('receiptorders_hx', 'PurchaseordersController@receiptorders_hx');
//        Route::get('edit_hx', 'PurchaseordersController@edit_hx');
//        Route::patch('update_hx', 'PurchaseordersController@update_hx');
//        Route::get('getpoheadtaxrateass_hx', 'PurchaseordersController@getpoheadtaxrateass_hx');
    });
//    Route::group(['prefix' => 'purchaseorders/{purchaseorder}/payments'], function () {
//        Route::get('/', 'PaymentsController@index');
//        Route::get('create', 'PaymentsController@create');
//        Route::post('store', 'PaymentsController@store');
//        Route::delete('destroy/{payment}', 'PaymentsController@destroy');
//
//        Route::get('create_hxold', 'PaymentsController@create_hxold');
//        Route::post('store_hxold', 'PaymentsController@store_hxold');
//    });
//    Route::group(['prefix' => 'purchaseorders'], function() {
//        Route::get('getitemsbyorderkey/{key}/{supplierid?}', 'PurchaseordersController@getitemsbyorderkey');
//        Route::get('index_hx', 'PurchaseordersController@index_hx');
//        Route::get('create_hx', 'PurchaseordersController@create_hx');
//        Route::post('store_hx', 'PurchaseordersController@store_hx');
//        Route::post('search_hx', 'PurchaseordersController@search_hx');
//    });
//    Route::group(['prefix' => 'poheadtaxrateass'], function() {
//        Route::get('destorybyid/{id}', 'PoheadtaxrateassController@destorybyid');       // use get for page opt.
//    });
    Route::resource('purchaseordercs', 'PurchaseordercController');
    Route::get('poitemcs/{headId}/create', 'PoitemcController@createByPoheadId');
    Route::resource('poitemcs', 'PoitemcController');
    Route::group(['prefix' => 'asns'], function () {
        Route::post('packingstore', 'AsnController@packingstore');
//        Route::get('packing', 'PurchaseordercController@packing');
    });
    Route::group(['prefix' => 'asns/{id}'], function () {
        Route::get('detail', 'AsnController@detail');
        Route::get('labelpreprint', 'AsnController@labelpreprint');
//        Route::get('packing', 'PurchaseordercController@packing');
    });
    Route::resource('asns', 'AsnController');
//    Route::resource('asnitems', 'AsnitemController');
});

Route::group(['prefix' => 'crm', 'namespace' => 'Crm', 'middleware' => ['web', 'auth']], function() {
    Route::resource('contacts', 'ContactsController');
});

Route::group(['prefix' => 'accounting', 'namespace' => 'Accounting', 'middleware' => ['web', 'auth']], function() {
    Route::resource('receivables', 'ReceivablesController');
    Route::resource('payables', 'PayablesController');
});

Route::group(['prefix' => 'approval', 'namespace' => 'Approval', 'middleware' => ['web', 'auth']], function() {
    Route::group(['prefix' => 'reimbursements'], function() {
        Route::get('mindex', 'ReimbursementsController@mindex');
        Route::get('mindexmy', 'ReimbursementsController@mindexmy');      // 我发起的
        Route::get('mcreate', 'ReimbursementsController@mcreate');
        Route::post('check', 'ReimbursementsController@check');
        Route::post('mstore', 'ReimbursementsController@mstore');
        Route::get('mshow/{id}', 'ReimbursementsController@mshow');
        Route::get('mindexmyapproval', 'ReimbursementsController@mindexmyapproval');      // 待我审批的
        Route::get('mindexmyapprovaled', 'ReimbursementsController@mindexmyapprovaled');      // 我已审批的
        Route::get('search/{key}', 'ReimbursementsController@search');
    });
    Route::resource('reimbursements', 'ReimbursementsController');
    Route::group(['prefix' => 'paymentrequests'], function() {
        Route::get('mcreate', 'PaymentrequestsController@mcreate');
        Route::post('mstore', 'PaymentrequestsController@mstore');
        Route::get('mshow/{id}', 'PaymentrequestsController@mshow');
        Route::post('search', 'PaymentrequestsController@search');              // 搜索功能
        Route::post('msearch', 'PaymentrequestsController@msearch');              // 搜索功能
        Route::get('search2/{key}', 'PaymentrequestsController@search2');         // 查询数据
        Route::post('export', 'PaymentrequestsController@export');
        Route::post('exportitem/{id}', 'PaymentrequestsController@exportitem');
        Route::delete('mdestroy/{id}', 'PaymentrequestsController@mdestroy');
        Route::get('{id}/mrecvdetail', 'PaymentrequestsController@mrecvdetail');
        Route::get('{id}/mrecvdetail2', 'PaymentrequestsController@mrecvdetail2');
        Route::get('{id}/mrecvdetail3', 'PaymentrequestsController@mrecvdetail3');
        Route::get('{id}/mrecvdetail4', 'PaymentrequestsController@mrecvdetail4');
        Route::get('{id}/mrecvdetail5', 'PaymentrequestsController@mrecvdetail5');
        Route::get('mrecvdetail5data/{itemid}/{receiptid?}', 'PaymentrequestsController@mrecvdetail5data');
        Route::get('{id}/printpage', 'PaymentrequestsController@printpage');
        Route::get('{id}/pay', 'PaymentrequestsController@pay');
        Route::get('hasrepeat/{pohead_id}/{amount?}', 'PaymentrequestsController@hasrepeat');    // pdfjs viewer.html
        Route::get('exceedingpay/{pohead_id}/{amount?}', 'PaymentrequestsController@exceedingpay');
        Route::get('indexjson', 'PaymentrequestsController@indexjson');    // pdfjs viewer.html
//        Route::get('pdfjs/viewer/{pdffile?}', 'PaymentrequestsController@pdfjsviewer');
    });
    Route::resource('paymentrequests', 'PaymentrequestsController');
    Route::group(['prefix' => 'issuedrawing'], function() {
        Route::get('getitemsbysoheadid/{sohead_id}', 'IssuedrawingController@getitemsbysoheadid');
        Route::get('mcreate', 'IssuedrawingController@mcreate');
        Route::post('mstore', 'IssuedrawingController@mstore');
        Route::get('{id}/modifyweight', 'IssuedrawingController@modifyweight');
        Route::post('{id}/updateweight', 'IssuedrawingController@updateweight');
        Route::post('{id}/mupdateweight', 'IssuedrawingController@mupdateweight');
        Route::any('search', 'IssuedrawingController@search');              // 搜索功能
        Route::get('mshow/{id}', 'IssuedrawingController@mshow');
    });
    Route::resource('issuedrawing', 'IssuedrawingController');
    Route::resource('approversettings', 'ApproversettingsController');

    Route::group(['prefix' => 'reimbursementapprovals'], function() {
        Route::get('{reimbursementid}/mcreate', 'ReimbursementapprovalsController@mcreate');
        Route::post('mstore', 'ReimbursementapprovalsController@mstore');
    });
    Route::resource('reimbursementapprovals', 'ReimbursementapprovalsController');
    Route::group(['prefix' => 'paymentrequestapprovals'], function() {
        Route::get('{paymentrequestid}/mcreate', 'PaymentrequestapprovalsController@mcreate');
        Route::post('mstore', 'PaymentrequestapprovalsController@mstore');
    });
    Route::resource('paymentrequestapprovals', 'PaymentrequestapprovalsController');
    Route::group(['prefix' => 'mindexmy'], function() {
        Route::get('', 'ApprovalController@mindexmy');      // 我发起的
        Route::any('search/{key?}', 'ApprovalController@searchmindexmy');      // 我发起的
    });
    Route::get('mindexmying', 'ApprovalController@mindexmying');      // 我发起的
    Route::group(['prefix' => 'mindexmyed'], function() {
        Route::get('', 'ApprovalController@mindexmyed');      // 我发起的
        Route::any('search/{key?}', 'ApprovalController@searchmindexmyed');      // 我发起的
    });
    Route::group(['prefix' => 'mindexmyapproval', 'as' => 'approval/mindexmyapproval'], function() {
        Route::any('', 'ApprovalController@mindexmyapproval');      // 待我审批的
        Route::any('search/{key?}', 'ApprovalController@searchmindexmyapproval');      // 待我审批的
    });
    Route::group(['prefix' => 'mindexmyapprovaled'], function() {
        Route::get('', 'ApprovalController@mindexmyapprovaled');      // 我已审批的
        Route::any('search/{key?}', 'ApprovalController@searchmindexmyapprovaled');      // 我已审批的
    });
    // Route::get('mindexmyapprovaled', 'ApprovalController@mindexmyapprovaled');      // 我已审批的

    Route::group(['prefix' => 'reports'], function() {
        Route::get('paymentrequest', 'ApprovalreportsController@paymentrequest');
    });

    Route::resource('/', 'ApprovalController');
    Route::resource('approvaltypes', 'ApprovaltypesController');

    Route::resource('paymentrequestretract', 'PaymentrequestretractController');
    Route::group(['prefix' => 'paymentrequestretractapproval'], function() {
        Route::get('{paymentrequestretractid}/mcreate', 'PaymentrequestretractapprovalController@mcreate');
        Route::post('mstore', 'PaymentrequestretractapprovalController@mstore');
    });
    Route::resource('paymentrequestretractapproval', 'PaymentrequestretractapprovalController');

    Route::group(['prefix' => 'mcitempurchase'], function() {
        Route::get('mcreate', 'McitempurchaseController@mcreate');
        Route::post('mstore', 'McitempurchaseController@mstore');

    });
    Route::resource('mcitempurchase', 'McitempurchaseController');
    Route::resource('mcitempurchaseattachment', 'McitempurchaseattachmentController');
    Route::resource('mcitempurchaseissuedrawing', 'McitempurchaseissuedrawingController');
    Route::resource('mcitempurchaseitem', 'McitempurchaseitemController');

    Route::group(['prefix' => 'pppayment'], function() {
        Route::get('mcreate', 'PppaymentController@mcreate');
        Route::post('mstore', 'PppaymentController@mstore');
        Route::post('synchronize_status_to_erp', 'PppaymentController@synchronize_status_to_erp');
    });
    Route::resource('pppayment', 'PppaymentController');
    Route::resource('pppaymentitem', 'PppaymentitemController');
    Route::resource('pppaymentitemissuedrawing', 'PppaymentitemissuedrawingController');
    Route::resource('pppaymentitemattachment', 'PppaymentitemattachmentController');

    Route::group(['prefix' => 'projectsitepurchases'], function() {
        Route::get('mcreate', 'ProjectsitepurchaseController@mcreate');
        Route::post('mstore', 'ProjectsitepurchaseController@mstore');
        Route::get('getitemsbykey/{key}', 'ProjectsitepurchaseController@getitemsbykey');
    });
    Route::resource('projectsitepurchases', 'ProjectsitepurchaseController');

    Route::group(['prefix' => 'vendordeductions'], function() {
        Route::get('mcreate', 'VendordeductionController@mcreate');
        Route::post('mstore', 'VendordeductionController@mstore');
        Route::get('getitemsbykey/{key}', 'VendordeductionController@getitemsbykey');
    });
    Route::resource('vendordeductions', 'VendordeductionController');

    Route::group(['prefix' => 'techpurchase'], function() {
        Route::get('mcreate', 'TechpurchaseController@mcreate');
        Route::post('mstore', 'TechpurchaseController@mstore');

    });
    Route::resource('techpurchase', 'TechpurchaseController');
    Route::resource('techpurchaseattachment', 'TechpurchaseachmentController');
    Route::resource('techpurchaseitem', 'TechpurchaseitemController');

    Route::post('bingdingtalk', 'ApprovalController@bingdingtalk');
    Route::get('report', '\App\Http\Controllers\System\ReportController@indexapproval');
    Route::group(['prefix' => 'report2'], function() {
        Route::get('issuedrawingpurchasedetail', 'ApprovalController@issuedrawingpurchasedetail');
        Route::post('issuedrawingpurchasedetailexport', 'ApprovalController@issuedrawingpurchasedetailexport');
        Route::post('issuedrawingpurchasedetailexport2', 'ApprovalController@issuedrawingpurchasedetailexport2');
        Route::post('issuedrawingpurchasedetailexport3', 'ApprovalController@issuedrawingpurchasedetailexport3');
        Route::get('issuedrawingjson', 'ApprovalController@issuedrawingjson');
        Route::get('mcitempurchasejson', 'ApprovalController@mcitempurchasejson');
        Route::get('pppaymentjson', 'ApprovalController@pppaymentjson');
    });
    Route::group(['prefix' => 'synchronize'], function () {
        Route::get('', 'SynchronizeController@index');
        Route::post('synchronize', 'SynchronizeController@synchronize');
    });
});

Route::group(['prefix' => 'approval', 'namespace' => 'Approval', 'middleware' => ['web', 'auth']], function() {

    Route::group(['prefix' => 'paymentrequests'], function() {
        Route::get('pdfjs/viewer/{pdffile?}', 'PaymentrequestsController@pdfjsviewer');
    });
});

Route::group(['prefix' => 'dingtalk', 'namespace' => 'Dingtalk', 'middleware' => ['web', 'auth']], function () {
    Route::group(['prefix' => 'dtlogs'], function () {
        Route::post('search', 'DtlogController@search');
        Route::post('relate_xmjlsgrz_sohead_id', 'DtlogController@relate_xmjlsgrz_sohead_id');
        Route::post('relate_gctsrz_sohead_id', 'DtlogController@relate_gctsrz_sohead_id');
    });
    Route::group(['prefix' => 'dtlogs/{dtlog}'], function () {
        Route::get('attachsohead', 'DtlogController@attachsohead');
        Route::patch('updateattachsohead', 'DtlogController@updateattachsohead');
        Route::get('peoplecount', 'DtlogController@peoplecount');
        Route::patch('updatepeoplecount', 'DtlogController@updatepeoplecount');
    });
    Route::resource('dtlogs', 'DtlogController');
    Route::get('report', '\App\Http\Controllers\System\ReportController@indexdingtalk');
});

Route::group(['prefix' => 'system', 'namespace' => 'System', 'middleware' => ['web', 'auth']], function() {
    Route::resource('employees', 'EmployeesController');
    Route::resource('depts', 'DeptsController');
    Route::resource('images', 'ImagesController');
    Route::post('users/test', 'UsersController@test');
    Route::get('users/{id}/editrole', 'UsersController@editrole');
    Route::post('users/{id}/updaterole', 'UsersController@updaterole');
    Route::post('userroles/store', 'UserrolesController@store');
    Route::get('users/{id}/roles/edit', 'UserrolesController@edit');
    Route::group(['prefix' => 'users'], function() {
        Route::post('updateuseroldall', 'UsersController@updateuseroldall');

        Route::post('search', 'UsersController@search');              // 搜索功能
        Route::post('msearch', 'UsersController@msearch');              // 搜索功能
        Route::get('getitemsbykey/{key}', 'UsersController@getitemsbykey');
    });
    Route::group(['prefix' => 'users/{id}'], function() {
        Route::get('editpass', 'UsersController@editpass');
        Route::post('updatepass', 'UsersController@updatepass');
        Route::get('edituserold', 'UsersController@edituserold');
        Route::post('updateuserold', 'UsersController@updateuserold');
        Route::get('google2fa', 'UsersController@google2fa');
        Route::post('updategoogle2fa', 'UsersController@updategoogle2fa');
    });
    Route::post('users/bingdingtalk', 'UsersController@bingdingtalk');
    Route::post('users/bingdingtalkcancel', 'UsersController@bingdingtalkcancel');
    Route::post('users/binddingtalk2', 'UsersController@binddingtalk2');
    Route::post('users/binddingtalk2cancel', 'UsersController@binddingtalk2cancel');
    // Route::post('users/test', 'UsersController@test');
    Route::resource('users', 'UsersController');
    Route::group(['prefix' => 'users/{user}/roles'], function () {
        Route::get('/', 'UserrolesController@index');
        Route::get('create', 'UserrolesController@create');
        Route::delete('destroy/{role}', 'UserrolesController@destroy');
    });
    Route::resource('roles', 'RolesController');
     Route::resource('permissions', 'PermissionsController');
    Route::group(['prefix' => 'roles/{role}/permissions'], function() {
        Route::get('/', 'RolepermissionsController@index');
        Route::get('create', 'RolepermissionsController@create');
        Route::delete('destroy/{permission}', 'RolepermissionsController@destroy');
    });
    Route::post('rolepermissions/store', 'RolepermissionsController@store');
    Route::get('report/{id}/statistics/{autostatistics}', 'ReportController@statistics');
    Route::any('report/{id}/statistics', 'ReportController@statistics');
    Route::post('report/{id}/export', 'ReportController@export');
    Route::resource('report', 'ReportController');

    Route::group(['prefix' => 'userold'], function() {
        Route::get('hasrepeatoldid/{oldid}', 'UseroldController@hasrepeatoldid');
    });
    Route::resource('userold', 'UseroldController');

    Route::resource('operationlog', 'OperationlogController');
    Route::group(['prefix' => 'reminderswitches'], function() {
        Route::get('storebyclick/{tablename}/{tableid}/{type}/{value}', 'ReminderswitchController@storebyclick');
    });
    Route::resource('reminderswitches', 'ReminderswitchController');
});

Route::group(['prefix' => 'teaching', 'namespace' => 'Teaching', 'middleware' => ['web', 'auth']], function() {
    Route::resource('teachingpoint', 'TeachingpointController');
    Route::resource('teachingadministrator', 'TeachingadministratorController');
    Route::resource('teachingstudentimage', 'TeachingstudentimageController');


    // Route::group(['prefix' => 'mindexmy'], function() {
    //     Route::get('', 'ApprovalController@mindexmy');      // 我发起的
    //     Route::post('search/{key?}', 'ApprovalController@searchmindexmy');      // 我发起的
    // });
    // Route::get('mindexmying', 'ApprovalController@mindexmying');      // 我发起的
    // Route::get('mindexmyed', 'ApprovalController@mindexmyed');      // 我发起的
    // Route::group(['prefix' => 'mindexmyapproval'], function() {
    //     Route::get('', 'ApprovalController@mindexmyapproval');      // 待我审批的
    //     Route::post('search/{key?}', 'ApprovalController@searchmindexmyapproval');      // 待我审批的
    // });
    // Route::group(['prefix' => 'mindexmyapprovaled'], function() {
    //     Route::get('', 'ApprovalController@mindexmyapprovaled');      // 我已审批的
    //     Route::post('search/{key?}', 'ApprovalController@searchmindexmyapprovaled');      // 我已审批的
    // });
    // // Route::get('mindexmyapprovaled', 'ApprovalController@mindexmyapprovaled');      // 我已审批的

    // Route::group(['prefix' => 'reports'], function() {
    //     Route::get('paymentrequest', 'ApprovalreportsController@paymentrequest');
    // });

    // Route::resource('/', 'ApprovalController');
    // Route::resource('approvaltypes', 'ApprovaltypesController');
});

Route::group(['prefix' => 'my', 'namespace' => 'My', 'middleware' => ['web', 'auth']], function() {
    Route::group(['prefix' => 'bonus'], function() {
        Route::any('', 'MyController@bonus');
        Route::any('byorder', 'MyController@bonusbyorder');
        Route::post('byorderexport', 'MyController@byorderexport');
        Route::post('byorderexport2', 'MyController@byorderexport2');
        Route::get('indexjsonbyorder', 'MyController@indexjsonbyorder');
        Route::get('detailjsonbyorder/{sohead_id}', 'MyController@detailjsonbyorder');
        Route::get('indexjsonbysalesmanager', 'MyController@indexjsonbysalesmanager');
        Route::get('indexjsonbytechdept', 'MyController@indexjsonbytechdept');
//        Route::any('search', 'MyController@searchbonus');
    });
//    Route::group(['prefix' => 'bonusbyorder'], function() {
//        Route::get('', 'MyController@bonusbyorder');
//    });
});

Route::group(['middleware' => 'web'], function () {
    Route::auth();

    // for admin login
//    Route::post('login2', 'Auth\AuthController@login2');
    Route::get('login2', 'Auth\AuthController@showLoginForm2');
//    Route::get('/home', 'HomeController@index');
});



// Load other urls.
$GodPath = __DIR__.'/../God/routes.php';
if (file_exists($GodPath)) {
    include_once $GodPath;
}

// git pull route
// Route::get('gitpull', function() {
//     return view('gitpull');
// });

Route::post('gitpull2', function() {
    return view('gitpull2');
});

Route::post('gitpullpost', function() {
    return view('gitpullpost');
});
