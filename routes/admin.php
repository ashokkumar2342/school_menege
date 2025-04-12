<?php


Route::group(['middleware' => ['preventBackHistory','web']], function() {
	Route::get('login', 'Auth\LoginController@login')->name('admin.login'); 
	Route::post('logout', 'Auth\LoginController@logout')->name('admin.logout.get');
	Route::get('logout_time', 'Auth\LoginController@logout')->name('admin.logout_time.get');
	Route::get('refreshcaptcha', 'Auth\LoginController@refreshCaptcha')->name('admin.refresh.captcha');
	Route::post('login-post', 'Auth\LoginController@loginPost')->name('admin.login.post');

	Route::get('payment/{s_code}', 'OnlinePaymentController@payment')->name('admin.online.payment');
});

Route::group(['middleware' => ['preventBackHistory','admin','web']], function() {
	Route::get('dashboard', 'DashboardController@index')->name('admin.dashboard');

	Route::prefix('account')->group(function () {
		//Change Password
		Route::get('change-password', 'AccountController@changePassword')->name('admin.account.change.password');
		Route::post('change-password-store', 'AccountController@changePasswordStore')->name('admin.account.change.password.store');
		//Reset Password
		Route::get('reset-password', 'AccountController@resetPassWord')->name('admin.account.reset.password'); 
		Route::post('reset-password-change', 'AccountController@resetPassWordChange')->name('admin.account.reset.password.change');
		
	});

	// Support
	Route::group(['prefix' => 'support'], function() {
		// Feedback/Help/Error (751)
	    Route::get('desk_index', 'SupportController@deskIndex')->name('admin.support.desk.index');
	    Route::get('desk_table', 'SupportController@deskTable')->name('admin.support.desk.table');
	    Route::get('desk-form', 'SupportController@deskForm')->name('admin.support.desk.form');
	    Route::post('desk-store', 'SupportController@deskStore')->name('admin.support.desk.store');
	    
	    // Feedback/Solution (752)
	    Route::get('solution_index', 'SupportController@solutionIndex')->name('admin.support.solution.index');
	    Route::get('solution_table', 'SupportController@solutionTable')->name('admin.support.solution.table');
	    Route::get('solution_status/{rec_id}', 'SupportController@solutionStatus')->name('admin.support.solution.status');
	    Route::post('solution_store/{rec_id}', 'SupportController@solutionStore')->name('admin.support.solution.store');

	    // Error Exception (753)
	    Route::get('error_index', 'SupportController@errorIndex')->name('admin.support.error.index');
	    Route::get('error-show', 'SupportController@errorShow')->name('admin.support.error.show');
	    Route::get('error-resolved/{id}', 'SupportController@resolved')->name('admin.support.error.resolved');
	    Route::get('error-delete/{id}', 'SupportController@delete')->name('admin.support.error.delete');
	}); 

	
 });
