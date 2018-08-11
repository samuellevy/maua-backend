<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\ComercialStoresTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\ComercialStoresTable Test Case
 */
class ComercialStoresTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\ComercialStoresTable
     */
    public $ComercialStores;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.comercial_stores',
        'app.users',
        'app.stores'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::getTableLocator()->exists('ComercialStores') ? [] : ['className' => ComercialStoresTable::class];
        $this->ComercialStores = TableRegistry::getTableLocator()->get('ComercialStores', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->ComercialStores);

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
