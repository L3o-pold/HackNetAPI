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

use HackNet\Models\ConnectModel;
use Phalcon\Http\Request\Exception as HttpException;
use Phalcon\Http\Response;
use Phalcon\Mvc\View;
use Phalcon\Tag;

/**
 * Connect controller
 *
 * @category Game
 * @package  Hacknet
 * @author   Léopold Jacquot <leopold.jacquot@gmail.com>
 * @license  https://opensource.org/licenses/MIT MIT License
 * @link     http://www.hacknet.com
 * @since    1.0.0
 */
class ConnectController extends MainController
{

    /**
     * Fetch discovered user
     *
     * @return void
     */
    public function index()
    {
        $targets = ConnectModel::query()->columns(array('targetId', 'userIp'))->join(
            'HackNet\Models\UserModel',
            'u.id = targetId',
            'u'
        )->where(
            'u.id = :userId:',
            array(
                'userId' => $this->userId,
            )
        )->execute()
        ;

        $data = array();

        foreach ($targets as $target) {
            $data[] = array(
                'id'     => $target->targetId,
                'userIp' => $target->userIp,
            );
        }

        $this->response->setJsonContent(
            array(
                'status' => 'FOUND',
                'data'   => $data,
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
     * @return void
     */
    public function get($userIp)
    {
        $userController = new UserController();

        $user = $userController->getUser($userIp);

        $lastAttempt = ConnectModel::findFirst(
            array(
                'conditions' => 'userId = ?1 AND targetId = ?2',
                'bind'       => array(
                    1 => $this->userId,
                    2 => $user->id,
                ),
            )
        );

        if (!$lastAttempt) {
            $this->addTarget($user->id);
        }

        $this->response->setJsonContent(
            array(
                'status' => 'FOUND',
                'data'   => array(
                    'id'     => $user->id,
                    'userIp' => $user->userIp,
                ),
            )
        );

        $this->response->send();
    }

    /**
     * Add the target to user connexions
     *
     * @param int $targetId The target id
     *
     * @throws HttpException If we cannot save the connexion
     */
    private function addTarget($targetId)
    {

        $target = new ConnectModel();

        $target->userId   = $this->userId;
        $target->targetId = $targetId;

        if (!$target->save()) {
            throw new HttpException($target->getMessages(), 401);
        }
    }
}
