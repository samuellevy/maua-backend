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
        $this->loadComponent('ProcessPoints');
    }
    
    public function token()
    {
        $user = $this->Auth->identify();
        if (!$user) {
            throw new UnauthorizedException('Usuário ou senha incorreto(s)');
        }
        // die(debug($user));
        $this->set([
            'success' => true,
            'data' => [
                'user' => $user['username'],
                'role_id' => $user['role_id'],
                'token' => JWT::encode([
                    'sub' => $user['id'],
                    'exp' =>  time() + 604800
                ],
                Security::salt())
            ],
            '_serialize' => ['success', 'data', 'role_id']
        ]);
    }


    public function get($id){
        $identity = $this->Auth->identify();
        $user = $this->Users->get($id, ['contain'=>['Stores.Sales','Roles']]);
        $stores = $this->Users->Stores->find('all', ['order'=>['total DESC']])->all()->toArray();
        foreach($stores as $key=>$store):
            $stores[$key]->ranking = $key + 1;
        endforeach;

        $this->set([
            'success' => true,
            'user' => [
                'username' => $user->username,
                'name' => $user->name,
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


    public function list($store_id=null){
        $identity = $this->Auth->identify();
        if($store_id == null){
            $users = $this->Users->find('all', ['conditions'=>['store_id'=>$identity['store_id'], 'Users.active'=>1, 'NOT'=>['Users.id'=>$identity['id']]], 'contain'=>['Stores.Users', 'Roles', 'CourseProgress.Courses']])->all()->toArray();
        }else{
            $users = $this->Users->find('all', ['conditions'=>['store_id'=>$store_id, 'Users.active'=>1, 'NOT'=>['Users.id'=>$identity['id']]], 'contain'=>['Stores.Users', 'Roles', 'CourseProgress.Courses']])->all()->toArray();
        }

        // die(debug($users));
        foreach($users as $key=>$item){
            // $users[$key]->completed = true;
            if(isset($users[$key]['course_progress'][0])){
                $count_cp = count($users[$key]['course_progress'])-1;
                $course_progress = $users[$key]['course_progress'][$count_cp];
                $users[$key]->course_status = 'Completou o módulo '.$course_progress['course']['title'].'!';
                $users[$key]->completed = true;
            }else{
                $users[$key]->course_status = 'Ainda não completou nenhum módulo.';
                $users[$key]->completed = false;
            }
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
                // $this->processPoints(['newUser', $data['store_id'], $identity['id']]);
                if(date('m')==8){
                    // $this->ProcessPoints->execute('new_user', $data['store_id'], $identity['id']);
                }
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
            'contain' => ['Stores']
        ]);

        $data['username'] = '@_'.$user->username;
        
        if ($this->request->is('post')) {
            $user = $this->Users->patchEntity($user, $data);

            if ($this->Users->save($user)) {
                $return = true;
                // $this->processPoints(['delUser', $user['store_id'], $user['id']]);
                if(date('m')==8){
                    // $this->ProcessPoints->execute('del_user', $user['store_id'], $identity['id'], $data['id']);
                }
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

        $user = $this->Users->find('all', ['conditions'=>['username'=>$data['email'], 'phone'=>$data['phone']]])->first();
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

        $user = $this->Users->find('all', ['conditions'=>['username'=>$data['email'], 'phone'=>$data['phone']]])->first();
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

    /** functions */

    // public function processPoints($arguments){
    //     switch($arguments[0]):
    //         case 'newUser':
    //         $users = $this->Users->find('all', ['conditions'=>['Users.store_id'=>$arguments[1],'Users.active'=>true]])->all();
    //         $count_users = count($users);

    //         // procura se a loja já ganhou pontos por conclusao de curso de todos os funcionários
    //         $this->loadModel('Points');
    //         $points = $this->Points->find('all')->all();
    //         $qtt_points = count($points);

    //         die(debug($points));

    //         if($qtt_points>0){
    //             $pointing = -25;
    //             $this->loadModel('Points');
    //             $data['title'] = 'Novo funcionário a completar módulo';
    //             $data['point'] = $pointing;
    //             $data['user_id'] = $arguments[2];
    //             $data['store_id'] = $arguments[1];
    //             $data['status'] = 1;
    //             $point = $this->Points->newEntity();
    //             $point = $this->Points->patchEntity($point, $data);
    //             $this->Points->save($point);

    //             $this->loadModel('Stores');
    //             $store = $this->Stores->get($arguments[1]);
    //             $total_store = $store->total;
    //             $store = $this->Stores->patchEntity($store, ['total'=>$total_store+$pointing]);
    //             // die(debug($store->total));
    //             $this->Stores->save($store);
    //         }

    //         if($count_users == 2):
    //             $pointing = 20;
    //             $this->loadModel('Points');
    //             $data['title'] = 'Cadastro de funcionários';
    //             $data['point'] = $pointing;
    //             $data['user_id'] = $arguments[2];
    //             $data['store_id'] = $arguments[1];
    //             $data['type'] = 'new_user';
    //             $data['month'] = 8;
    //             $point = $this->Points->newEntity();
    //             $point = $this->Points->patchEntity($point, $data);
    //             $this->Points->save($point);

    //             $this->loadModel('Stores');
    //             $store = $this->Stores->get($arguments[1]);
    //             $total_store = $store->total;
    //             $store = $this->Stores->patchEntity($store, ['total'=>$total_store+$pointing]);
    //             // die(debug($store->total));
    //             $this->Stores->save($store);
    //         endif;
    //     break;

    //         case 'delUser':
    //         $users = $this->Users->find('all', ['conditions'=>['Users.store_id'=>$arguments[1],'Users.active'=>true]])->all();
    //         $count_users = count($users);
    //         if($count_users == 1):
    //             $pointing = -20;
    //             $this->loadModel('Points');
    //             $data['title'] = 'Remoção de funcionários';
    //             $data['point'] = $pointing;
    //             $data['user_id'] = $arguments[2];
    //             $data['store_id'] = $arguments[1];
    //             $data['type'] = 'del_user';
    //             $data['month'] = 8;
    //             $point = $this->Points->newEntity();
    //             $point = $this->Points->patchEntity($point, $data);
    //             $this->Points->save($point);

    //             $this->loadModel('Stores');
    //             $store = $this->Stores->get($arguments[1]);
    //             $total_store = $store->total;
    //             $store = $this->Stores->patchEntity($store, ['total'=>$total_store+$pointing]);
    //             // die(debug($store->total));
    //             $this->Stores->save($store);
    //             endif;
    //         break;
    //     endswitch;
    // }
}