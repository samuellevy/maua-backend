<?php
namespace App\Controller\Admin;

use App\Controller\AppControllerAdmin;
use Cake\Event\Event;

/**
 * Users Controller
 *
 * @property \App\Model\Table\UsersTable $Users
 */
class UsersController extends AppController
{

    public function beforeFilter(Event $event){
        parent::beforeFilter($event);
        $this->Auth->allow('add');
    }

    /**
     * Index method
     *
     * @return \Cake\Network\Response|null
     */
    public function index()
    {
        $this->loadModel('Roles');
        $roles = $this->Roles->find('list')->toArray();
        $conditions = [];

        $query = $this->request->query;
        if(isset($query['role'])){
            $role = $query['role'];
            if($role != 0){
                array_push($conditions, ['role_id'=>$role]);
            }
        }

        if(isset($query['access'])){
            $access = $query['access'];
            if($access != "all"){
                array_push($conditions, ['first_access'=>$access]);
            }
        }

        $this->paginate = [
            'contain'=>['Roles'],
            'conditions'=>['Users.id >='=>10, $conditions]
        ];
        $users = $this->paginate($this->Users);

        if ($this->request->is(['patch', 'post', 'put'])) {
            if (isset($this->request->data['id_search'])){
                $id = $this->request->data['id_search'];
                $conditions = ['Users.id'=>$id];
            }
            $users = $this->Users->find('all', [
                'contain'=>['Roles'],
                'conditions'=>['Users.id >='=>10, $conditions]
            ]);
        }

        $this->set(compact('users'));
        $this->set('_serialize', ['users']);

        $this->set(compact('roles'));
        $this->set('_serialize', ['roles']);

        $this->loadModel('Stores');
        $stores = $this->Stores->find('all', ['contain'=>['Users.Roles', 'Users.CourseProgress'], 'conditions'=>['id >='=>10]])->all();
        $stores = $stores->toArray();
        $this->set(compact('stores'));
    }

    /**
     * View method
     *
     * @param string|null $id User id.
     * @return \Cake\Network\Response|null
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $user = $this->Users->get($id, [
            'contain' => ['Roles']
        ]);

        $this->set('user', $user);
        $this->set('_serialize', ['user']);
    }
    /**
     * Add method
     *
     * @return \Cake\Network\Response|null Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $user = $this->Users->newEntity();
        if ($this->request->is('post')) {
            $user = $this->Users->patchEntity($user, $this->request->getData());
            if ($this->Users->save($user)) {
                $this->Flash->success(__('Salvo com sucesso.'));
                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('Não pôde ser salvo.'));
        }
        $roles = $this->Users->Roles->find('list', ['limit' => 200]);
        $stores = $this->Users->Stores->find('list', ['limit' => 200]);
        $this->set(compact('user', 'roles', 'stores'));
        $this->set('_serialize', ['user']);
    }
    /**
     * Edit method
     *
     * @param string|null $id User id.
     * @return \Cake\Network\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $user = $this->Users->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $user = $this->Users->patchEntity($user, $this->request->getData());
            if(empty($user->password)){
                unset($user->password);
                unset($user->confirm_password);
            }
            if ($this->Users->save($user)) {
                $this->Flash->success(__('Salvo com sucesso.'));
                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('Não pôde ser salvo.'));
        }
        $roles = $this->Users->Roles->find('list', ['limit' => 200]);
        $stores = $this->Users->Stores->find('list', ['limit' => 200]);
        $this->set(compact('user', 'roles', 'stores'));
        $this->set('_serialize', ['user']);
    }

    /**
     * Delete method
     *
     * @param string|null $id User id.
     * @return \Cake\Network\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $user = $this->Users->get($id);
        if ($this->Users->delete($user)) {
            $this->Flash->success(__('Removido com sucesso.'));
        } else {
            $this->Flash->error(__('Não pôde ser removido.'));
        }

        return $this->redirect(['action' => 'index']);
    }

    /* Login area */
        public function login(){
        $this->viewBuilder()->layout('login');
        if($this->request->is('post')){
            $user = $this->Auth->identify();
            if($user){
            $this->Auth->setUser($user);
            return $this->redirect($this->Auth->redirectUrl());
            }
            $this->Flash->error(__('Invalid username or password.'));
        }
    }

    public function logout(){
        return $this->redirect($this->Auth->logout());
    }

    public function list(){
        $this->loadModel('Stores');
        $stores = $this->Stores->find('all', ['contain'=>['Users.Roles'], 'conditions'=>['id >='=>10]])->all();
        $this->set(compact('stores'));
    }
}
