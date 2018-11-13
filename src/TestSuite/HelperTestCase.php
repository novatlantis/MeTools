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
 * @since       2.17.5
 */
namespace MeTools\TestSuite;

use MeTools\TestSuite\TestCase;
use MeTools\TestSuite\Traits\MockTrait;

/**
 * Abstract class for test helpers
 */
class HelperTestCase extends TestCase
{
    use MockTrait;

    /**
     * Helper instance
     * @var \PHPUnit\Framework\MockObject\MockObject
     */
    protected $Helper;

    /**
     * Called before every test method
     * @return void
     * @uses $Helper
     */
    public function setUp()
    {
        parent::setUp();

        if (!$this->Helper) {
            $parts = explode('\\', get_class($this));
            array_splice($parts, 1, 2, []);
            $parts[] = substr(array_pop($parts), 0, -4);
            $className = implode('\\', $parts);

            $this->Helper = $this->getMockForHelper($className, null);
        }
    }
}