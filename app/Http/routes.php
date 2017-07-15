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
// Route::post('dingtalk/receive', function() {
//     return '';
// });

Route::post('faceplusplus/detect', 'FaceplusplusController@detect');
Route::post('faceplusplus/compare', 'FaceplusplusController@compare');
Route::post('faceplusplus/search', 'FaceplusplusController@search');
Route::post('faceplusplus/faceset_create', 'FaceplusplusController@faceset_create');

Route::post('facecore/urlfacedetect', 'FacecoreController@urlfacedetect');

Route::post('cloudwalk/face_tool_detect', 'CloudwalkController@face_tool_detect');

Route::group(['middleware' => ['web']], function () {
    // Route::get('mddauth', function() { return view('mddauth'); });
    Route::get('mddauth/{appname?}/{url?}', 'DingTalkController@mddauth');
//    Route::get('test', 'DingTalkController@test');
    Route::get('ddauth/{appname?}/{url?}', 'DingTalkController@ddauth');

	Route::get('dingtalk/getuserinfo/{code}', 'DingTalkController@getuserinfo');
    Route::get('dingtalk/getuserinfoByScancode/{code}', 'DingTalkController@getuserinfoByScancode');
    Route::get('dingtalk/getuserinfoByScancode_hxold/{code}', 'DingTalkController@getuserinfoByScancode_hxold');
    Route::get('dingtalk/getconfig', 'DingTalkController@getconfig');
    Route::post('dingtalk/register_call_back', 'DingTalkController@register_call_back');
    Route::get('dingtalk/delete_call_back', 'DingTalkController@delete_call_back');
    Route::post('dingtalk/synchronizeusers', 'DingTalkController@synchronizeusers');

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
});

Route::group(['middleware' => ['web', 'auth']], function () {
    //
    Route::get('/', function() { return view('navbarerp'); });
	Route::get('/home', function() { return view('navbarerp'); });
    Route::get('mapproval', function() { return view('mapproval'); });


    Route::get('api/dropdown', 'Addr\ProvincesController@getIndex');
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
});

Route::group(['prefix' => 'sales', 'namespace' => 'Sales', 'middleware' => ['web', 'auth']], function() {
    Route::get('salesorders/{id}/ship', 'SalesordersController@ship');
    Route::post('salesorders/search', 'SalesordersController@search');
    Route::group(['prefix' => 'salesorders'], function() {
        Route::get('mindex', 'SalesordersController@mindex');
        Route::get('getitemsbykey/{key}/{customerid?}', 'SalesordersController@getitemsbykey');
        Route::get('getitembyid/{id}', 'SalesordersController@getitembyid');
        Route::get('getsohx', 'SalesordersController@getsohx');
        Route::get('{id}/mstatistics', 'SalesordersController@mstatistics');
    });
    Route::resource('salesorders', 'SalesordersController');
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

        Route::get('index_hx', 'PurchaseordersController@index_hx');
        Route::post('search_hx', 'PurchaseordersController@search_hx');
    });
    Route::resource('purchaseorders', 'PurchaseordersController');
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
        Route::get('{id}/printpage', 'PaymentrequestsController@printpage');
        Route::get('{id}/pay', 'PaymentrequestsController@pay');
    });
    Route::resource('paymentrequests', 'PaymentrequestsController');
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
        Route::post('search/{key?}', 'ApprovalController@searchmindexmy');      // 我发起的
    });
    Route::get('mindexmying', 'ApprovalController@mindexmying');      // 我发起的
    Route::get('mindexmyed', 'ApprovalController@mindexmyed');      // 我发起的
    Route::group(['prefix' => 'mindexmyapproval'], function() {
        Route::get('', 'ApprovalController@mindexmyapproval');      // 待我审批的
        Route::post('search/{key?}', 'ApprovalController@searchmindexmyapproval');      // 待我审批的
    });
    Route::group(['prefix' => 'mindexmyapprovaled'], function() {
        Route::get('', 'ApprovalController@mindexmyapprovaled');      // 我已审批的
        Route::post('search/{key?}', 'ApprovalController@searchmindexmyapprovaled');      // 我已审批的
    });
    // Route::get('mindexmyapprovaled', 'ApprovalController@mindexmyapprovaled');      // 我已审批的

    Route::group(['prefix' => 'reports'], function() {
        Route::get('paymentrequest', 'ApprovalreportsController@paymentrequest');
    });

    Route::resource('/', 'ApprovalController');
    Route::resource('approvaltypes', 'ApprovaltypesController');
});

Route::group(['prefix' => 'system', 'namespace' => 'System', 'middleware' => ['web', 'auth']], function() {
    Route::resource('employees', 'EmployeesController');
    Route::resource('depts', 'DeptsController');
    Route::resource('images', 'ImagesController');
    Route::get('users/{id}/editrole', 'UsersController@editrole');
    Route::post('users/{id}/updaterole', 'UsersController@updaterole');
    Route::post('userroles/store', 'UserrolesController@store');
    Route::get('users/{id}/roles/edit', 'UserrolesController@edit');
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
    Route::get('report/{id}/statistics', 'ReportController@statistics');
    Route::post('report/{id}/statistics', 'ReportController@statistics');
    Route::post('report/{id}/export', 'ReportController@export');
    Route::resource('report', 'ReportController');

    Route::group(['prefix' => 'userold'], function() {
        Route::get('hasrepeatoldid/{oldid}', 'UseroldController@hasrepeatoldid');
    });
    Route::resource('userold', 'UseroldController');
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

Route::group(['middleware' => 'web'], function () {
    Route::auth();

//    Route::get('/home', 'HomeController@index');
});

// for admin login
Route::group(['middleware' => 'web'], function () {
//    Route::post('login2', 'Auth\AuthController@login2');
    Route::get('login2', 'Auth\AuthController@showLoginForm');

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
