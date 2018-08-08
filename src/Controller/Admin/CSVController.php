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

    public function resultados()
    {
        $this->viewBuilder()->layout('ajax');
        $this->loadModel('Stores');
        $stores = $this->Stores->find('all', ['contain'=>['Users.Roles', 'Users.CourseProgress'], 'conditions'=>['id >='=>10]])->all();
        $stores = $stores->toArray();
        $this->set(compact('stores'));
    }

    public function participantes()
    {
        $this->viewBuilder()->layout('ajax');
        $this->loadModel('Stores');
        $stores = $this->Stores->find('all', ['contain'=>['Users.Roles'], 'conditions'=>['id >='=>10]])->all();
        $stores = $stores->toArray();
        $this->set(compact('stores'));
        }



}
