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
        $this->loadComponent('FormatDate');
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

        $points = $this->Points->find('all', ['contain'=>'Stores'])->all();
        $points = $points->toArray();
        $total = 0;
        foreach($points as $key=>$point):
            $points[$key]->date = $this->FormatDate->formatDate(h($point->created),'mes_ano');
            $total += $point->point;
        endforeach;

        $this->set([
            'success' => true,
            'total' => $total,
            'points' => $points,
            '_serialize' => ['success', 'points', 'total']
        ]);
    }
}