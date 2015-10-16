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

/**
 * Services are globally registered in this file
 *
 * @var \Phalcon\Config $config
 */

use Phalcon\Di\FactoryDefault;
use Phalcon\Mvc\Dispatcher;
use Phalcon\Mvc\Url as UrlResolver;
use Phalcon\Mvc\View\Simple as View;

$di = new FactoryDefault();

/**
 * Sets the view component
 */
$di->setShared(
    'view', function () use ($config) {
        $view = new View();
        $view->setViewsDir($config->application->viewsDir);
        return $view;
    }
);

/**
 * The URL component is used to generate all kind of urls in the application
 */
$di->setShared(
    'url', function () use ($config) {
        $url = new UrlResolver();
        $url->setBaseUri($config->application->baseUri);
        return $url;
    }
);

/**
 * Database connection is created based in the parameters defined
 * in the configuration file
 */
$di->setShared(
    'db', function () use ($config) {
        $dbConfig = $config->database->toArray();
        $adapter = $dbConfig['adapter'];
        unset($dbConfig['adapter']);

        $class = 'Phalcon\Db\Adapter\Pdo\\' . $adapter;

        return new $class($dbConfig);
    }
);

$di->setShared(
    'crypt', function () use ($di, $config) {
        $crypt = new \Phalcon\Crypt();
        $crypt->setMode(MCRYPT_MODE_CFB);
        $crypt->setKey($config->application->salt);

        return $crypt;
    }
);

$di->set('config', $config, true);