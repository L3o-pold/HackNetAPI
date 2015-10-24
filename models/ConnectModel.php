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
namespace HackNet\Models;

use Phalcon\Mvc\Model as Model;

/**
 * Connect Model
 *
 * @category Game
 * @package  Hacknet
 * @author   Léopold Jacquot <leopold.jacquot@gmail.com>
 * @license  https://opensource.org/licenses/MIT MIT License
 * @link     http://www.hacknet.com
 * @since    1.0.0
 */
class ConnectModel extends Model
{

    /**
     * User id
     *
     * @var int
     */
    public $userId;

    /**
     * Target user id
     *
     * @var int
     */
    public $targetId;

    /**
     * Get the SQL table name
     *
     * @return string Table name
     */
    public function getSource()
    {
        return 'discover';
    }

    /**
     * Map field on database table
     *
     * @return array<string,string> Mapped field
     */
    public function columnMap()
    {
        return [
            'user_id'     => 'userId',
            'com_user_id' => 'targetId',
        ];
    }

    /**
     * Declare database relationship
     *
     * @return void
     */
    public function initialize()
    {
        $this->belongsTo('userId', 'HackNet\\Models\\UserModel', 'id');
        $this->belongsTo('targetId', 'HackNet\\Models\\UserModel', 'id');
    }
}
