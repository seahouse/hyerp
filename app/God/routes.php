<?php

Route::group(['middleware' => ['web']], function () {
    Route::group(['prefix' => 'god', 'namespace' => ''], function () {
        Route::get('',     '\App\God\Controllers\HomeController@index');
        Route::get('home', '\App\God\Controllers\HomeController@index');

        Route::group(['middleware' => ['auth']], function () {
            Route::group(['prefix' => 'system', 'namespace' => ''], function() {
                Route::resource('user',            '\App\God\Controllers\System\UserController');
                Route::resource('role',            '\App\God\Controllers\System\RoleController');
                Route::resource('permission',      '\App\God\Controllers\System\PermissionController');
                Route::resource('permission_role', '\App\God\Controllers\System\PermissionRoleController');
                Route::resource('role_user',       '\App\God\Controllers\System\RoleUserController');
            });

            Route::group(['prefix' => 'approval', 'namespace' => ''], function () {
                Route::put('reimbursement/approve/{id}', '\App\God\Controllers\Approval\ReimbursementController@approve');
                Route::resource('reimbursement',         '\App\God\Controllers\Approval\ReimbursementController');
                Route::resource('reimbursementtype',     '\App\God\Controllers\Approval\ReimbursementTypeController');
            });
        });
    });

    Route::group(['prefix' => 'dingtalk', 'namespace' => ''], function () {
        Route::get('login',  '\App\God\Controllers\DingTalk\AuthController@login');
        Route::get('logout', '\App\God\Controllers\DingTalk\AuthController@logout');

        Route::group(['middleware' => ['auth']], function () {
            Route::group(['prefix' => 'approval', 'namespace' => ''], function () {
                Route::get('',            '\App\God\Controllers\DingTalk\ApprovalController@home');
                Route::get('requesttome', '\App\God\Controllers\DingTalk\ApprovalController@requestToMe');
                Route::get('handledbyme', '\App\God\Controllers\DingTalk\ApprovalController@handledByMe');
                Route::get('requestbyme', '\App\God\Controllers\DingTalk\ApprovalController@requestByMe');
            });
        });
    });
});
