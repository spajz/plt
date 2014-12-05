<?php

return array(
    // Package root path
	'path'              => rtrim(dirname(dirname(__DIR__)), DS).DS ,

    'publicpath'        => public_path().DS.'packages'.DS.'spajz'.DS.'admin'.DS,

    'assets'            => 'packages/spajz/admin/',

    'admin_language'  => 'en',

    'admin_languages' => array(
        'en'            => __('admin::admin.english'),
        'sr'            => __('admin::admin.serbian'),
    ),

    'bulk_actions'      => array(
        ''              => __('admin::admin.select'),
        'edit'          => __('admin::admin.edit'),
        'delete'        => __('admin::admin.delete'),
        'activate'      => __('admin::admin.activate'),
        'deactivate'    => __('admin::admin.deactivate'),
    ),

    'assets' => array(
        'workbench/spajz/admin/public/assets',
        'workbench/spajz/admin/public/assets/ckeditor',
        'workbench/spajz/admin/public/assets/ckeditor/adapters',
        'workbench/spajz/admin/public/assets/css',
        'workbench/spajz/admin/public/assets/font',
        'workbench/spajz/admin/public/assets/img',
        'workbench/spajz/admin/public/assets/js',
        'workbench/spajz/admin/public/assets/js/dynatree',
        'workbench/spajz/admin/public/assets/js/dynatree/skin-vista',
    )

);
