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
use Phalcon\Http\Request\Exception as HttpException;
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
                'conditions' => 'userId = ?1',
                'bind'       => array(1 => $userId),
                'order'      => 'fileName',
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
     * @throws HttpException
     * @return void
     */
    public function get($fileName)
    {

        $userId = $this->session->get('auth')['id'];

        $file = $this->getUserFile($userId, $fileName);

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
        $userId = $this->session->get('auth')['id'];

        $file = $this->getUserFile($userId, $fileName);

        $postFile = $this->request->getJsonRawBody();

        $file->fileContent = $postFile->fileContent;

        if (!$file->save()) {
            throw new HttpException($file->getMessages(), 401);
        }

        // Change the HTTP status
        $this->response->setStatusCode(201, 'Edited');

        $this->response->setJsonContent(
            array(
                'status' => 'OK',
                'data'   => $file,
            )
        );

        $this->response->send();
    }

    /**
     * Create a new user file
     *
     * @throws HttpException          If duplicate
     * @return void
     */
    public function post()
    {
        $userId = $this->session->get('auth')['id'];

        $postFile = $this->request->getJsonRawBody();

        $file = new FileModel();

        $file->fileName    = $postFile->fileName;
        $file->fileContent = $postFile->fileContent;
        $file->userId      = $userId;

        if (!$file->save()) {
            throw new HttpException($file->getMessages(), 401);
        }

        // Change the HTTP status
        $this->response->setStatusCode(201, 'Created');

        $this->response->setJsonContent(
            array(
                'status' => 'OK',
                'data'   => $file,
            )
        );

        $this->response->send();
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
        $userId = $this->session->get('auth')['id'];

        $file = $this->getUserFile($userId, $fileName);

        if ($file->delete() == false) {
            throw new HttpException($file->getMessages(), 401);
        }

        $this->response->setJsonContent(
            array(
                'status' => 'OK',
            )
        );

        $this->response->send();
    }

    /**
     * Get a user file
     *
     * @param int    $userId   The user id
     * @param string $fileName The requested file name
     *
     * @return \Phalcon\Mvc\Model The file requested
     * @throws HttpException      If user isn't authorized or file doesn't exist
     */
    private function getUserFile($userId, $fileName)
    {
        $file = FileModel::findFirst(
            array(
                'conditions' => 'userId = ?1 AND fileName = ?2',
                'bind'       => array(
                    1 => $userId,
                    2 => $fileName,
                ),
            )
        );

        if ($file == false) {
            throw new HttpException('Not Found', 404);
        }

        return $file;
    }
}
