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

use Phalcon\Http\Response;
use Phalcon\Mvc\View;
use Phalcon\Tag;
use UserApp\API;
use UserApp\Widget\User as UserApp;

/**
 * User controller
 *
 * @category Game
 * @package  Hacknet
 * @author   Léopold Jacquot <leopold.jacquot@gmail.com>
 * @license  http://www.gnu.org/licenses/old-licenses/gpl-2.0.txt MIT License
 * @link     http://www.hacknet.com
 * @since    1.0.0
 */
class UserController extends MainController
{

    /**
     * Fetch all users
     *
     * @return void
     */
    public function indexAction()
    {
        $config = $this->getDI()->get('config');

        $api = new API($config->oauth->appId, $config->oauth->appToken);

        $users = $api->user->search();

        $data = [];
        foreach ($users->items as $user) {
            $data[] = [
                'id'    => $user->user_id,
                'name'    => $user->first_name,
                'email' => $user->login,
                'userAppId' => $user->user_id
            ];
        }

        echo json_encode((object) $data);
    }

    /**
     * Fetch an user
     *
     * @param int $userId User id
     *
     * @return void
     */
    public function getAction($userAppId)
    {
        $user = UserModel::findFirst(
            array(
                "conditions" => "userAppId = ?1",
                "bind"       => array(1 => $userAppId)
            )
        );

        if ($user == false) {
            throw new Exception('Not Found', 404);
        }

        $this->response->setJsonContent(
            array(
                'status' => 'FOUND',
                'data'   => array(
                    'id'          => $user->id,
                    'name'    => $user->name,
                    'email'      => $user->email,
                    'userAppId' => $user->userAppId
                )
            )
        );

        $this->response->send();
    }

    /**
     * Update an user
     *
     * @param int $userId User id
     *
     * @return void
     */
    public function putAction($userId)
    {
    }

    /**
     * Create a new user
     *
     * @return void
     */
    public function postAction()
    {
        $user = $this->request->getJsonRawBody();

        /**
         * Note
         * @TODO Check if Phalcon allow a better way
         */
        $phql
            = "INSERT INTO userModel (id, name, email, userAppId) "
              . "VALUES (null, :name:, :email:, :userAppId:)";

        $status = $this->modelsManager->executeQuery(
            $phql, array(
                'name'    => $user->name,
                'email' => $user->email,
                'userAppId'      => UserApp::current()->user_id
            )
        );

        // Create a response
        $response = new Response();

        // Check if the insertion was successful
        if (!$status->success()) {
            throw new Exception('Conflit', 401);
        }

        // Change the HTTP status
        $response->setStatusCode(201, "Created");

        $user->id = $status->getModel()->id;

        $response->setJsonContent(array('status' => 'OK', 'data' => $user));

        return $response;
    }

    /**
     * Delete an user
     *
     * @param int $userId User id
     *
     * @return void
     */
    public function deleteAction($userId)
    {
    }
}
