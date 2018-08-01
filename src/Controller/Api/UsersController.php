<?php
namespace App\Controller\Api;

use Cake\Event\Event;
use Cake\Network\Exception\UnauthorizedException;
use Cake\Utility\Security;
use Firebase\JWT\JWT;

class UsersController extends AppController
{
    public function initialize()
    {
        parent::initialize();
        $this->Auth->allow(['add', 'token', 'forget', 'saveNewPass']);
    }
    
    public function token()
    {
        $user = $this->Auth->identify();
        if (!$user) {
            throw new UnauthorizedException('Usuário ou senha incorreto(s)');
        }

        $this->set([
            'success' => true,
            'data' => [
                'user' => $user['username'],
                'token' => JWT::encode([
                    'sub' => $user['id'],
                    'exp' =>  time() + 604800
                ],
                Security::salt())
            ],
            '_serialize' => ['success', 'data']
        ]);
    }

    public function me(){
        $identity = $this->Auth->identify();
        $user = $this->Users->get($identity['id'], ['contain'=>['Stores.Sales','Roles']]);
        $stores = $this->Users->Stores->find('all', ['order'=>['total DESC']])->all()->toArray();
        foreach($stores as $key=>$store):
            $stores[$key]->ranking = $key + 1;
        endforeach;

        $this->set([
            'success' => true,
            'user' => [
                'username' => $identity['username'],
                'name' => $identity['name'],
                'email' => $user->email, 
                'loja' => $user->store->name,
                'phone' => $user->phone,
                'ranking' => $stores[$user->store->id]->ranking,
                'pontuacao' => $stores[$user->store->id]->total,
                'role_id' => $user->role->id,
                'role' => $user->role->name,
                'first_access' => $user->first_access
            ],
            '_serialize' => ['success', 'user']
        ]);
    }


    public function list(){
        $identity = $this->Auth->identify();
        $users = $this->Users->find('all', ['conditions'=>['store_id'=>$identity['store_id'], 'Users.active'=>1, 'NOT'=>['Users.id'=>$identity['id']]]], ['contain'=>['Stores.Users', 'Roles']])->all()->toArray();
        foreach($users as $key=>$item){
            $users[$key]->completed = true;
            $users[$key]->course_status = 'Todos os módulos foram completos';
        }
        
        $this->set([
            'success' => true,
            'users' => $users,
            '_serialize' => ['success', 'users']
        ]);
    }

    public function edit($who=null){
        $identity = $this->Auth->identify();

        switch($who):
            case 'me':
                $user = $this->Users->get($identity['id'], [
                    'contain' => ['Stores']
                ]);
                if ($this->request->is('post')) {
                    $user = $this->Users->patchEntity($user, $this->request->getData());

                    if($user->senha!=''){
                        $user->password = $user->senha;
                    }

                    if ($this->Users->save($user)) {
                        $return = 'Salvo com sucesso';
                    }else{
                        $return = 'Erro ao salvar';
                    }
                }

                $user = $this->Users->get($identity['id'], [
                    'contain' => ['Stores']
                ]);

                $this->set([
                    'success' => true,
                    'message' => $return,
                    'user' => [
                        'username' => $identity['username'],
                        'name' => $user['name'],
                        'email' => $user->email, 
                        'loja' => $user->store->name,
                        'phone' => $user->phone,
                        'senha' => $user->senha,
                        'ranking' => '4',
                        'pontuacao' => '510'
                    ],
                    '_serialize' => ['success', 'user']
                ]);
            break;
            default:
                $user = $this->Users->get($who, [
                    'contain' => ['Stores']
                ]);
                if ($this->request->is('post')) {
                    $user = $this->Users->patchEntity($user, $this->request->getData());
                    if($user->senha!=''){
                        $user->password = $user->senha;
                    }

                    if ($this->Users->save($user)) {
                        $return = 'Salvo com sucesso';
                    }else{
                        $return = 'Erro ao salvar';
                    }
                }

                $this->set([
                    'success' => true,
                    'message' => $return,
                    '_serialize' => ['success', 'message']
                ]);
            break;
        endswitch;
    }

    public function add(){
        $identity = $this->Auth->identify();
        $data = $this->request->getData();
        $data['store_id']=$identity['store_id'];
        $data['active']=1;
        $data['role_id']=6;
        $data['username']=$data['email'];
        $data['password']='quementendevende';

        $user = $this->Users->newEntity();
        if ($this->request->is('post')) {
            $user = $this->Users->patchEntity($user, $data);

            if ($this->Users->save($user)) {
                $return = true;
                $this->processPoints(['newUser', $data['store_id'], $identity['id']]);
            }else{
                $return = false;
            }
        }

        if($return){
            $this->set([
                'success' => true,
                'message' => 'Adicionado com sucesso',
                '_serialize' => ['success', 'message']
            ]);
        }else{
            $this->set([
                'success' => false,
                'message' => 'Erro',
                '_serialize' => ['success', 'message']
            ]);
        }
    }

    public function processPoints($arguments){
        switch($arguments[0]):
            case 'newUser':
            $users = $this->Users->find('all', ['conditions'=>['Users.store_id'=>$arguments[1]]])->all();
            $count_users = count($users);
            if($count_users == 2):
                $pointing = 20;
                $this->loadModel('Points');
                $data['title'] = 'Cadastro de funcionários';
                $data['point'] = $pointing;
                $data['user_id'] = $arguments[2];
                $data['store_id'] = $arguments[1];
                $point = $this->Points->newEntity();
                $point = $this->Points->patchEntity($point, $data);
                $this->Points->save($point);

                $this->loadModel('Stores');
                $store = $this->Stores->get($arguments[1]);
                $total_store = $store->total;
                $store = $this->Stores->patchEntity($store, ['total'=>$total_store+$pointing]);
                // die(debug($store->total));
                $this->Stores->save($store);
                endif;
            break;
        endswitch;
    }

    public function remove(){
        $identity = $this->Auth->identify();
        $data = $this->request->getData();
        $data['active']=0;
        
        $user = $this->Users->get($data['id'], [
        ]);
        
        if ($this->request->is('post')) {
            $user = $this->Users->patchEntity($user, $data);

            if ($this->Users->save($user)) {
                $return = true;
            }else{
                $return = false;
            }
        }

        if($return){
            $this->set([
                'success' => true,
                'message' => 'Removido com sucesso',
                '_serialize' => ['success', 'message']
            ]);
        }else{
            $this->set([
                'success' => false,
                'message' => 'Erro',
                '_serialize' => ['success', 'message']
            ]);
        }
    }

    public function forget(){
        $identity = $this->Auth->identify();
        $data = $this->request->data;

        $user = $this->Users->find('all', ['conditions'=>['email'=>$data['email'], 'phone'=>$data['phone']]])->first();
        $hasUser = count($user);

        if($hasUser){
            $this->set([
                'success' => true,
                'user' => $user,
                'data' => $data,
                '_serialize' => ['success', 'user', 'data']
                ]
            );
        }else{
            $this->set([
                'success' => false,
                'message' => 'Usuário não encontrado',
                '_serialize' => ['success', 'message']
                ]
            );
        }
    }

    public function saveNewPass(){
        $identity = $this->Auth->identify();
        $data = $this->request->data;

        $user = $this->Users->find('all', ['conditions'=>['email'=>$data['email'], 'phone'=>$data['phone']]])->first();
        $hasUser = count($user);
        $user = $this->Users->get($user->id);
        $user = $this->Users->patchEntity($user, $data);
        $this->Users->save($user);

        $this->set([
            'success' => true,
            'user' => $user,
            'data' => $data,
            '_serialize' => ['success', 'user', 'data']
            ]
        );
    }
}