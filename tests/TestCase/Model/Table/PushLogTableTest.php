<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\PushLogTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\PushLogTable Test Case
 */
class PushLogTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\PushLogTable
     */
    public $PushLog;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.push_log',
        'app.users'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::getTableLocator()->exists('PushLog') ? [] : ['className' => PushLogTable::class];
        $this->PushLog = TableRegistry::getTableLocator()->get('PushLog', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->PushLog);

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
