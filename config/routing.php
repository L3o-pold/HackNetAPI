<?php

/**
 * HackNet
 * Licensed under The MIT License (MIT)
 * For full copyright and license information, please see the LICENSE
 * Redistributions of files must retain the above copyright notice.
 * PHP version 5
 *
 * @category Game
 * @package  Hacknet
 * @author   Léopold Jacquot <leopold.jacquot@gmail.com>
 * @license  https://opensource.org/licenses/MIT MIT License
 * @link     http://www.hacknet.com
 * @since    1.0.0
 */

use HackNet\Controllers\ConnectController;
use HackNet\Controllers\FileController;
use HackNet\Controllers\UserController;
use Phalcon\Http\Request\Exception as HttpException;
use Phalcon\Mvc\Micro\Collection as MicroCollection;
use UserApp\Widget\User as UserApp;

/**
 * Local variables
 *
 * @var \Phalcon\Mvc\Micro $app
 */
$app->notFound(
    function () use ($app) {
        throw new HttpException('Not Found', 404);
    }
);

UserApp::setAppId($config->oauth->appId);

$files = new MicroCollection();
$files->setHandler(new FileController());
$files->setPrefix('/file');
$files->get('/', 'index');
$files->get('/{id}', 'get');
$files->post('/', 'post');
$files->put('/{id}', 'put');
$files->delete('/{id}', 'delete');

$users = new MicroCollection();
$users->setHandler(new UserController());
$users->setPrefix('/user');
$users->get('/{ip}', 'get');

$connexions = new MicroCollection();
$connexions->setHandler(new ConnectController());
$connexions->setPrefix('/connect');
$connexions->get('/', 'index');
$connexions->get('/{id}', 'get');

$app->mount($files);
$app->mount($users);
$app->mount($connexions);
