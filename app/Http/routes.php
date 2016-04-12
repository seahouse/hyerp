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


Route::group(['middleware' => ['web']], function () {
    // Route::get('mddauth', function() { return view('mddauth'); });
    Route::get('mddauth', 'DingTalkController@mddauth');
    Route::get('mapproval', function() { return view('mapproval'); });

	Route::get('dingtalk/getuserinfo/{code}', 'DingTalkController@getuserinfo');
    Route::get('dingtalk/getconfig', 'DingTalkController@getconfig');
});

Route::group(['middleware' => ['web', 'auth']], function () {
    //
    Route::get('/', function() { return view('navbarerp'); });
	Route::get('/home', function() { return view('navbarerp'); });

//     Route::resource('itemclasses', 'ItemclassesController');
//     Route::post('items/search', 'ItemsController@search');
//     Route::group(['prefix' => 'items'], function() {
//         Route::get('mindex', 'ItemsController@mindex');
//     });
//     Route::resource('items', 'ItemsController');
//     Route::resource('boms', 'BomsController');
//     Route::get('bomitems/{id}/createitem', 'BomitemsController@createitem');
//     Route::resource('bomitems', 'BomitemsController');
    // Route::resource('contacts', 'ContactsController');
    // Route::resource('custinfos', 'CustinfosController');

    Route::get('api/dropdown', 'Addr\ProvincesController@getIndex');
});

Route::group(['prefix' => 'approval', 'namespace' => 'Approval', 'middleware' => ['web']], function() {
    // Route::group(['prefix' => 'reimbursements'], function() {
    //     Route::get('mindex', 'ReimbursementsController@mindex');
    //     Route::get('mcreate', 'ReimbursementsController@mcreate');
    //     Route::post('mstore', 'ReimbursementsController@mstore');
    // });
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
});

Route::group(['prefix' => 'product', 'namespace' => 'Product', 'middleware' => ['web', 'auth']], function() {
    Route::resource('itemclasses', 'ItemclassesController');
    Route::post('items/search', 'ItemsController@search');
    Route::group(['prefix' => 'items'], function() {
        Route::get('mmindex', 'ItemsController@mmindex');
        Route::get('mindex', 'ItemsController@mindex');
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
        Route::get('getitemsbykey/{key}', 'SalesordersController@getitemsbykey');
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
    Route::resource('custinfos', 'CustinfosController');
});

Route::group(['prefix' => 'sales', 'namespace' => 'Sales'], function() {
    Route::group(['prefix' => 'salesorders/receiptpayments'], function () {
        Route::post('storebync', 'ReceiptpaymentsController@storebync');
    });
});

Route::group(['prefix' => 'purchase', 'namespace' => 'Purchase', 'middleware' => ['web', 'auth']], function() {
    Route::resource('vendinfos', 'VendinfosController');
    Route::resource('vendtypes', 'VendtypesController');
    Route::get('purchaseorders/{id}/detail', 'PurchaseordersController@detail');
    Route::get('purchaseorders/{id}/receiving', 'PurchaseordersController@receiving');
    Route::group(['prefix' => 'purchaseorders/{purchaseorder}/payments'], function () {
        Route::get('/', 'PaymentsController@index');
        Route::get('create', 'PaymentsController@create');
        Route::post('store', 'PaymentsController@store');
        Route::delete('destroy/{payment}', 'PaymentsController@destroy');
    });
    Route::resource('purchaseorders', 'PurchaseordersController');
    Route::get('poitems/{headId}/create', 'PoitemsController@createByPoheadId');
    Route::resource('poitems', 'PoitemsController');
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
        Route::post('mstore', 'ReimbursementsController@mstore');
        Route::get('mshow/{id}', 'ReimbursementsController@mshow');
        Route::get('mindexmyapproval', 'ReimbursementsController@mindexmyapproval');      // 待我审批的
        Route::get('mindexmyapprovaled', 'ReimbursementsController@mindexmyapprovaled');      // 我已审批的
        Route::get('search/{key}', 'ReimbursementsController@search');
    });
    Route::resource('reimbursements', 'ReimbursementsController');
    Route::resource('approversettings', 'ApproversettingsController');

    Route::group(['prefix' => 'reimbursementapprovals'], function() {
        Route::get('{reimbursementid}/mcreate', 'ReimbursementapprovalsController@mcreate');
        Route::post('mstore', 'ReimbursementapprovalsController@mstore');
    });
    Route::resource('reimbursementapprovals', 'ReimbursementapprovalsController');
});

Route::group(['prefix' => 'system', 'namespace' => 'System', 'middleware' => ['web', 'auth']], function() {
    Route::resource('employees', 'EmployeesController');
    Route::resource('depts', 'DeptsController');
    Route::resource('images', 'ImagesController');
    Route::get('users/{id}/editrole', 'UsersController@editrole');
    Route::post('users/{id}/updaterole', 'UsersController@updaterole');
    Route::post('userroles/store', 'UserrolesController@store');
    Route::get('users/{id}/roles/edit', 'UserrolesController@edit');
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
});

Route::group(['middleware' => 'web'], function () {
    Route::auth();

//    Route::get('/home', 'HomeController@index');
});


// Load other urls.
$GodPath = __DIR__.'/../God/routes.php';
if (file_exists($GodPath)) {
    include_once $GodPath;
}

Route::get('gitpull', function() {
    return view('gitpull');
});
