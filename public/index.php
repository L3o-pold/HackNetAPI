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

use Phalcon\Mvc\Micro;

define('APP_PATH', realpath('..'));

try {
    /**
     * Read the configuration
     */
    $config = include __DIR__.'/../config/config.php';

    /**
     * Include Services
     */
    include APP_PATH.'/config/services.php';

    /**
     * Include Autoloader
     */
    include APP_PATH.'/config/loader.php';

    /**
     * Starting the application
     * Assign service locator to the application
     */
    $app = new Micro($di);

    /**
     * Include Application
     */
    include APP_PATH.'/app.php';

    /**
     * Handle the request
     */
    $app->handle();
} catch (\Exception $e) {
    $code = 400;

    if ($e instanceof Phalcon\Http\Request\Exception) {
        $code = $e->getCode();
    }

    /**
     * Check ENV debug to hide this in production
     */
    $message = $e->getMessage();

    $app->response->setStatusCode(
        $code,
        is_array($message) ? $message[0] : $message
    );

    $app->response->setJsonContent(
        [
            'errors' => [
                [
                    'status' => 'ERROR',
                    'messages' => (array) $message,
                ],
            ],
        ]
    );
    $app->response->send();
}
