<?php 
namespace App\Controller\Component;

use Cake\Controller\Component;
use Cake\ORM\TableRegistry;

class ProcessPointsComponent extends Component {

    public function initialize(array $config) {
        parent::initialize($config);
        // $this->loadModel('Users');
    }

    private function loadModel($model) {
        $this->$model = TableRegistry::get($model);
    }

    public function execute($type=null, $store_id=null, $user_id=null, $target=null){
        $month = date('m');
        switch($type):
            case 'new_user':
                $this->loadModel('Points');

                // verifica se ganhou pontos por cadastro de usuário
                $points = $this->Points->find('all', ['conditions'=>['type'=>'new_user', 'store_id'=>$store_id, 'month'=>$month, 'status'=>1]])->all();
                $qtt_points = count($points);

                if($qtt_points==0){
                    $this->insert_points(20, 'new_user', 'Cadastro de funcionários', $store_id, $user_id);
                }

                // verifica se ganhou pontos por conclusão de módulo da equipe
                $point = $this->Points->find('all', ['conditions'=>['type'=>'completed_module', 'store_id'=>$store_id, 'month'=>$month, 'status'=>1]])->first();
                $qtt_points = count($point);

                // die(debug($point));
                
                if($qtt_points>0){
                    $register_id = $point->id;
                    $this->remove_points(25, 'new_user_to_course', 'Novo funcionário a completar módulo', $store_id, $user_id, $register_id);
                }
                break;
            case 'del_user':
                $this->loadModel('Users');
                $users = $this->Users->find('all', ['conditions'=>['Users.store_id'=>$store_id, 'Users.role_id'=>6, 'Users.active'=>true]])->all();
                $count_users = count($users);

                $this->loadModel('Points');
                // verifica se ganhou pontos por adicionar funcionários
                $point = $this->Points->find('all', ['conditions'=>['type'=>'new_user', 'store_id'=>$store_id, 'month'=>$month, 'status'=>1]])->first();
                $qtt_points = count($point);

                if($count_users == 0 && $qtt_points > 0){
                    $register_id = $point->id;
                    $this->remove_points(20, 'del_user', 'Remoção de funcionários', $store_id, $user_id, $register_id, $target);
                }
            break;
            case 'calculate_answer':
                // usuários ativos na loja
                $this->loadModel('Users');
                $users = $this->Users->find('all', ['conditions'=>['Users.store_id'=>$store_id, 'Users.role_id'=>6, 'Users.active'=>true]])->all();
                $count_users = count($users);

                // identifica course progress
                $this->loadModel('CourseProgress');
                $cp = $this->CourseProgress->getResults($target, $store_id);
                $count_cp = count($cp);

                // verifica se ganhou pontos por conclusão de módulo da equipe
                $this->loadModel('Points');
                $point = $this->Points->find('all', ['conditions'=>['type'=>'completed_module', 'store_id'=>$store_id, 'month'=>$month, 'status'=>1]])->first();
                $count_point = count($point);


                if($count_point==0 && $count_users == $count_cp){
                    $this->insert_points(25, 'completed_module', 'Todos os funcionários concluíram o módulo', $store_id, $user_id);
                }
            break;
            case 'update_sales':
            
            break;
        endswitch;
    }

    public function insert_points($points=null,$type=null,$msg=null,$store_id=null,$user_id=null){
        $this->loadModel('Points');
        $data['title'] = $msg;
        $data['point'] = $points;
        $data['user_id'] = $user_id;
        $data['store_id'] = $store_id;
        $data['type'] = $type;
        $data['month'] = date('m');
        $data['status'] = 1;
        $point = $this->Points->newEntity();
        $point = $this->Points->patchEntity($point, $data);
        $this->Points->save($point);

        $this->loadModel('Stores');
        $store = $this->Stores->get($store_id);
        $total_store = $store->total;
        $store = $this->Stores->patchEntity($store, ['total'=>$total_store+$points]);
        $this->Stores->save($store);
    }

    public function remove_points($points=null,$type=null,$msg=null,$store_id=null,$user_id=null,$register_id=null, $obs=null){
        $this->loadModel('Points');
        $data['title'] = $msg;
        $data['point'] = $points*-1;
        $data['user_id'] = $user_id;
        $data['store_id'] = $store_id;
        $data['type'] = $type;
        $data['month'] = date('m');
        $data['status'] = 1;
        $data['obs'] = '[removed:'.$obs.', id:'.$register_id.']';
        $point = $this->Points->newEntity();
        $point = $this->Points->patchEntity($point, $data);
        $this->Points->save($point);

        $data=[];
        $data['status'] = 0;
        $point = $this->Points->get($register_id);
        $point = $this->Points->patchEntity($point, $data);
        $this->Points->save($point);

        $this->loadModel('Stores');
        $store = $this->Stores->get($store_id);
        $total_store = $store->total;
        $store = $this->Stores->patchEntity($store, ['total'=>$total_store+($points*-1)]);
        $this->Stores->save($store);
    }
}