<?php
$base = 'App\Modules\Comment\Controllers\\';

if (defined('ADMIN')) {
    // Admin

    // News
    Route::group(array('prefix' => ADMIN), function () use ($base) {

        Route::get('comment/api/datatable', array(
                'as' => ADMIN . '.comment.api.datatable',
                'uses' => $base . 'Admin\CommentController@getDatatable'
        ));

        Route::resource('comment', $base . 'Admin\CommentController');
    });

    // Sidebar menu
    navigation('sidemenu', 'comment::admin.comment._partials.sidemenu');

} else {

    // Frontend

}




