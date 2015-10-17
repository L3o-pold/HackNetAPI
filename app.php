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
use UserApp\Widget\User as UserApp;

$app->response->setContentType('application/json', 'UTF-8');

require __DIR__ . '/config/routing.php';

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
