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

use Phalcon\Mvc\Model as Model;

/**
 * File model
 * @category Game
 * @package  Hacknet
 * @author   Léopold Jacquot <leopold.jacquot@gmail.com>
 * @license  http://www.gnu.org/licenses/old-licenses/gpl-2.0.txt MIT License
 * @link     http://www.hacknet.com
 * @since    1.0.0
 */
class FileModel extends Model
{

    /**
     * File id
     * @var int
     */
    public $id;

    /**
     * File name
     * @var string
     */
    public $fileName;

    /**
     * File content
     * @var string
     */
    public $fileContent;

    /**
     * User id
     * @var int
     */
    public $userId;

    /**
     * Get the SQL table name
     *
     * @return string Table name
     */
    public function getSource()
    {
        return 'file';
    }


    /**
     * Validate user input
     *
     * @return bool Data is valid
     */
    public function validation()
    {
        $this->validate(
            new \Phalcon\Mvc\Model\Validator\StringLength(
                [
                'field'          => 'fileName',
                'max'            => 50,
                'min'            => 1,
                'messageMaximum' => 'We don\'t like really long fileName',
                'messageMinimum' => 'We want a longer fileName'
                ]
            )
        );

        /**
         * Note
         * @TODO Check userId and fileContent
         */
        return $this->validationHasFailed() != true;
    }
}
