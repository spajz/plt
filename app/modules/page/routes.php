<?php
$base = 'App\Modules\Page\Controllers\\';

if (defined('ADMIN')) {
    // Admin

    // Page
    Route::group(array('prefix' => ADMIN), function () use ($base) {

        Route::get('page/api/datatable', array(
                'as' => ADMIN . '.page.api.datatable',
                'uses' => $base . 'Admin\PageController@getDatatable'
        ));

        Route::get('page/api/get-tree/{id?}/{root?}', array(
                'as' => ADMIN . '.page.api.getTree',
                'uses' => $base . 'Admin\PageController@getTree'
        ));

        Route::post('page/api/update-tree', array(
                'as' => ADMIN . '.page.api.updateTree',
                'uses' => $base . 'Admin\PageController@updateTree'
        ));

        Route::resource('page', $base . 'Admin\PageController');

    });

    // Sidebar menu
    navigation('sidemenu', 'page::admin.page._partials.sidemenu');

} else {

    // Frontend
    Route::get('/', array(
            'as' => 'page.home',
            'uses' => $base . 'HomeController@index'
    ));


}




