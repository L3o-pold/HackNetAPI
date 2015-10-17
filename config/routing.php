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

use HackNet\Controllers\FileController;
use Phalcon\Http\Request\Exception;
use Phalcon\Mvc\Micro\Collection as MicroCollection;
use UserApp\Widget\User as UserApp;

/**
 * Local variables
 *
 * @var \Phalcon\Mvc\Micro $app
 */
UserApp::setAppId($config->oauth->appId);

$files = new MicroCollection();
$files->setHandler(new FileController());
$files->setPrefix('/file');
$files->get('/', 'index');
$files->get('/{id}', 'get');
$files->post('/', 'post');
$files->put('/{id}', 'put');
$files->delete('/{id}', 'delete');

$app->mount($files);

$app->notFound(
    function () use ($app) {
        throw new Exception('Not Found', 404);
    }
);

$app->error(
    function ($exception) use ($app) {
        $code = 400;

        if ($exception instanceof Exception) {
            $code = $exception->getCode();
        }

        $message = $exception->getMessage();

        $app->response->setStatusCode($code, $message);

        $app->response->setJsonContent(
            [
                'errors' => [
                    [
                        'status'   => 'ERROR',
                        'messages' => (array) $message,
                    ],
                ],
            ]
        );
        $app->response->send();
    }
);
