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

use Phalcon\Mvc\Controller;
use UserApp\Widget\User as UserApp;

/**
 * Main controller
 * @category Game
 * @package  Hacknet
 * @author   Léopold Jacquot <leopold.jacquot@gmail.com>
 * @license  http://www.gnu.org/licenses/old-licenses/gpl-2.0.txt MIT License
 * @link     http://www.hacknet.com
 * @since    1.0.0
 */
class MainController extends Controller
{
    /**
     * Auth an user based on UserApp
     *
     * @return void
     * @throws Exception If login fail
     */
    public function onConstruct() 
    {

        $user = $this->_getCurrentUser();

        /**
         * User is not registered in our MySQL database
         */
        if (!$user) {
            $user->id = $this->_userRegister();
        }

        $this->session->set(
            'auth',
            array(
                'id'   => $user->id
            )
        );
    }

    /**
     * Get connected user based on UserApp credential
     *
     * @return \Phalcon\Mvc\Model
     */
    private function _getCurrentUser()
    {
        return UserModel::findFirst(
            array(
                "conditions" => "userAppId = ?1",
                "bind"       => array(1 => UserApp::current()->user_id)
            )
        );
    }

    /**
     * Register a player
     *
     * @return int       User id
     * @throws Exception If user can't be registered
     */
    private function _userRegister()
    {
        $phql
            = "INSERT INTO userModel (id, name, email, userAppId) "
              . "VALUES (null, :name:, :email:, :userAppId:)";

        $appUser = UserApp::current();

        /**
         * Result of insertion
         *
         * @var Phalcon\Mvc\Model\Query\Status $status
         */
        $status = $this->modelsManager->executeQuery(
            $phql, array(
                'name'       => $appUser->last_name,
                'email'      => $appUser->email,
                'userAppId' => $appUser->user_id
            )
        );
        
        if ($status->success()) {
            return $status->getModel()->id;
        }

        $errorMessage = 'Error during login:';

        /**
         * Errors during inserting
         *
         * @var Phalcon\Mvc\Model\Message $message
         */
        foreach ($status->getMessages() as $message) {
            $errorMessage .= ' ' . $message->getMessage();
        }

        throw new Exception($errorMessage, 400);
    }
}
