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
 * @license  http://www.gnu.org/licenses/old-licenses/gpl-2.0.txt MIT License
 * @link     http://www.hacknet.com
 * @since    1.0.0
 */

use Phalcon\Mvc\Model as Model;

/**
 * User Model
 *
 * @category Game
 * @package  Hacknet
 * @author   Léopold Jacquot <leopold.jacquot@gmail.com>
 * @license  http://www.gnu.org/licenses/old-licenses/gpl-2.0.txt MIT License
 * @link     http://www.hacknet.com
 * @since    1.0.0
 */
class UserModel extends Model
{

    /**
     * User id
     *
     * @var int
     */
    public $id;

    /**
     * User name
     *
     * @var string
     */
    public $name;

    /**
     * User email
     *
     * @var string
     */
    public $email;

    /**
     * User App ID
     *
     * @var string
     */
    public $userAppId;

    /**
     * User IP adress
     *
     * @var string
     */
    public $userIp;

    /**
     * Get the SQL table name
     *
     * @return string Table name
     */
    public function getSource()
    {
        return 'computer';
    }

    /**
     * Map field on database table
     *
     * @return array
     */
    public function columnMap()
    {
        return [
            'user_id'    => 'id',
            'user_name'  => 'name',
            'user_email' => 'email',
            'user_appid' => 'userAppId',
            'user_ip'    => 'userIp'
        ];
    }

    /**
     * Declare database relationship
     *
     * @return void
     */
    public function initialize()
    {
        $this->hasMany('id', 'FileModel', 'userId');
    }

    /**
     * Validate user input
     *
     * @return bool Data is valid
     */
    public function validation()
    {
        $this->validate(
            new \Phalcon\Mvc\Model\Validator\Email(
                [
                    'field'          => 'email',
                    'max'            => 50,
                    'min'            => 3,
                    'messageMaximum' => 'We don\'t like really long names',
                    'messageMinimum' => 'We want the full name'
                ]
            )
        );

        $this->validate(
            new \Phalcon\Mvc\Model\Validator\Uniqueness(
                [
                    'field'   => 'email',
                    'message' => "Value of field 'email' is already present in another record"
                ]
            )
        );

        $this->validate(
            new \Phalcon\Mvc\Model\Validator\StringLength(
                [
                    'field'          => 'userAppId',
                    'max'            => 50,
                    'min'            => 3,
                    'messageMaximum' => 'Invalid UserApp id',
                    'messageMinimum' => 'Invalid UserApp id'
                ]
            )
        );

        $this->validate(
            new \Phalcon\Mvc\Model\Validator\Uniqueness(
                [
                    'field'   => 'userAppId',
                    'message' => "Value of field 'userAppId' is already present in another record"
                ]
            )
        );

        return $this->validationHasFailed() != true;
    }
}
