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
namespace HackNet\Tests;

use Phalcon\Config;
use Phalcon\DI;
use Phalcon\Di\FactoryDefault;
use Phalcon\DiInterface;
use PHPUnit_Framework_IncompleteTestError;
use PHPUnit_Framework_TestCase;

/**
 * Unit base class
 *
 * @category Game
 * @package  Hacknet
 * @author   Léopold Jacquot <leopold.jacquot@gmail.com>
 * @license  https://opensource.org/licenses/MIT MIT License
 * @link     http://www.hacknet.com
 * @since    1.0.0
 */
abstract class AbstractUnitTestCase extends PHPUnit_Framework_TestCase
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
     * DI
     *
     * @var \Phalcon\DiInterface
     */
    protected $di;

    /**
     * Loaded
     *
     * @var bool
     */
    private $loaded = false;

    /**
     * Sets the test up by loading the DI container and other stuff
     *
     * @param \Phalcon\DiInterface $di     DI
     * @param \Phalcon\Config      $config Configuration
     *
     * @return void
     */
    public function setUp(DiInterface $di = null, Config $config = null)
    {
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
                'url',
                function () {
                    $url = new Url();
                    $url->setBaseUri('/');

                    return $url;
                }
            );

            $di->set(
                'escaper',
                function () {
                    return new \Phalcon\Escaper();
                }
            );
        }

        $this->di = $di;

        $this->loaded = true;
    }

    /**
     * Check if the test case is setup properly
     *
     * @throws \PHPUnit_Framework_IncompleteTestError;
     * @return void
     */
    public function __destruct()
    {
        if (!$this->loaded) {
            throw new PHPUnit_Framework_IncompleteTestError(
                'Please run parent::setUp().'
            );
        }
    }

    /**
     * Checks if a particular extension is loaded and if not it marks
     * the tests skipped
     *
     * @param mixed $extension Extension
     *
     * @return void
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
     * @param string $prefix A prefix for the file
     * @param string $suffix A suffix for the file
     *
     * @return string
     */
    protected function getFileName($prefix = '', $suffix = 'log')
    {
        $prefix = ($prefix) ? $prefix.'_' : '';
        $suffix = ($suffix) ? $suffix : 'log';

        return uniqid($prefix, true).'.'.$suffix;
    }

    /**
     * Removes a file from the system
     *
     * @param string $path     File path
     * @param string $fileName File name
     *
     * @return void
     */
    protected function cleanFile($path, $fileName)
    {
        $file = (substr($path, -1, 1) != "/") ? ($path.'/') : $path;
        $file .= $fileName;

        $actual = file_exists($file);

        if ($actual) {
            unlink($file);
        }
    }

    /**
     * Get DI
     *
     * @return \Phalcon\DiInterface
     */
    protected function getDI()
    {
        return $this->di;
    }

    /**
     * Tear down
     *
     * @return void
     */
    protected function tearDown()
    {
        $di = $this->getDI();
        $di::reset();
        parent::tearDown();
    }
}
