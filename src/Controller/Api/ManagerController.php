<?php
namespace App\Controller\Api;

use Cake\Event\Event;
use Cake\Network\Exception\UnauthorizedException;
use Cake\Utility\Security;
use Firebase\JWT\JWT;

class ManagerController extends AppController
{
    public function initialize()
    {
        parent::initialize();
    }
    
    public function infos(){
        $this->loadModel('Users');
        $identity = $this->Auth->identify();
        $user = $this->Users->get($identity['id'], ['contain'=>['Roles']]);
        $rankingYellow = $this->Users->Stores->ranking('p');
        
        $comercial_stores = $this->Users->ComercialStores->find('list', ['conditions'=>['user_id'=>$user->id]])->toArray();
        
        $this->set([
            'success' => true,
            'user' => [
                'username' => $identity['username'],
                'name' => $identity['name'],
                'email' => $user->email, 
                'phone' => $user->phone,
                'role_id' => $user->role->id,
                'role' => $user->role->name,
            ],
            'comercial_stores'=>$comercial_stores,
            'rankingYellow' => $ranking->yellow,
            'rankingGreen' => $ranking->green,
            'rankingBlack' => $ranking->black,
            '_serialize' => ['success', 'user', 'comercial_stores', 'rankingYellow', 'rankingGreen', 'rankingBlack']
            ]
        );
    }
    
    public function ranking($category){
        $this->loadModel('Users');
        $identity = $this->Auth->identify();
        $user = $this->Users->get($identity['id'], ['contain'=>['Roles']]);
        $ranking = $this->Users->Stores->ranking($user->id, $category, 10);
        
        $comercial_stores = $this->Users->ComercialStores->find('list', ['conditions'=>['user_id'=>$user->id]])->toArray();
        // die(debug($ranking));
        foreach($ranking as $key=>$item):
            $ranking[$key]['position'] = $key + 1;
            $ranking[$key]['user_id'] = $item['comercial_store']['user_id'];
            $ranking[$key]['store_id'] = $item['comercial_store']['store_id'];
        endforeach;
        
        $this->set([
            'success' => true,
            'user' => [
                'id' => $user->id,
                'username' => $identity['username'],
                'name' => $identity['name'],
                'email' => $user->email, 
                'phone' => $user->phone,
                'role_id' => $user->role->id,
                'role' => $user->role->name,
            ],
            'ranking' => $ranking,
            '_serialize' => ['success', 'user', 'comercial_stores', 'ranking']
            ]
        );
    }
    
    public function all_ranking($category){
        $this->loadModel('Users');
        $identity = $this->Auth->identify();
        $user = $this->Users->get($identity['id'], ['contain'=>['Roles']]);
        $ranking = $this->Users->Stores->ranking($user->id, $category, false);
        $count_stores = $this->Users->Stores->count_stores($category);
        $count_stores_enabled = $this->Users->Stores->count_stores($category, 'enabled');

        $comercial_stores = $this->Users->ComercialStores->find('list', ['conditions'=>['user_id'=>$user->id]])->toArray();
        // die(debug($ranking));
        foreach($ranking as $key=>$item):
            $ranking[$key]['position'] = $key + 1;
            $ranking[$key]['user_id'] = $item['comercial_store']['user_id'];
            $ranking[$key]['store_id'] = $item['comercial_store']['store_id'];
        endforeach;
        
        $this->set([
            'success' => true,
            'user' => [
                'id' => $user->id,
                'username' => $identity['username'],
                'name' => $identity['name'],
                'email' => $user->email, 
                'phone' => $user->phone,
                'role_id' => $user->role->id,
                'role' => $user->role->name,
            ],
            'count_stores'=>$count_stores,
            'count_stores_enabled' => $count_stores_enabled,
            'ranking' => $ranking,
            '_serialize' => ['success', 'user', 'comercial_stores', 'count_stores', 'count_stores_enabled', 'ranking']
            ]
        );
    }
}