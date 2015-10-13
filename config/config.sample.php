<?php

defined('APP_PATH') || define('APP_PATH', realpath('.'));

return new \Phalcon\Config(
    [
        'database'    => [
            'adapter'  => 'Mysql',
            'host'     => 'localhost',
            'username' => 'root',
            'password' => '',
            'dbname'   => '',
            'charset'  => 'utf8',
        ],
        'application' => [
            'baseUri'        => '/api/',
            'salt'           => '',
            'controllersDir' => APP_PATH . '/controllers/',
            'modelsDir'      => APP_PATH . '/models/',
            'pluginsDir'     => APP_PATH . '/plugins/',
            'migrationsDir'  => APP_PATH . '/migrations/',
            'viewsDir'       => APP_PATH . '/views/',
            'cacheDir'       => APP_PATH . '/cache/'
        ],
        'oauth'       => [
            'appId'    => '',
            'appToken' => ''
        ]
    ]
);
