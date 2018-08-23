<?php
namespace App\Controller\Admin;

use App\Controller\AppControllerAdmin;
use Cake\Event\Event;

/**
 * Report Controller
 *
 * @property \App\Model\Table\ReportTable $Report
 */
class ReportController extends AppController
{

    public function beforeFilter(Event $event){
        parent::beforeFilter($event);
        $this->Auth->allow('add');
    }

    public function index()
    {
        $this->loadModel('Stores');
        $stores = $this->paginate($this->Stores, [
            'contain'=>['Users.CourseProgress'],
        ]);
        $stores = $stores->toArray();
        // die(debug($stores));
        $this->set(compact('stores'));
        $this->set('_serialize', ['stores']);
    }

    public function resultados()
    {
        $this->viewBuilder()->layout('ajax');
        $this->loadModel('Stores');
        $stores = $this->Stores->find('all', ['contain'=>['Users.Roles', 'Users.CourseProgress', 'Users'=>['conditions'=>['active'=>1]]], 'conditions'=>['id >='=>10]])->all();
        $stores = $stores->toArray();
        $this->set(compact('stores'));
    }

    public function participantes()
    {
        $this->viewBuilder()->layout('ajax');
        $this->loadModel('Stores');
        $stores = $this->Stores->find('all', ['contain'=>['Users.Roles', 'Users'=>['conditions'=>['active'=>1]]], 'conditions'=>['id >='=>10]])->all();
        $stores = $stores->toArray();
        $this->set(compact('stores'));
    }

}
