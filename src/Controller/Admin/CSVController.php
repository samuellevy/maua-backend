<?php
namespace App\Controller\Admin;

use App\Controller\AppControllerAdmin;
use Cake\Event\Event;

/**
 * CSV Controller
 *
 * @property \App\Model\Table\CSVTable $CSV
 */
class CSVController extends AppController
{

    public function beforeFilter(Event $event){
        parent::beforeFilter($event);
        $this->Auth->allow('add');
    }
}
