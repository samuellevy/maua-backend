<?php
namespace App\Test\TestCase\Controller\Component;

use App\Controller\Component\FormatDateComponent;
use Cake\Controller\ComponentRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Controller\Component\FormatDateComponent Test Case
 */
class FormatDateComponentTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Controller\Component\FormatDateComponent
     */
    public $FormatDate;

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $registry = new ComponentRegistry();
        $this->FormatDate = new FormatDateComponent($registry);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->FormatDate);

        parent::tearDown();
    }

    /**
     * Test initial setup
     *
     * @return void
     */
    public function testInitialization()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }
}
