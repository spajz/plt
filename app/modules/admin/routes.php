<?php
$base = 'App\Modules\Admin\Controllers\\';

if (Request::segment(1) == 'admin') {
    define('ADMIN', 'admin');
}

//Route::filter('admin', function () {
//    $not_check = array('admin/login');
//    if (in_array(Route::current()->uri(), $not_check)) return;
//    if (!Sentry::check()) {
//        return Redirect::route('admin.login');
//    }
//});

//Route::when('admin*', 'admin');

Route::get('admin', array(
        'as' => 'admin.index',
        'uses' => $base . 'DashboardController@getIndex'
));
Route::get('admin/dashboard', array(
        'as' => 'admin.dashboard',
        'uses' => $base . 'DashboardController@getIndex'
));
Route::get('admin/logout', array(
        'as' => 'admin.logout',
        'uses' => $base . 'AuthController@getLogout'
));
Route::get('admin/login', array(
        'as' => 'admin.login',
        'uses' => $base . 'AuthController@getLogin'
));
Route::post('admin/login', array(
        'as' => 'admin.login.post',
        'uses' => $base . 'AuthController@postLogin'
));
Route::get('admin/language/{lang}', array(
        'as' => 'admin.language',
        'uses' => $base . 'AdminController@changeLanguage'
));
Route::post('admin/options/{type?}', array(
        'as' => 'admin.options',
        'uses' => $base . 'AdminController@setOptions'
));
Route::get('admin/api/toggle-status/{model}/{id}', array(
        'as' => 'admin.api.toggleStatus',
        'uses' => $base . 'AdminController@toggleStatus'
));
Route::post('admin/api/sort-model', array(
        'as' => 'admin.api.sortModel',
        'uses' => $base . 'AdminController@sortModel'
));
Route::get('admin/api/destroy-model/{model}/{id}', array(
        'as' => 'admin.api.destroyModel',
        'uses' => $base . 'AdminController@destroyModel'
));
Route::get('admin/api/get-model-by-id/{model?}/{id?}/{view?}', array(
        'as' => 'admin.api.getModelById',
        'uses' => $base . 'AdminController@getModelById'
));
Route::get('admin/api/get-model-list/{model?}/{column?}/{key?}', array(
        'as' => 'admin.api.getModelList',
        'uses' => $base . 'AdminController@getModelList'
));
Route::post('admin/api/create-msg', array(
        'as' => 'admin.api.createMsg',
        'uses' => $base . 'AdminController@createMsg'
));
Route::post('admin/api/crop-image', array(
        'as' => 'admin.api.cropImage',
        'uses' => $base . 'AdminController@cropImage'
));

Route::post('admin/api/search-options', array(
        'as' => 'admin.api.searchOptions',
        'uses' => $base . 'AdminController@searchOptions'
));

Route::get('admin/api/search', array(
        'as' => 'admin.api.search',
        'uses' => $base . 'AdminController@search'
));

Route::post('admin/api/add-items-from-search', array(
        'as' => 'admin.api.addItemsFromSearch',
        'uses' => $base . 'AdminController@addItemsFromSearch'
));


Route::get('admin/sentry/{param?}', $base . 'AuthController@sentryAction');
