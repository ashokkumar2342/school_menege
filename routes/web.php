<?php

// Route::get('/', function () {
//     return redirect()->route('admin.login');
 
// });
Route::get('/', function () {
    return redirect()->route('admin.login');
 	// return view('admin.auth.login');
});

Route::get('getallclass', 'Admin\ApiController@getclass')->name('admin.api.getclass');
Route::get('getallsubjects', 'Admin\ApiController@getallsubjects')->name('admin.api.getallsubjects');
Route::get('getsubjectadmin/{class_id}', 'Admin\ApiController@getsubject')->name('admin.api.getsubject');
Route::get('getschapter/{class_id}/{subject_id}', 'Admin\ApiController@getchapter')->name('admin.api.getchapter');

Route::get('getpdf/{chapter_id}', 'Admin\ApiController@getpdf')->name('admin.api.getpdf');
Route::get('pdf/view/{encryptedPath}', 'Admin\ApiController@securePdfView')->name('admin.common.pdf.view');

Route::get('getvideoClassWise/{class_id}', 'Admin\ApiController@getvideoClassWise')->name('admin.api.getvideoClassWise');

Route::get('getvideo/{chapter_id}', 'Admin\ApiController@getvideo')->name('admin.api.getvideo');
Route::get('viewvideo/stream/{id}/{token}', 'Admin\ApiController@strem_video')->name('admin.viewvideo.stream');

Route::get('/pdf/proxy/{enc}', 'Admin\ApiController@show')
    ->name('pdf.proxy');




