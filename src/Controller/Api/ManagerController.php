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
    
    public function infos($store_id=null){
        $this->loadModel('Users');
        $identity = $this->Auth->identify();
        
        $user = $this->Users->get($identity['id'], ['contain'=>['Stores.Sales'=>['sort'=>'month DESC', 'conditions'=>['month'=>(int)date('m')]], 'Stores.Points', 'Roles']]);
        
        $store = $this->Users->Stores->find('all', ['contain'=>['Sales'], 'order'=>['total DESC'], 'conditions'=>['id'=>$store_id]])->first();
        $my_ranking = $this->Users->Stores->store_ranking($store_id);
        // die(debug($store));
        $this->loadComponent('FormatDate');
        foreach($user->store->points as $iey=>$point):
            $user->store->points[$iey]->date = $this->FormatDate->formatDate($point->created,'mes_ano');
        endforeach;
        
        if(isset($store->sales[0])){
            $percent = round(($store->sales[0]->quantity*100)/$store->sales[0]->goal);
        }else{
            $percent = 0;
        }
        $month = [1=>'Janeiro',2=>'Fevereiro',3=>'Março',4=>'Abril',5=>'Maio',6=>'Junho',7=>'Julho',8=>'Agosto',9=>'Setembro',10=>'Outubro',11=>'Novembro',12=>'Dezembro'];
        
        $this->loadModel('Posts');
        $post = $this->Posts->find('all',['contain'=>['Files'], 'limit'=>1,'order'=>['id DESC']])->first();
        $url = 'http://dev2.3aww.com.br/lafargemaua/uploads/files/';
        
        $this->loadModel('Pages');
        $page = $this->Pages->find('all', ['conditions'=>['slug'=>'about'],'limit'=>1])->first();
        $rules = $this->Pages->find('all', ['conditions'=>['slug'=>'rules'],'limit'=>1])->first();

        $this->loadModel('Sales');
        $sale_base = $this->Sales->find('all', ['limit'=>1])->first();

        $this->set([
            'success' => true,
            'user' => [
                'username' => $identity['username'],
                'name' => $identity['name'],
                'email' => $user->email, 
                'loja' => $user->store->name,
                'phone' => $user->phone,
                'ranking' => $my_ranking,
                'pontuacao' => $store->total,
                'role_id' => $user->role->id,
                'role' => $user->role->name
            ],
            'store' => [
                'name' => $store->name,
                'points' => $store->total,
                'ranking' => $my_ranking,
            ],
            'sales' => [
                'quantity'=>isset($store->sales[0])?$store->sales[0]->quantity:0,
                'goal'=>isset($store->sales[0])?$store->sales[0]->goal:0,
                'month'=>isset($store->sales[0])?$store->sales[0]->month:0,
                'month_name'=>isset($store->sales[0])?$month[$store->sales[0]->month]:0,
                'percent'=> $percent,
                'year'=>'2018',
                'message' => "Quase lá"
            ],
            'points' => $user->store->points,
            'configs' => [
                'last_update' => isset($sale_base)?$sale_base->created:0,
                'clean_cache' => false,
            ],
            '_serialize' => ['success', 'user', 'store', 'points', 'sales', 'post', 'rules', 'push', 'configs','page']
            ]
        );
    }

    public function store($store_id){
        $this->loadModel('Users');
        $identity = $this->Auth->identify();
        $user = $this->Users->get($identity['id'], ['contain'=>['Roles']]);
        $rankingYellow = $this->Users->Stores->ranking('p');
        
        $comercial_stores = $this->Users->ComercialStores->find('all', ['conditions'=>['store_id'=>$store_id], 'contain'=>['Stores']])->toArray();
        $my_ranking = $this->Users->Stores->store_ranking($store_id);

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
            'store'=>$comercial_stores[0]->store,
            'ranking'=>$my_ranking,
            '_serialize' => ['success', 'user', 'comercial_stores', 'store', 'ranking']
            ]
        );
    }
    
    public function ranking($category){
        $this->loadModel('Users');
        $identity = $this->Auth->identify();
        $user = $this->Users->get($identity['id'], ['contain'=>['Roles']]);
        $ranking = $this->Users->Stores->ranking($user->id, $category, false);
        
        $comercial_stores = $this->Users->ComercialStores->find('list', ['conditions'=>['user_id'=>$user->id]])->toArray();
        
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
        $ranking = $this->Users->Stores->ranking(null, $category, false);
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