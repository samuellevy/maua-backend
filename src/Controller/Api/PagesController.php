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
            '_serialize' => ['success', 'page']
        ]);
    }
}