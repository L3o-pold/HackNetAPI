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

use Phalcon\DI;
use Phalcon\Test\UnitTestCase as PhalconTestCase;

/**
 * Unit base clss
 *
 * @category Game
 * @package  Hacknet
 * @author   Léopold Jacquot <leopold.jacquot@gmail.com>
 * @license  http://www.gnu.org/licenses/old-licenses/gpl-2.0.txt MIT License
 * @link     http://www.hacknet.com
 * @since    1.0.0
 */
abstract class UnitTestCase extends PhalconTestCase
{
    /**
     * Cache
     *
     * @var \Voice\Cache
     */
    protected $cache;

    /**
     * Config
     *
     * @var \Phalcon\Config
     */
    protected $config;

    /**
     * Loaded
     *
     * @var bool
     */
    private $_loaded = false;

    /**
     * Init test
     *
     * @param \Phalcon\DiInterface|null $di     Dependence injector
     * @param \Phalcon\Config|null      $config Application configuration
     *
     * @return void
     */
    public function setUp(
        Phalcon\DiInterface $di = null, Phalcon\Config $config = null
    ) {
        // Load any additional services that might be required during testing
        $di = DI::getDefault();

        parent::setUp($di);

        $this->_loaded = true;
    }

    /**
     * Check if the test case is setup properly
     *
     * @throws \PHPUnit_Framework_IncompleteTestError;
     * @return void
     */
    public function __destruct()
    {
        if (!$this->_loaded) {
            throw new \PHPUnit_Framework_IncompleteTestError(
                'Please run parent::setUp().'
            );
        }
    }
}