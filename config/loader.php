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
$loader = new \Phalcon\Loader();

$loader->registerNamespaces(
    array(
        'HackNet\Models'      => $config->application->modelsDir,
        'HackNet\Controllers' => $config->application->controllersDir,
    )
)->register()
;

require __DIR__.'/../vendor/autoload.php';
