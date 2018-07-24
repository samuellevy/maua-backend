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

        $questions = $this->Questions->find('all', ['conditions'=>['Questions.course_id'=>$course_id, 'Questions.status'=>1], 'contain'=>['Options']]);
        $course = $this->Questions->Courses->find('all', ['conditions'=>['Courses.id'=>$course_id]])->first();
        $this->set([
            'success' => true,
            'course'=> $course,
            'questions' => $questions,
            '_serialize' => ['success', 'course', 'questions']
        ]);

        // die(debug($questions));
    }

    public function answer(){
        $identity = $this->Auth->identify();
       
        $data = $this->request->getData();

        $this->loadModel('Answers');
        if ($this->request->is('post')) {
            try{
                foreach($data['answers'] as $item){
                    $item['user_id']=$identity['id'];
                    $answer = $this->Answers->newEntity();
                    $answer = $this->Answers->patchEntity($answer, $item);
                    $this->Answers->save($answer);
                    $return = true;
                }
            } catch (Exception $e) {
                $return = false;
            }
        }
        
        $this->loadModel('Questions');
        $question_id = $data['answers'][0]['question_id'];
        $question = $this->Questions->find('all', ['conditions'=>['Questions.id'=>$question_id]])->first();
        
        $this->loadModel('CourseProgress');
        $progress['user_id'] = $identity['id'];
        $progress['course_id'] = $question->course_id;
        $progress['progress'] = 1;
        $course_progress = $this->CourseProgress->newEntity();
        $course_progress = $this->CourseProgress->patchEntity($course_progress, $progress);
        $this->CourseProgress->save($course_progress);

        if($return){
            $this->set([
                'success' => true,
                'message' => 'Enviado com sucesso',
                '_serialize' => ['success', 'message']
            ]);
        }else{
            $this->set([
                'success' => false,
                'message' => 'Erro',
                '_serialize' => ['success', 'message']
            ]);
        }
    }
}