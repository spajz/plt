<?php
$base = 'App\Modules\Product\Controllers\\';

if (defined('ADMIN')) {
    // Admin

    // Brand
    Route::group(array('prefix' => ADMIN), function () use ($base) {
        Route::resource('brand', $base . 'Admin\BrandController');

        Route::get('brand/api/datatable', array(
                'as' => ADMIN . '.brand.api.datatable',
                'uses' => $base . 'Admin\BrandController@getDatatable'
        ));
    });

    // Category
    Route::group(array('prefix' => ADMIN), function () use ($base) {

        Route::get('category/api/datatable', array(
                'as' => ADMIN . '.category.api.datatable',
                'uses' => $base . 'Admin\CategoryController@getDatatable'
        ));

        Route::get('category/api/get-tree/{id?}/{root?}', array(
                'as' => ADMIN . '.category.api.getTree',
                'uses' => $base . 'Admin\CategoryController@getTree'
        ));

        Route::post('category/api/update-tree', array(
                'as' => ADMIN . '.category.api.updateTree',
                'uses' => $base . 'Admin\CategoryController@updateTree'
        ));

        Route::resource('category', $base . 'Admin\CategoryController');
    });

    // Add menu item
    navigation('sidemenu', 'product::admin.category._partials.sidemenu');

    // Add menu item
    navigation('sidemenu', 'product::admin.product._partials.sidemenu');

    // Group
    Route::group(array('prefix' => ADMIN), function () use ($base) {
        Route::resource('group', $base . 'Admin\GroupController');
    });

    // Product
    Route::group(array('prefix' => ADMIN), function () use ($base) {

        Route::get('product/api/datatable/{group_id?}', array(
                'as' => ADMIN . '.product.api.datatable',
                'uses' => $base . 'Admin\ProductController@getDatatable'
        ));

        Route::get('product/api/get-tree/{id?}/{root?}', array(
                'as' => ADMIN . '.product.api.getTree',
                'uses' => $base . 'Admin\ProductController@getTree'
        ));

        Route::post('product/api/update-tree', array(
                'as' => ADMIN . '.product.api.updateTree',
                'uses' => $base . 'Admin\ProductController@updateTree'
        ));

//        Route::post('product/api/search-options', array(
//                'as' => ADMIN . '.product.api.searchOptions',
//                'uses' => $base . 'Admin\ProductController@searchOptions'
//        ));

//        Route::get('product/api/search', array(
//                'as' => ADMIN . '.product.api.search',
//                'uses' => $base . 'Admin\ProductController@search'
//        ));

//        Route::post('product/api/add-items-from-search', array(
//                'as' => ADMIN . '.product.api.addItemsFromSearch',
//                'uses' => $base . 'Admin\ProductController@addItemsFromSearch'
//        ));

        Route::resource('product', $base . 'Admin\ProductController');
    });

} else {

    // Frontend
    Route::get('product', array(
            'as' => 'product.home',
            'uses' => $base . 'ProductController@home'
    ));

    Route::get('category/{slug}/{id}', array(
            'as' => 'product.category',
            'uses' => $base . 'ProductController@showList'
    ));

    Route::get('product/{slug}/{id}', array(
            'as' => 'product',
            'uses' => $base . 'ProductController@showSingle'
    ));

    Route::get('cart/add/{id}/{quantity?}', array(
            'as' => 'cart.add',
            'uses' => $base . 'ProductController@addToCart'
    ));

}




