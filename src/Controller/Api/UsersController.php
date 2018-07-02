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
        $this->Auth->allow(['add', 'token']);
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
        $user = $this->Users->get($identity['id'], ['contain'=>['Stores.Sales']]);
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
                'pontuacao' => $stores[$user->store->id]->total
            ],
            '_serialize' => ['success', 'user']
        ]);
    }


    public function list(){
        $identity = $this->Auth->identify();
        $user = $this->Users->get($identity['id'], ['contain'=>['Stores.Users']]);
        foreach($user->store->users as $key=>$item){
            $user->store->users[$key]->completed = true;
            $user->store->users[$key]->course_status = 'Todos os módulos foram completos';
        }
        
        $this->set([
            'success' => true,
            'users' => $user->store->users,
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
                    $user->phone = $user->tel;
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
                    'user' => [
                        'username' => $identity['username'],
                        'name' => $identity['name'],
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
                    $user->phone = $user->tel;
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
        $data['phone']=$data['tel'];
        $data['store_id']=$identity['store_id'];
        $data['active']=1;
        $data['role_id']=6;

        $user = $this->Users->newEntity();
        if ($this->request->is('post')) {
            $user = $this->Users->patchEntity($user, $data);

            if($user->senha!=''){
                $user->password = $user->senha;
            }

            if ($this->Users->save($user)) {
                $return = true;
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
}