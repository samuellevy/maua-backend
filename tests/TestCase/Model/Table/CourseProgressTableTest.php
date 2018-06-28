<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\CourseProgressTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\CourseProgressTable Test Case
 */
class CourseProgressTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\CourseProgressTable
     */
    public $CourseProgress;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.course_progress',
        'app.users',
        'app.courses'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::getTableLocator()->exists('CourseProgress') ? [] : ['className' => CourseProgressTable::class];
        $this->CourseProgress = TableRegistry::getTableLocator()->get('CourseProgress', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->CourseProgress);

        parent::tearDown();
    }

    /**
     * Test initialize method
     *
     * @return void
     */
    public function testInitialize()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test validationDefault method
     *
     * @return void
     */
    public function testValidationDefault()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test buildRules method
     *
     * @return void
     */
    public function testBuildRules()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }
}
