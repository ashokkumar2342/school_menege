<?php

// Route::get('/', function () {
//     return redirect()->route('admin.login');
 
// });
Route::get('/', function () {
    return redirect()->route('admin.login');
 	// return view('admin.auth.login');
});

Route::get('getvideo', 'Admin\ApiController@getvideo')->name('admin.api.getvideo');
Route::get('stremvideo/{paramiter}', 'Admin\ApiController@stremvideo')->name('admin.api.stremvideo');




