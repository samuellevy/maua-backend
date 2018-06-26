<?php
namespace App\Controller\Api;

use Cake\Event\Event;
use Cake\Network\Exception\UnauthorizedException;
use Cake\Utility\Security;
use Firebase\JWT\JWT;

class QuestionsController extends AppController
{
    public function initialize()
    {
        parent::initialize();
    }

    public function index(){
        throw new UnauthorizedException('Rota desconhecida');
    }

    public function get($course_id=null)
    {
        $user = $this->Auth->identify();
        if (!$user) {
            throw new UnauthorizedException('Não autorizado');
        }
        if($course_id == null){
            throw new UnauthorizedException('Curso não definido');
        }

        $question = $this->Questions->find('all', ['conditions'=>['Questions.course_id'=>$course_id, 'status'=>1], 'contain'=>['Options']]);
        $this->set([
            'success' => true,
            'question' => [
                $question
            ],
            '_serialize' => ['success', 'question']
        ]);
    }
}