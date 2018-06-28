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

    public function add()
    {
        $this->Crud->on('afterSave', function(Event $event) {
            if ($event->subject->created) {
                $this->set('data', [
                    'id' => $event->subject->entity->id,
                    'token' => JWT::encode(
                        [
                            'sub' => $event->subject->entity->id,
                            'exp' =>  time() + 604800
                        ],
                    Security::salt())
                ]);
                $this->Crud->action()->config('serialize.data', 'data');
            }
        });
        return $this->Crud->execute();
    }
    public function token()
    {
        $user = $this->Auth->identify();
        if (!$user) {
            throw new UnauthorizedException('CPF ou senha incorreto(s)');
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

        $this->set([
            'success' => true,
            'user' => [
                'username' => $identity['username'],
                'name' => $identity['name'],
                'email' => $user->email, 
                'loja' => $user->store->name,
                'phone' => $user->phone,
                'ranking' => '4',
                'pontuacao' => '510'
            ],
            '_serialize' => ['success', 'user']
        ]);
    }

    public function edit($who=null){
       

        if($who == 'me'){
            $identity = $this->Auth->identify();

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
        }
    }
}