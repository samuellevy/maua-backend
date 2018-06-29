<?php
namespace App\Controller\Api;

use Cake\Event\Event;
use Cake\Network\Exception\UnauthorizedException;
use Cake\Utility\Security;
use Firebase\JWT\JWT;

class SalesController extends AppController
{
    public function initialize()
    {
        parent::initialize();
    }

    public function index(){
        throw new UnauthorizedException('Rota desconhecida');
    }

    public function get($from=null)
    {
        $identity = $this->Auth->identify();
        $this->loadModel('Users');
        $user = $this->Users->get($identity['id'], ['contain'=>['Stores.Sales']]);

        if (!$user) {
            throw new UnauthorizedException('Não autorizado');
        }
        
        switch($from):
            case 'me':
                $sales = $user->store->sales;
            break;
            case 'category':
                exit();
            break;
        endswitch;

        $this->set([
            'success' => true,
            'sales' => $sales,
            '_serialize' => ['success', 'sales']
        ]);
    }

    public function get_log(){
        $identity = $this->Auth->identify();
        $this->loadModel('Users');
        $user = $this->Users->get($identity['id'], ['contain'=>['Stores.Sales']]);

        if (!$user) {
            throw new UnauthorizedException('Não autorizado');
        }
    }
}