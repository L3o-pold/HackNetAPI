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

use Phalcon\Http\Request\Exception;
use Phalcon\Http\Response;
use Phalcon\Mvc\View;
use Phalcon\Tag;
use UserApp\Widget\User as UserApp;

/**
 * File controller
 *
 * @category Game
 * @package  Hacknet
 * @author   Léopold Jacquot <leopold.jacquot@gmail.com>
 * @license  http://www.gnu.org/licenses/old-licenses/gpl-2.0.txt MIT License
 * @link     http://www.hacknet.com
 * @since    1.0.0
 */
class FileController extends MainController
{

    /**
     * Fetch all user files
     *
     * @TODO   Fetch userId from DB with appID credentials
     * @return null
     */
    public function indexAction()
    {
        $userId = $this->session->get('auth')['id'];

        $files = FileModel::find(
            array(
                "conditions" => "userId = ?1",
                "bind"       => array(1 => $userId),
                "order"      => "fileName"
            )
        );

        $data = array();

        foreach ($files as $file) {
            $data[] = array(
                'id'       => $file->id,
                'fileName' => $file->fileName,
                'userId'   => $file->userId
            );
        }

        $this->response->setJsonContent(
            array(
                'status' => 'FOUND',
                'data'   => $data
            )
        );

        $this->response->send();
    }

    /**
     * Fetch a user file
     *
     * @param string $fileName File name
     *
     * @throws Exception
     * @return void
     */
    public function getAction($fileName)
    {

        $userId = $this->session->get('auth')['id'];

        $file = FileModel::findFirst(
            array(
                "conditions" => "userId = ?1 AND fileName = ?2",
                "bind"       => array(1 => $userId, 2 => $fileName)
            )
        );

        if ($file == false) {
            throw new Exception('Not Found', 404);
        }

        $this->response->setJsonContent(
            array(
                'status' => 'FOUND',
                'data'   => array(
                    'id'          => $file->id,
                    'fileName'    => $file->fileName,
                    'userId'      => $file->userId,
                    'fileContent' => $file->fileContent
                )
            )
        );

        $this->response->send();
    }

    /**
     * Update a user file
     *
     * @param string $fileName File name
     *
     * @return void
     */
    public function putAction($fileName)
    {
        $userId = $this->session->get('auth')['id'];
    }

    /**
     * Create a new user file
     *
     * @throws Exception          If duplicate
     * @return Response $response API Response
     */
    public function postAction()
    {
        $userId = $this->session->get('auth')['id'];

        $file = $this->request->getJsonRawBody();

        /**
         * Note
         * @TODO Check if Phalcon allow a better way
         */
        $phql
            = "INSERT INTO fileModel (id, fileName, fileContent, userId) "
              . "VALUES (null, :fileName:, :fileContent:, :userId:)";

        $status = $this->modelsManager->executeQuery(
            $phql, array(
                'fileName'    => $file->fileName,
                'fileContent' => $file->fileContent,
                'userId'      => $userId
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

        $file->id = $status->getModel()->id;

        $response->setJsonContent(array('status' => 'OK', 'data' => $file));

        return $response;
    }

    /**
     * Delete a user file
     *
     * @param string $fileName File name
     *
     * @return void
     */
    public function deleteAction($fileName)
    {
    }
}
