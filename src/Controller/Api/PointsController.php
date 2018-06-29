<?php
namespace App\Controller\Api;

use Cake\Event\Event;
use Cake\Network\Exception\UnauthorizedException;
use Cake\Utility\Security;
use Firebase\JWT\JWT;

class PointsController extends AppController
{
    public function initialize()
    {
        parent::initialize();
    }

    public function index(){
        throw new UnauthorizedException('Rota desconhecida');
    }

    public function get()
    {
        $identity = $this->Auth->identify();
        $this->loadModel('Users');
        $user = $this->Users->get($identity['id'], ['contain'=>[]]);

        if (!$user) {
            throw new UnauthorizedException('Não autorizado');
        }

        $points = $this->Points->find('all', ['contain'=>'Stores']);
        $this->set([
            'success' => true,
            'sales' => $points,
            '_serialize' => ['success', 'sales']
        ]);
    }
}