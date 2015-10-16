<?php

/**
 * HackNet
 *
 * Licensed under The MIT License (MIT)
 * For full copyright and license information, please see the LICENSE
 * Redistributions of files must retain the above copyright notice.
 *
 * PHP version 5
 *
 * @category Game
 * @package  Hacknet
 * @author   Léopold Jacquot <leopold.jacquot@gmail.com>
 * @license  http://www.gnu.org/licenses/old-licenses/gpl-2.0.txt MIT License
 * @link     http://www.hacknet.com
 * @since    1.0.0
 */
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
