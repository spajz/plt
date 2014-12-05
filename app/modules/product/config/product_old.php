<?php

return array(
        'images' => array(
                'path' => public_path() . '/media/images/',
                'required' => false,
                'multiple' => true,
                'validator' => 'mimes:jpeg,jpg,png,gif|image|max:2000000', // don't use required
                'filetype' => 'jpg',
                'sizes' => array(
                        'original' => array( // required
                                'bgcolor' => '#fff',
                                'quality' => 75,
                                'actions' => array(),
                        ),
                        'thumb' => array(
                                'bgcolor' => '#fff',
                                'quality' => 75,
                                'actions' => array(
                                        'fit' => array(100, 80),
                                )
                        ),
                        'large' => array(
                                'bgcolor' => '#fff',
                                'quality' => 75,
                                'actions' => array(
                                        'resize' => array(800, 600),
                                )
                        ),
                ),
        )
);
