<?php
namespace App\Controller\Admin;

use Cake\Controller\Controller;
use Cake\Event\Event;

class AppController extends Controller
{

    public function initialize()
    {
        parent::initialize();

        $this->loadComponent('RequestHandler');
        $this->loadComponent('Flash');
        $this->loadComponent('Auth',[
          'authenticate' => [
            'Form' => [
              'fields' => [
                'username' => 'username',
                'password' => 'password'
              ]
            ]
          ],
          'loginRedirect' => [
            'controller' => 'Users',
            'action' => 'index'
          ],
          'logoutRedirect' => [
            'controller' => 'Users',
            'action' => 'login'
          ]
        ]);
    }

    public function beforeRender(Event $event)
    {
        if (!array_key_exists('_serialize', $this->viewVars) &&
            in_array($this->response->type(), ['application/json', 'application/xml'])
        ) {
            $this->set('_serialize', true);
        }
    }

    public function beforeFilter(Event $event)
    {
        $this->viewBuilder()->theme('Gerenciador'); // AppUI is my plugin name
        $params = $this->request->params;
        $this->set('username', $this->Auth->user('username'));
        $this->set('params', $params);
    }
}
