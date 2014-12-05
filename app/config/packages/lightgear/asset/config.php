<?php

return array(

    /**
     * The paths where the assets will be searched in,
     * relative to the project root
     */
    'search_paths' => array(
        '/workbench',
        '/vendor',
        '/app/assets',
        '/workbench/spajz/admin/public/assets'
    ),

    /**
     * The directory, relative to "public" where the assets will
     * be published to.
     */
    'public_dir' => 'packages/spajz/admin',

    /**
     * Turn on/off assets minification.
     */
    'minify' => false,

    /**
     * The patterns for minified assets.
     * If an asset filename contains one of these patterns,
     * then it will be considered already minified and skip
     * the minification process.
     * Ex. jquery.min.js, yui-min.js
     */
    'minify_patterns' => array('-min.', '.min.'),

    /**
     * Turn on/off assets merge.
     */
    'combine' => false,

    /**
     * The filename of the combined styles.
     */
    'combined_styles' => 'application.css',

    /**
     * The filename of the combined scripts.
     */
    'combined_scripts' => 'application.js',

    /**
     * Turn on/off assets caching.
     */
    'use_cache' => false,

);
