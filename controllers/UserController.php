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
namespace HackNet\Controllers;

use HackNet\Models\UserModel;
use Phalcon\Http\Request\Exception as HttpException;

/**
 * User controller
 *
 * @category Game
 * @package  Hacknet
 * @author   Léopold Jacquot <leopold.jacquot@gmail.com>
 * @license  https://opensource.org/licenses/MIT MIT License
 * @link     http://www.hacknet.com
 * @since    1.0.0
 */
class UserController extends MainController
{

    /**
     * Fetch an user
     *
     * @param string $userIp The user ip address
     *
     * @throws HttpException
     * @return void
     */
    public function get($userIp)
    {
        $user = $this->getUser($userIp);

        $this->response->setJsonContent(
            array(
                'status' => 'FOUND',
                'data'   => array(
                    'id'        => $user->id,
                    'name'      => $user->name,
                    'userAppId' => $user->userAppId,
                    'userIp'    => $user->userIp,
                ),
            )
        );

        $this->response->send();
    }

    /**
     * Fetch an user
     *
     * @param string $userIp The user ip address
     *
     * @throws HttpException
     * @return \Phalcon\Mvc\Model
     */
    public function getUser($userIp)
    {
        $user = UserModel::findFirst(
            array(
                'conditions' => 'userIp = ?1',
                'bind'       => array(
                    1 => $userIp,
                ),
            )
        );

        if (!$user) {
            throw new HttpException('Not Found', 404);
        }

        return $user;
    }
}
