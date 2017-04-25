<?php
$configuration = [
    [
        'setHandler' => '\UAPI\Controller\IndexCtrl',
        'routes' => [
            'get' => [
                '/' => 'index'
            ]
        ]
    ],
    [
        'setHandler' => '\UAPI\Controller\UsersCtrl',
        'routes' => [
            'get' => [
                '/users/{id:([^?]+)}' => 'get',
                '/users' => 'index'
            ],
            'post' => [
                '/users' => 'insert'
            ],
            'put' => [
                '/users/{id:([^?]+)}' => 'update'
            ],
            'delete' => [
                '/users/{id:([^?]+)}' => 'delete'
            ],
        ]
    ]
];

return $configuration;
