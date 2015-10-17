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

use Phalcon\Mvc\Micro\Collection as MicroCollection;
use UserApp\Widget\User as UserApp;

/**
 * Local variables
 *
 * @var \Phalcon\Mvc\Micro $app
 */
UserApp::setAppId($config->oauth->appId);

$users = new MicroCollection();
$users->setHandler(new UserController());
$users->setPrefix('/user');
$users->get('/', 'indexAction');
$users->get('/{id}', 'getAction');
$users->post('/', 'postAction');
$users->put('/{id}', 'putAction');
$users->delete('/{id}', 'deleteAction');

$files = new MicroCollection();
$files->setHandler(new FileController());
$files->setPrefix('/file');
$files->get('/', 'indexAction');
$files->get('/{id}', 'getAction');
$files->post('/', 'postAction');
$files->put('/{id}', 'putAction');
$files->delete('/{id}', 'deleteAction');

$app->mount($users);
$app->mount($files);

$app->notFound(
    function () use ($app) {
        throw new Exception('Not Found', 404);
    }
);