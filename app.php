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
 * Local variables
 *
 * @var \Phalcon\Mvc\Micro $app
 */
use Phalcon\Http\Request\Exception;
use Phalcon\Mvc\Micro\Collection as MicroCollection;
use UserApp\Widget\User as UserApp;

//$response = $app->response;
//$response->setHeader('Access-Control-Allow-Origin', '*');
//$response->setHeader('Access-Control-Allow-Headers', 'X-Requested-With');
//$response->sendHeaders();

$app->response->setContentType('application/json', 'UTF-8');

UserApp::setAppId($config->oauth->appId);

$users = new MicroCollection();
$users->setHandler(new UserController());
$users->setPrefix('/user');
$users->get('/', 'indexAction');
$users->get('/{id:[0-9]+}', 'getAction');
$users->post('/', 'postAction');
$users->put('/{id:[0-9]+}', 'putAction');
$users->delete('/{id:[0-9]+}', 'deleteAction');

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

$app->before(
    function () use ($app) {

        $authenticated = UserApp::authenticated();

        if ($authenticated) {
            return true;
        }

        if ($app->cookies->has('ua_session_token')) {
            $app->cookies->useEncryption(false);
            $token = $app->cookies->get('ua_session_token');
            $token = $token->getValue();
            $app->cookies->useEncryption(true);
        } else {
            throw new Exception('Forbiden', 401);
        }

        return UserApp::loginWithToken($token);
    }
);

$app->error(
    function ($exception) use ($app) {
        $message = 'Bad Request';
        $code    = 400;

        if ($exception instanceof Exception) {
            $message = $exception->getMessage();
            $code    = $exception->getCode();
        } else {
            $message = $exception->getMessage();
        }

        $app->response->setStatusCode($code, $message);

        $app->response->setJsonContent(
            [
            'errors' => [
            [
                'status'   => 'ERROR',
                'messages' => (array) $message
            ]
            ]
            ]
        );
        $app->response->send();
    }
);
