<?php

use Phalcon\Http\Request\Exception;
use Phalcon\Http\Response;
use Phalcon\Mvc\View;
use Phalcon\Tag;

/**
 * @package FileController
 * @author  Léopold Jacquot
 */
class FileController extends MainController {

    /**
     * @TODO Fetch userId from DB with appID credentials
     */
    public function indexAction() {

        $userId = 1;

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
     * @param     $fileName
     */
    public function getAction($fileName) {

        $userId = 1;

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
     * @param     $fileName
     */
    public function putAction($fileName) {
        $userId = 1;
    }

    public function postAction() {

        $userId = 1;

        $file = $this->request->getJsonRawBody();

        /**
         * @TODO Check if Phalcon allow a better way
         */
        $phql
            = "INSERT INTO fileModel (id, fileName, fileContent, userId) VALUES (null, :fileName:, :fileContent:, :userId:)";

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

        $response->setJsonContent(array('status' => 'OK', 'data'   => $file));

        return $response;
    }

    /**
     * @TODO implement
     * @param     $fileName
     */
    public function deleteAction($fileName) {
    }
}