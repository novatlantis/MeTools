<?php
/**
 * This file is part of me-tools.
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright   Copyright (c) Mirko Pagliai
 * @link        https://github.com/mirko-pagliai/me-tools
 * @license     https://opensource.org/licenses/mit-license.php MIT License
 * @since       2.14.0
 */
namespace MeTools\TestSuite;

use Cake\Core\Configure;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase as CakeTestCase;
use Exception;
use MeTools\TestSuite\MockTrait;
use Symfony\Component\Filesystem\Filesystem;
use Tools\ReflectionTrait;
use Tools\TestSuite\TestTrait;

/**
 * TestCase class
 */
abstract class TestCase extends CakeTestCase
{
    use MockTrait;
    use ReflectionTrait;
    use TestTrait;

    /**
     * Called before every test method
     * @return void
     */
    public function setUp()
    {
        parent::setUp();

        $this->loadPlugins(Configure::read('pluginsToLoad') ?: ['MeTools']);
    }

    /**
     * Called after every test method
     * @return void
     */
    public function tearDown()
    {
        parent::tearDown();

        try {
            if (LOGS !== TMP) {
                unlink_recursive(LOGS, 'empty');
            }
            unlink_recursive(WWW_ROOT . 'vendor', 'empty');
            unlink(WWW_ROOT . 'me_tools');
            unlink(WWW_ROOT . 'robots.txt');
        } catch (Exception $e) {
        }
    }

    /**
     * Internal method to get a log full path
     * @param string $filename Log filename
     * @return string
     * @since 2.16.10
     */
    protected function getLogFullPath($filename)
    {
        if (!pathinfo($filename, PATHINFO_EXTENSION)) {
            $filename .= '.log';
        }

        return Filesystem::isAbsolutePath($filename) ? $filename : LOGS . $filename;
    }

    /**
     * Get a table instance from the registry
     * @param string $alias The alias name you want to get
     * @param array $options The options you want to build the table with
     * @return \Cake\ORM\Table|null
     * @since 2.18.11
     */
    protected function getTable($alias, array $options = [])
    {
        if ($alias === 'App' || (isset($options['className']) && !class_exists($options['className']))) {
            return null;
        }

        TableRegistry::getTableLocator()->clear();

        return TableRegistry::getTableLocator()->get($alias, $options);
    }

    /**
     * Asserts log file contents
     * @param string $expectedContent The expected contents
     * @param string $filename Log filename
     * @param string $message The failure message that will be appended to the
     *  generated message
     * @return void
     * @uses getLogFullPath()
     */
    public function assertLogContains($expectedContent, $filename, $message = '')
    {
        $filename = $this->getLogFullPath($filename);

        try {
            is_readable_or_fail($filename);
            $content = file_get_contents($filename);
        } catch (Exception $e) {
            $this->fail($e->getMessage());
        }

        $this->assertContains($expectedContent, $content, $message);
    }

    /**
     * Deletes a log file
     * @param string $filename Log filename
     * @return void
     * @uses getLogFullPath()
     */
    public function deleteLog($filename)
    {
        unlink($this->getLogFullPath($filename));
    }
}
