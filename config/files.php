<?php

return [

    'main_folder' => 'media',

    'models' =>[],

    'file'=>[
        'is_image' => true,
        'thumb' => [
            'activate' => true,
            'height' => 100,
            'width' => 100,
        ],
        'resize' => [
            'activate' => true,
            'height' => 1000,
            'width' => 1000,
        ],
    ],

    'media' => [
        'is_image' => true,
        'activate' => true,
        'resize' => [
            'activate' => true,
            'height' => 900,
            'width' => 900,
        ],
        'thumb' => [
            'activate' => false,
            'height' => 100,
            'width' => 100,
        ],
    ],

];
