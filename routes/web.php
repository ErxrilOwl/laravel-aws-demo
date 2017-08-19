<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return redirect(route('aws.s3-upload.index'));
});

Route::group(['prefix' => 'aws', 'namespace' => 'Aws'], function() {
    Route::group(['prefix' => 's3'], function() {
        Route::post('upload', 'S3UploadController')->name('aws.s3.upload.store');
    });
});
