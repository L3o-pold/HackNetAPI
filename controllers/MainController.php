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
use Phalcon\Mvc\Controller;
use Phalcon\Mvc\Model\Message;
use UserApp\Widget\User as UserApp;

/**
 * Main controller
 *
 * @category Game
 * @package  Hacknet
 * @author   Léopold Jacquot <leopold.jacquot@gmail.com>
 * @license  https://opensource.org/licenses/MIT MIT License
 * @link     http://www.hacknet.com
 * @since    1.0.0
 */
class MainController extends Controller
{
    /**
     * Auth an user based on UserApp
     *
     * @return void
     */
    public function onConstruct()
    {

        $this->session->set('auth', null);

        if (!$this->cookies->has('ua_session_token')) {
            throw new HttpException('Forbiden', 401);
        }

        $this->cookies->useEncryption(false);
        $token = $this->cookies->get('ua_session_token');
        $token = $token->getValue();
        $this->cookies->useEncryption(true);

        UserApp::loginWithToken($token);

        $user = $this->getCurrentUser();

        /**
         * User is not registered in our MySQL database
         */
        if (!$user) {
            $user     = new \stdClass();
            $user->id = $this->userRegister();
        }

        $this->session->set('auth', array('id' => $user->id, ));
    }

    /**
     * Get connected user based on UserApp credential
     *
     * @return \Phalcon\Mvc\Model
     */
    private function getCurrentUser()
    {
        return UserModel::findFirst(
            array(
                'conditions' => 'userAppId = ?1',
                'bind'       => array(1 => UserApp::current()->user_id, ),
            )
        );
    }

    /**
     * Register a player
     *
     * @return int       User id
     * @throws HttpException If user can't be registered
     */
    private function userRegister()
    {
        $appUser      = UserApp::current();
        $errorMessage = 'Error during login:';
        $user         = new UserModel();

        $user->name      = $appUser->{'last_name'};
        $user->email     = $appUser->email;
        $user->userAppId = $appUser->{'user_id'};

        if ($user->save()) {
            return $user->id;
        }

        /**
         * Errors during inserting
         *
         * @var Message $message
         */
        foreach ($user->getMessages() as $message) {
            $errorMessage .= ' '.$message->getMessage();
        }

        throw new HttpException($errorMessage, 400);
    }
}
