<?php

// Route::get('/', function () {
//     return redirect()->route('admin.login');
 
// });
Route::get('/', function () {
    return redirect()->route('admin.login');
 	// return view('admin.auth.login');
});

Route::get('getuserdetails', 'Admin\ApiController@getuserdetails')->name('admin.api.getuserdetails');
Route::get('getclass', 'Admin\ApiController@getclass')->name('admin.api.getclass');
Route::get('getsubject/{class_id}', 'Admin\ApiController@getsubject')->name('admin.api.getsubject');
Route::get('getschapter/{subject_id}', 'Admin\ApiController@getchapter')->name('admin.api.getchapter');

Route::get('getvideo/{chapter_id}', 'Admin\ApiController@getvideo')->name('admin.api.getvideo');
Route::get('stremvideo/{paramiter}', 'Admin\ApiController@stremvideo')->name('admin.api.stremvideo');

Route::get('viewvideo/stream/{id}/{token}', 'Admin\ApiController@strem_video')->name('admin.viewvideo.stream');

Route::get('getpdf/{chapter_id}', 'Admin\ApiController@getpdf')->name('admin.api.getpdf');
Route::get('strempdf/{paramiter}', 'Admin\ApiController@strempdf')->name('admin.api.strempdf');




