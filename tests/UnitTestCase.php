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
abstract class UnitTestCase extends \PHPUnit_Framework_TestCase
{

    /**
     * Holds the configuration variables and other stuff
     * I can use the DI container but for tests like the Translate
     * we do not need the overhead
     *
     * @var array
     */
    protected $config = array();

    /**
     * @var \Phalcon\DiInterface
     */
    protected $di;

    /**
     * Cache
     *
     * @var \Voice\Cache
     */
    protected $cache;

    /**
     * Loaded
     *
     * @var bool
     */
    private $_loaded = false;

    /**
     * Sets the test up by loading the DI container and other stuff
     *
     * @param  \Phalcon\DiInterface $di
     * @param  \Phalcon\Config $config
     *
*@return void
     */
    public function setUp(
        Phalcon\DiInterface $di = null, Phalcon\Config $config = null
    ) {
        // Load any additional services that might be required during testing
        $di = DI::getDefault();

        $this->checkExtension('phalcon');

        if (!is_null($config)) {
            $this->config = $config;
        }

        if (is_null($di)) {
            // Reset the DI container
            DI::reset();

            // Instantiate a new DI container
            $di = new FactoryDefault();

            // Set the URL
            $di->set(
                'url', function () {
                $url = new Url();
                $url->setBaseUri('/');

                return $url;
            }
            );

            $di->set(
                'escaper', function () {
                return new \Phalcon\Escaper();
            }
            );
        }

        $this->di = $di;

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

    /**
     * Checks if a particular extension is loaded and if not it marks
     * the tests skipped
     *
     * @param mixed $extension
     */
    public function checkExtension($extension)
    {
        $message = function ($ext) {
            sprintf('Warning: %s extension is not loaded', $ext);
        };

        if (is_array($extension)) {
            foreach ($extension as $ext) {
                if (!extension_loaded($ext)) {
                    $this->markTestSkipped($message($ext));
                    break;
                }
            }
        } elseif (!extension_loaded($extension)) {
            $this->markTestSkipped($message($extension));
        }
    }

    /**
     * Returns a unique file name
     *
     * @author Nikos Dimopoulos <nikos@phalconphp.com>
     * @since  2012-09-30
     *
     * @param  string $prefix A prefix for the file
     * @param  string $suffix A suffix for the file
     *
     * @return string
     */
    protected function getFileName($prefix = '', $suffix = 'log')
    {
        $prefix = ($prefix) ? $prefix . '_' : '';
        $suffix = ($suffix) ? $suffix : 'log';

        return uniqid($prefix, true) . '.' . $suffix;
    }

    /**
     * Removes a file from the system
     *
     * @author Nikos Dimopoulos <nikos@phalconphp.com>
     * @since  2012-09-30
     *
     * @param $path
     * @param $fileName
     */
    protected function cleanFile($path, $fileName)
    {
        $file = (substr($path, -1, 1) != "/") ? ($path . '/') : $path;
        $file .= $fileName;

        $actual = file_exists($file);

        if ($actual) {
            unlink($file);
        }
    }

    /**
     * @return \Phalcon\DiInterface
     */
    protected function getDI()
    {
        return $this->di;
    }

    protected function tearDown()
    {
        $di = $this->getDI();
        $di::reset();
        parent::tearDown();
    }
}