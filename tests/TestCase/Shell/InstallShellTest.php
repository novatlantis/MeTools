<?php
/**
 * This file is part of MeTools.
 *
 * MeTools is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as
 * published by the Free Software Foundation, either version 3 of the
 * License, or (at your option) any later version.
 *
 * MeTools is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 * along with MeTools.  If not, see <http://www.gnu.org/licenses/>.
 *
 * @author      Mirko Pagliai <mirko.pagliai@gmail.com>
 * @copyright   Copyright (c) 2016, Mirko Pagliai for Nova Atlantis Ltd
 * @license     http://www.gnu.org/licenses/agpl.txt AGPL License
 * @link        http://git.novatlantis.it Nova Atlantis Ltd
 */
namespace MeTools\Test\TestCase\Shell;

use Cake\Console\ConsoleIo;
use Cake\TestSuite\Stub\ConsoleOutput;
use Cake\TestSuite\TestCase;
use MeTools\Shell\InstallShell;
use Reflection\ReflectionTrait;

/**
 * InstallShellTest class
 */
class InstallShellTest extends TestCase
{
    use ReflectionTrait;

    /**
     * @var \MeTools\Shell\InstallShell
     */
    protected $InstallShell;

    /**
     * @var \Cake\TestSuite\Stub\ConsoleOutput
     */
    protected $err;

    /**
     * @var \Cake\TestSuite\Stub\ConsoleOutput
     */
    protected $out;

    /**
     * Setup the test case, backup the static object values so they can be
     * restored. Specifically backs up the contents of Configure and paths in
     *  App if they have not already been backed up
     * @return void
     */
    public function setUp()
    {
        parent::setUp();

        $this->out = new ConsoleOutput();
        $this->err = new ConsoleOutput();
        $io = new ConsoleIo($this->out, $this->err);
        $io->level(2);

        $this->InstallShell = $this->getMockBuilder(InstallShell::class)
            ->setMethods(['in', '_stop'])
            ->setConstructorArgs([$io])
            ->getMock();
    }

    /**
     * Teardown any static object changes and restore them
     * @return void
     */
    public function tearDown()
    {
        parent::tearDown();

        //Deletes all fonts and vendors
        foreach (array_merge(
            glob(WWW_ROOT . 'fonts' . DS . '*'),
            glob(WWW_ROOT . 'vendor' . DS . '*')
        ) as $file) {
            if (basename($file) !== 'empty') {
                unlink($file);
            }
        }

        unset($this->InstallShell, $this->err, $this->out);
    }

    /**
     * Tests for `copyConfig()` method
     * @test
     */
    public function testCopyConfig()
    {
        $this->InstallShell->copyConfig();

        $this->assertEquals([
            'File or directory tests/test_app/config/recaptcha.php already exists',
        ], $this->out->messages());

        $this->assertEmpty($this->err->messages());
    }

    /**
     * Tests for `copyFonts()` method
     * @test
     */
    public function testCopyFonts()
    {
        $this->InstallShell->copyFonts();

        $this->assertEquals([
            'Link tests/test_app/webroot/fonts/fontawesome-webfont.eot has been created',
            'Link tests/test_app/webroot/fonts/fontawesome-webfont.ttf has been created',
            'Link tests/test_app/webroot/fonts/fontawesome-webfont.woff has been created',
            'Link tests/test_app/webroot/fonts/fontawesome-webfont.woff2 has been created',
        ], $this->out->messages());

        $this->assertEmpty($this->err->messages());
    }

    /**
     * Tests for `createDirectories()` method
     * @test
     */
    public function testCreateDirectories()
    {
        $this->InstallShell->createDirectories();

        foreach ([
            TMP . 'cache' . DS . 'models',
            TMP . 'cache' . DS . 'persistent',
            TMP . 'cache' . DS . 'views',
            TMP . 'cache',
            WWW_ROOT . 'files',
        ] as $dir) {
            rmdir($dir);
        }

        $this->assertEquals([
            'File or directory /tmp/ already exists',
            'File or directory /tmp/ already exists',
            'Created /tmp/cache directory',
            'Setted permissions on /tmp/cache',
            'Created /tmp/cache/models directory',
            'Setted permissions on /tmp/cache/models',
            'Created /tmp/cache/persistent directory',
            'Setted permissions on /tmp/cache/persistent',
            'Created /tmp/cache/views directory',
            'Setted permissions on /tmp/cache/views',
            'File or directory /tmp/sessions already exists',
            'File or directory /tmp/tests already exists',
            'Created tests/test_app/webroot/files directory',
            'Setted permissions on tests/test_app/webroot/files',
            'File or directory tests/test_app/webroot/fonts already exists',
            'File or directory tests/test_app/webroot/vendor already exists',
        ], $this->out->messages());

        $this->assertEmpty($this->err->messages());
    }

    /**
     * Tests for `createRobots()` method
     * @test
     */
    public function testCreateRobots()
    {
        $this->assertFileNotExists(WWW_ROOT . 'robots.txt');
        $this->InstallShell->createRobots();
        $this->assertFileExists(WWW_ROOT . 'robots.txt');

        $this->assertEquals(
            file_get_contents(WWW_ROOT . 'robots.txt'),
            'User-agent: *' . PHP_EOL . 'Disallow: /admin/' . PHP_EOL .
            'Disallow: /ckeditor/' . PHP_EOL . 'Disallow: /css/' . PHP_EOL .
            'Disallow: /js/' . PHP_EOL . 'Disallow: /vendor/'
        );

        unlink(WWW_ROOT . 'robots.txt');

        $this->assertNotEmpty($this->out->messages());
        $this->assertEmpty($this->err->messages());
    }

    /**
     * Tests for `createVendorsLinks()` method
     * @test
     */
    public function testCreateVendorsLinks()
    {
        $this->InstallShell->createVendorsLinks();

        $this->assertEquals([
            'Link tests/test_app/webroot/vendor/bootstrap-datetimepicker has been created',
            'Link tests/test_app/webroot/vendor/jquery has been created',
            'Link tests/test_app/webroot/vendor/moment has been created',
            'Link tests/test_app/webroot/vendor/font-awesome has been created',
            'Link tests/test_app/webroot/vendor/fancybox has been created',
        ], $this->out->messages());

        $this->assertEmpty($this->err->messages());
    }

    /**
     * Test for `fixComposerJson()` method
     * @test
     */
    public function testFixComposerJson()
    {
        //Tries to fix a no existing file
        $this->InstallShell->fixComposerJson('noExisting');

        //Writes an invalid composer.json file
        $file = TMP . 'invalid.json';
        file_put_contents($file, 'String');

        //Tries to fix an invalid file
        $this->InstallShell->fixComposerJson($file);

        unlink($file);

        //Writes a `composer.json` file
        $file = APP . 'composer.json';
        $json = [
            'name' => 'example',
            'description' => 'example of composer.json',
            'type' => 'project',
            'require' => ['php' => '>=5.5.9'],
            'autoload' => ['psr-4' => ['App' => 'src']],
        ];
        file_put_contents($file, json_encode($json, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT));

        //Fixes the file
        $this->InstallShell->fixComposerJson($file);

        //Tries to fix again
        $this->InstallShell->fixComposerJson($file);

        unlink($file);

        $this->assertEquals([
            'The file tests/test_app/composer.json has been fixed',
            'The file tests/test_app/composer.json doesn\'t need to be fixed',
        ], $this->out->messages());

        $this->assertEquals([
            '<error>File or directory noExisting not writeable</error>',
            '<error>The file /tmp/invalid.json does not seem a valid composer.json file</error>',
        ], $this->err->messages());
    }

    /**
     * Test for `setPermissions()` method
     * @test
     */
    public function testSetPermissions()
    {
        //Resets the `paths` property, removing some values
        $paths = array_diff(array_unique($this->InstallShell->paths), [
            TMP,
            WWW_ROOT . 'fonts',
            WWW_ROOT . 'vendor',
        ]);
        $this->setProperty($this->InstallShell, 'paths', $paths);

        $this->InstallShell->setPermissions();

        $this->assertEquals([
            'Setted permissions on /tmp/sessions',
            'Setted permissions on /tmp/tests',
        ], $this->out->messages());

        $this->assertEquals([
            '<error>Failed to set permissions on /tmp/cache</error>',
            '<error>Failed to set permissions on /tmp/cache/models</error>',
            '<error>Failed to set permissions on /tmp/cache/persistent</error>',
            '<error>Failed to set permissions on /tmp/cache/views</error>',
            '<error>Failed to set permissions on tests/test_app/webroot/files</error>',
        ], $this->err->messages());
    }

    /**
     * Test for `getOptionParser()` method
     * @test
     */
    public function testGetOptionParser()
    {
        $parser = $this->InstallShell->getOptionParser();

        $this->assertEquals('Cake\Console\ConsoleOptionParser', get_class($parser));
        $this->assertEquals([
            'all',
            'copyConfig',
            'copyFonts',
            'createDirectories',
            'createRobots',
            'createVendorsLinks',
            'fixComposerJson',
            'setPermissions',
        ], array_keys($parser->subcommands()));
        $this->assertEquals('Executes some tasks to make the system ready to work', $parser->description());
        $this->assertEquals(['force', 'help', 'quiet', 'verbose'], array_keys($parser->options()));
    }
}
