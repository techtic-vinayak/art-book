<?php

// --------------------------
// Custom Backpack Routes
// --------------------------
// This route file is loaded automatically by Backpack\Base.
// Routes you generate using Backpack\Generators will be placed here.

Route::group([
    'prefix'     => config('backpack.base.route_prefix', 'admin'),
    'middleware' => ['web', config('backpack.base.middleware_key', 'admin')],
    'namespace'  => 'App\Http\Controllers\Admin',
], function () {
    // custom admin routes
    Route::get('report-admin/reject/{id}', 'ReportAdminCrudController@reject');

    Route::get('report-admin/approve/{id}', 'ReportAdminCrudController@approve');
    CRUD::resource('users', 'UserCrudController');
    CRUD::resource('videoreport', 'VideoReportCrudController');
    CRUD::resource('video', 'VideoCrudController');
    CRUD::resource('paintingsize', 'PaintingSizeCrudController');
    CRUD::resource('category', 'CategoryCrudController');
    CRUD::resource('art', 'ArtCrudController');
    CRUD::resource('report-admin', 'ReportAdminCrudController');
}); // this should be the absolute last line of this file
