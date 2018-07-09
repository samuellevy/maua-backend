<?php
namespace App\Controller\Api;

use Cake\Event\Event;
use Cake\Network\Exception\UnauthorizedException;
use Cake\Utility\Security;
use Firebase\JWT\JWT;

class CoursesController extends AppController
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
        $user = $this->Auth->identify();
        if (!$user) {
            throw new UnauthorizedException('Não autorizado');
        }
        $courses = $this->Courses->find('all', ['contain'=>['CourseProgress'=>['conditions'=>['user_id'=>1]]], 'conditions'=>['status'=>1]]);
        $courses = $courses->toArray();
        $progress = [0=>'Novo',1=>'Em curso',2=>'Completo'];
        foreach($courses as $key=>$course){
            $course_progress = $courses[$key]['course_progres'];
            $courses[$key]['progress']=$courses[$key]['course_progres']==null?'Novo':'Completo';
        }
        $this->set([
            'success' => true,
            'courses' => $courses,
            '_serialize' => ['success', 'courses']
        ]);
    }

    public function getLastCourse(){
        $user = $this->Auth->identify();
        if (!$user) {
            throw new UnauthorizedException('Não autorizado');
        }
        $courses = $this->Courses->find('all', ['conditions'=>['status'=>1], 'limit'=>'1', 'order'=>['id DESC']]);
        $courses = $courses->toArray();
        $this->set([
            'success' => true,
            'course' => $courses[0],
            '_serialize' => ['success', 'course']
        ]);
    }
}