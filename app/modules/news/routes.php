<?php
$base = 'App\Modules\News\Controllers\\';

if (defined('ADMIN')) {
    // Admin

    // News
    Route::group(array('prefix' => ADMIN), function () use ($base) {

        Route::get('news/api/datatable', array(
                'as' => ADMIN . '.news.api.datatable',
                'uses' => $base . 'Admin\NewsController@getDatatable'
        ));

        Route::get('news-group/api/datatable', array(
                'as' => ADMIN . '.newsGroup.api.datatable',
                'uses' => $base . 'Admin\NewsGroupController@getDatatable'
        ));

        Route::resource('news', $base . 'Admin\NewsController');

        Route::resource('news-group', $base . 'Admin\NewsGroupController');

    });

    // Sidebar menu
    navigation('sidemenu', 'news::admin.news._partials.sidemenu');

    navigation('sidemenu', 'news::admin.group._partials.sidemenu');


} else {

    // Frontend

}




