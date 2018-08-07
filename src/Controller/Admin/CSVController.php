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

    public function index()
    {
        $this->viewBuilder()->layout('ajax');
        $this->loadModel('Stores');
        $stores = $this->paginate($this->Stores, [
            'contain'=>['Users.CourseProgress'],
        ]);
        $stores = $stores->toArray();
        // die(debug($stores));
        $this->set(compact('stores'));
        $this->set('_serialize', ['stores']);
    }



}
