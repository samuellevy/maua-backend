<?php
namespace App\Controller\Api;

use Cake\Event\Event;
use Cake\Network\Exception\UnauthorizedException;
use Cake\Utility\Security;
use Firebase\JWT\JWT;

class PublicController extends AppController
{
    public function initialize()
    {
        parent::initialize();
    }
    
    public function home(){
        $this->loadModel('Users');
        $identity = $this->Auth->identify();
        $user = $this->Users->get($identity['id'], ['contain'=>['Stores.Sales', 'Roles']]);
        $stores = $this->Users->Stores->find('all', ['order'=>['total DESC']])->all()->toArray();
        foreach($stores as $key=>$store):
            $stores[$key]->ranking = $key + 1;
        endforeach;
        
        // die(debug($user));
        
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
                'role' => $user->role->name
            ],
            'store' => [
                
            ],
            '_serialize' => ['success', 'user']
            ]
        );
    }
    
    // Performance
    
    public function infos(){
        $this->loadModel('Users');
        $identity = $this->Auth->identify();
        $user = $this->Users->get($identity['id'], ['contain'=>['Stores.Sales'=>['sort'=>'month DESC'], 'Stores.Points', 'Roles']]);
        $store_key = null;
        $stores = $this->Users->Stores->find('all', ['order'=>['total DESC']])->all()->toArray();
        foreach($stores as $key=>$store):
            $stores[$key]->ranking = $key + 1;
            $store_key = $store['id']==$user->store_id?$key:$store_key;
        endforeach;
        
        $this->loadComponent('FormatDate');
        foreach($user->store->points as $iey=>$point):
            $user->store->points[$iey]->date = $this->FormatDate->formatDate($point->created,'mes_ano');
        endforeach;
        
        $percent = round(($user->store->sales[0]->quantity*100)/$user->store->sales[0]->goal);
        $month = [1=>'Janeiro',2=>'Fevereiro',3=>'Março',4=>'Abril',5=>'Maio',6=>'Junho',7=>'Julho',8=>'Agosto',9=>'Setembro',10=>'Outubro',11=>'Novembro',12=>'Dezembro'];
        
        $this->loadModel('Posts');
        $post = $this->Posts->find('all',['contain'=>['Files'], 'limit'=>1,'order'=>['id DESC']])->first();
        $url = 'http://192.168.2.71/rest/uploads/files/';
        
        $this->loadModel('Pages');
        $page = $this->Pages->find('all', ['conditions'=>['slug'=>'about'],'limit'=>1])->first();
        $rules = $this->Pages->find('all', ['conditions'=>['slug'=>'rules'],'limit'=>1])->first();

        $this->set([
            'success' => true,
            'page' => [
                'id'=>$page->id,
                'slug'=>$page->slug,
                'title'=>$page->title,
                'description'=>$page->description,
                'content'=>$page->content,
                'url'=>$page->url,
            ],
            '_serialize' => ['success', 'page']
        ]);


        $this->set([
            'success' => true,
            'user' => [
                'username' => $identity['username'],
                'name' => $identity['name'],
                'email' => $user->email, 
                'loja' => $user->store->name,
                'phone' => $user->phone,
                'ranking' => $stores[$store_key]->ranking,
                'pontuacao' => $user->store->total,
                'role_id' => $user->role->id,
                'role' => $user->role->name
            ],
            'store' => [
                'name' => $user->store->name,
                'points' => $user->store->total,
                'ranking' => $stores[$store_key]->ranking,
            ],
            'sales' => [
                'quantity'=>$user->store->sales[0]->quantity,
                'goal'=>$user->store->sales[0]->goal,
                'month'=>$user->store->sales[0]->month,
                'month_name'=>$month[$user->store->sales[0]->month],
                'percent'=> $percent,
                'year'=>'2018',
                'message' => "Quase lá"
            ],
            'points' => $user->store->points,
            'post' => [
                'id'=>$post->id,
                'title'=>$post->title,
                'description'=>$post->description,
                'url'=>$post->url,
                'image'=>$url.$post->files[0]->filename,
            ],
            'page' => [
                'id'=>$page->id,
                'slug'=>$page->slug,
                'title'=>$page->title,
                'description'=>$page->description,
                'url'=>$page->url,
            ],
            'rules' => [
                'id'=>$rules->id,
                'slug'=>$rules->slug,
                'title'=>$rules->title,
                'description'=>$rules->description,
                'content'=>$rules->content,
                'url'=>$rules->url,
            ],
            '_serialize' => ['success', 'user', 'store', 'points', 'sales', 'post', 'page', 'rules']
            ]
        );
    }
    
    public function posts(){
        $this->loadModel('Users');
        $identity = $this->Auth->identify();
        
        $this->set([
            'success' => true,
            'post' => [
                'id'=>$post->id,
                'title'=>$post->title,
                'description'=>$post->description,
                'url'=>$post->url,
                'image'=>$url.$post->files[0]->filename,
            ],
            '_serialize' => ['success', 'post']
            ]
        );
    }

    public function ranking(){
        $this->loadModel('Users');
        $identity = $this->Auth->identify();
        $user = $this->Users->get($identity['id'], ['contain'=>['Stores.Sales'=>['sort'=>'month DESC'], 'Stores.Points', 'Roles']]);
        $stores = $this->Users->Stores->find('all', ['order'=>['total DESC']])->all()->toArray();
        
        $store_key = null;
        foreach($stores as $key=>$store):
            $stores[$key]->ranking = $key + 1;
            $store_key = $store['id']==$user->store_id?$key:$store_key;
        endforeach;

        $this->set([
            'success' => true,
            'my_store' => $stores[$store_key],
            'stores' => $stores,
            '_serialize' => ['success', 'my_store', 'stores']
            ]
        );
    }

    public function contact(){
        $this->loadModel('Users');
        $identity = $this->Auth->identify();
        $user = $this->Users->get($identity['id']);

        $data = $this->request->data;
        $data['user_id'] = $user->id;

        $this->loadModel('Messages');
        $message = $this->Messages->newEntity();
        $message = $this->Messages->patchEntity($message, $data);
        $this->Messages->save($message);

        $this->set([
            'success' => true,
            'user' => $user,
            'data' => $data,
            '_serialize' => ['success', 'user', 'data']
            ]
        );
    }
}