<?php
namespace App\Controller\Api;

use Cake\Event\Event;
use Cake\Network\Exception\UnauthorizedException;
use Cake\Utility\Security;
use Firebase\JWT\JWT;

class PagesController extends AppController
{
    public function initialize()
    {
        parent::initialize();
    }

    public function get($slug=null){
        $this->loadModel('Users');
        $identity = $this->Auth->identify();
        $user = $this->Users->get($identity['id'], ['contain'=>['Stores.Sales'=>['sort'=>'month DESC'], 'Stores.Points', 'Roles']]);

        if($user->role->name=='Lojista'){
            $slug='about_lojista';
        }
        $this->loadModel('Pages');
        $page = $this->Pages->find('all', ['conditions'=>['slug'=>$slug],'limit'=>1])->first();

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
            'user' => [
                'username' => $identity['username'],
                'name' => $identity['name'],
                'email' => $user->email, 
                'loja' => $user->store->name,
                'phone' => $user->phone,
                'pontuacao' => $user->store->total,
                'role_id' => $user->role->id,
                'role' => $user->role->name
            ],
            '_serialize' => ['success', 'page', 'user']
        ]);
    }
}