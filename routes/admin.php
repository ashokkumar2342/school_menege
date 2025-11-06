<?php


// Route::group(['middleware' => ['preventBackHistory','web']], function() {
	Route::get('login', 'Auth\LoginController@login')->name('admin.login'); 
	Route::post('logout', 'Auth\LoginController@logout')->name('admin.logout.get');
	Route::get('logout_time', 'Auth\LoginController@logout')->name('admin.logout_time.get');
	Route::get('refreshcaptcha', 'Auth\LoginController@refreshCaptcha')->name('admin.refresh.captcha');
	Route::post('login-post', 'Auth\LoginController@loginPost')->name('admin.login.post');

	Route::get('payment/{s_code}', 'OnlinePaymentController@payment')->name('admin.online.payment');
// });

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

	//Class create (11)
	Route::group(['prefix' => 'class'], function() {
	    Route::get('ct_list', 'ClassTypeController@index')->name('admin.class.index');
	    Route::get('ct_edit/{id}', 'ClassTypeController@edit')->name('admin.class.edit');
	    Route::post('ct_update/{id}', 'ClassTypeController@update')->name('admin.class.update');
	    Route::get('ct_deleteclass/{id}', 'ClassTypeController@deleteclass')->name('admin.class.deleteclass');
	});

	//Suject Type (12)
	Route::group(['prefix' => 'subject-type'], function() {
    	Route::get('sub_type_list', 'SubjectTypeController@index')->name('admin.subjectType.index');
		Route::get('sub_type_edit/{id}', 'SubjectTypeController@edit')->name('admin.subjectType.edit');
   		Route::post('update/{id}', 'SubjectTypeController@update')->name('admin.subjectType.update');
   		Route::get('sub_type_delete/{id}', 'SubjectTypeController@destroy')->name('admin.subjectType.delete');
   		Route::get('sub_type_pdf-generate', 'SubjectTypeController@pdfGenerate')->name('admin.subjectType.pdf.generate');
 
	});
	//Class Subject (13)
	Route::group(['prefix' => 'subject'], function() {
    	Route::get('cl_sub_list', 'SubjectController@index')->name('admin.subject.manageSubject,index');
    	Route::get('cl_sub_list_search', 'SubjectController@search')->name('admin.subject.search');
    	Route::post('cl_sub_list_add', 'SubjectController@store')->name('admin.subject.add');
    	Route::get('cl_sub_list_delete/{id}', 'SubjectController@destroy')->name('admin.manageSubject.delete');
	});

	//chapter (14)
	Route::group(['prefix' => 'chapter'], function() {
		Route::get('index', 'ChapterController@index')->name('admin.chapter.index');
		Route::get('table', 'ChapterController@table')->name('admin.chapter.table');
		Route::post('store', 'ChapterController@store')->name('admin.chapter.store');
		Route::get('edit/{rec_id}', 'ChapterController@edit')->name('admin.chapter.edit');
		Route::post('update/{rec_id}', 'ChapterController@update')->name('admin.chapter.update');
		Route::get('delete/{rec_id}', 'ChapterController@delete')->name('admin.chapter.delete');
		
	});


	//upload video (15)
	Route::group(['prefix' => 'video'], function() {
		Route::get('v-index', 'VideoController@video_index')->name('admin.video.index');
		Route::get('v-table', 'VideoController@video_table')->name('admin.video.table');
		Route::post('v-store', 'VideoController@store')->name('admin.video.store');
		Route::post('/watch-event', 'VideoController@watchEvent')->name('video.watch.event');

		

		
		Route::get('p-index', 'VideoController@pdf_index')->name('admin.pdf.index');
		Route::get('p-table', 'VideoController@pdf_table')->name('admin.pdf.table');
		Route::post('p-store', 'VideoController@pdf_store')->name('admin.pdf.store');
		Route::get('p-delete/{rec_id}', 'VideoController@pdf_delete')->name('admin.pdf.delete');
		
	});

	Route::group(['prefix' => 'common'], function() {
		Route::get('cmnctl-ctsub', 'CommonController@classWiseSubject')->name('admin.common.class.wise.subjects');
		Route::get('get-chapter', 'CommonController@subjectWiseChapter')->name('admin.common.subjects.wise.chapter');
	});

	// Support
	Route::group(['prefix' => 'support'], function() {
		// // Feedback/Help/Error (751)
	 //    Route::get('desk_index', 'SupportController@deskIndex')->name('admin.support.desk.index');
	 //    Route::get('desk_table', 'SupportController@deskTable')->name('admin.support.desk.table');
	 //    Route::get('desk-form', 'SupportController@deskForm')->name('admin.support.desk.form');
	 //    Route::post('desk-store', 'SupportController@deskStore')->name('admin.support.desk.store');
	    
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
	Route::group(['prefix' => 'school-detail'], function() {
	    Route::get('index', 'SchoolDetailsController@index')->name('admin.school.detail.index');
	    Route::get('add/{id}', 'SchoolDetailsController@edit')->name('admin.school.detail.edit');
	    Route::post('store/{id}', 'SchoolDetailsController@update')->name('admin.school.detail.update');
	    Route::get('delete/{id}', 'SchoolDetailsController@destroy')->name('admin.school.detail.delete');
	});

	
 });
