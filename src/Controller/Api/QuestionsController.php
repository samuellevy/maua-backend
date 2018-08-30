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
        $this->loadComponent('ProcessPoints');
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
       
        $this->loadModel('Users');
        $user = $this->Users->get($identity['id']);
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
        
        // $this->processPoints(['type'=>'setPoint', 'course_id'=>1, 'store_id'=>$user->store_id, 'user_id'=>$user->id]);
        $this->ProcessPoints->execute('calculate_answer', $user['store_id'], $identity['id'], $question->course_id);

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

    public function processPoints($arguments=null){
        // $arguments = ['setPoint', 1, 2];
        
        switch($arguments['type']):
            case 'setPoint':
            $this->loadModel('CourseProgress');
            $course_progress = $this->CourseProgress->getResults($arguments['course_id'],$arguments['store_id']);
            $qtt_course_progress = count($course_progress);

            $this->loadModel('Users');
            $users = $this->Users->find('all', ['conditions'=>['Users.store_id'=>$arguments['store_id'],'Users.active'=>true,'Users.role_id'=>6]])->all();
            $count_users = count($users);

            // die(debug($qtt_course_progress));
            if($count_users == $qtt_course_progress):
                $pointing = 25;
                $this->loadModel('Points');
                $data['title'] = 'Todos os funcionários concluíram o módulo';
                $data['point'] = $pointing;
                $data['user_id'] = $arguments['user_id'];
                $data['store_id'] = $arguments['store_id'];
                $data['type'] = 'completed_module';
                $data['month'] = 8;
                $point = $this->Points->newEntity();
                $point = $this->Points->patchEntity($point, $data);
                $this->Points->save($point);

                $this->loadModel('Stores');
                $store = $this->Stores->get($arguments['store_id']);
                $total_store = $store->total;
                $store = $this->Stores->patchEntity($store, ['total'=>$total_store+$pointing]);
                // die(debug($store->total));
                $this->Stores->save($store);
                return true;
                endif;
            break;
        endswitch;
    }

        public function test(){
        $this->loadModel('CourseProgress');
        $course_progress = $this->CourseProgress->getResults(2,2);
        $qtt_course_progress = count($course_progress);

        $this->loadModel('Users');
        $users = $this->Users->find('all', ['conditions'=>['Users.store_id'=>2,'Users.active'=>true, 'Users.role_id'=>6]])->all();
        $count_users = count($users);

        die(debug($count_users));
    }

}