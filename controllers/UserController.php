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
                'email' => $user->login
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
    public function getAction($userId)
    {
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
