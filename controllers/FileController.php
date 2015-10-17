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

use HackNet\Models\FileModel;
use Phalcon\Http\Request\Exception;
use Phalcon\Http\Response;
use Phalcon\Mvc\View;
use Phalcon\Tag;

/**
 * File controller
 *
 * @category Game
 * @package  Hacknet
 * @author   Léopold Jacquot <leopold.jacquot@gmail.com>
 * @license  https://opensource.org/licenses/MIT MIT License
 * @link     http://www.hacknet.com
 * @since    1.0.0
 */
class FileController extends MainController
{

    /**
     * Fetch all user files
     *
     * @return null
     */
    public function index()
    {
        $userId = $this->session->get('auth')['id'];

        $files = FileModel::find(
            array(
                "conditions" => "userId = ?1",
                "bind"       => array(1 => $userId),
                "order"      => "fileName",
            )
        );

        $data = array();

        foreach ($files as $file) {
            $data[] = array(
                'id'       => $file->id,
                'fileName' => $file->fileName,
                'userId'   => $file->userId,
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
     * Fetch a user file
     *
     * @param string $fileName File name
     *
     * @throws Exception
     * @return void
     */
    public function get($fileName)
    {

        $userId = $this->session->get('auth')['id'];

        $file = FileModel::findFirst(
            array(
                "conditions" => "userId = ?1 AND fileName = ?2",
                "bind"       => array(1 => $userId, 2 => $fileName, ),
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
                    'fileContent' => $file->fileContent,
                ),
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
    public function put($fileName)
    {
    }

    /**
     * Create a new user file
     *
     * @throws Exception          If duplicate
     * @return Response $response API Response
     */
    public function post()
    {
        $userId = $this->session->get('auth')['id'];

        $postFile = $this->request->getJsonRawBody();

        $file = new FileModel();

        $file->fileName    = $postFile->fileName;
        $file->fileContent = $postFile->fileContent;
        $file->userId      = $userId;

        $response = new Response();

        if (!$file->save()) {
            throw new Exception('Conflit', 401);
        }

        // Change the HTTP status
        $response->setStatusCode(201, "Created");

        $response->setJsonContent(array('status' => 'OK', 'data' => $file, ));

        return $response;
    }

    /**
     * Delete a user file
     *
     * @param string $fileName File name
     *
     * @return void
     */
    public function delete($fileName)
    {
    }
}
