<?php
namespace App\Controller\Api;

use Cake\Event\Event;
use Cake\Network\Exception\UnauthorizedException;
use Cake\Utility\Security;
use Firebase\JWT\JWT;

class PublicController extends AppController
{
    public function initialize()
    {
        parent::initialize();
        $this->Auth->allow(['review']);
    }
    
    public function home(){
        $this->loadModel('Users');
        $identity = $this->Auth->identify();
        $user = $this->Users->get($identity['id'], ['contain'=>['Stores.Sales', 'Roles']]);
        $stores = $this->Users->Stores->find('all', ['order'=>['total DESC']])->all()->toArray();
        foreach($stores as $key=>$store):
            $stores[$key]->ranking = $key + 1;
        endforeach;
        
        // die(debug($user));
        
        $this->set([
            'success' => true,
            'user' => [
                'username' => $identity['username'],
                'name' => $identity['name'],
                'email' => $user->email, 
                'loja' => $user->store->name,
                'phone' => $user->phone,
                'ranking' => $stores[$user->store->id]->ranking,
                'pontuacao' => $stores[$user->store->id]->total,
                'role_id' => $user->role->id,
                'role' => $user->role->name
            ],
            'store' => [
                
            ],
            '_serialize' => ['success', 'user']
            ]
        );
    }
    
    // Performance
    
    public function infos(){
        $this->loadModel('Users');
        $identity = $this->Auth->identify();
        $user = $this->Users->get($identity['id'], ['contain'=>['Stores.Sales'=>['sort'=>'month DESC', 'conditions'=>['month'=>(int)date('m')]], 'Stores.Points'=>['sort'=>['Points.created DESC']], 'Roles']]);
        $store_key = null;

        // // die(debug($user));
        $this->loadModel('Stores');
        if($user->role_id == 8){
            // $stores = $this->Users->Stores->find('all', ['order'=>['total DESC']])->all()->toArray();
            // $stores = $this->Stores->getAllRanking('p');
            $stores = $this->Stores->fetchRankingByMonth('p', 10);
        }else{
            // $stores = $this->Users->Stores->find('all', ['order'=>['total DESC'], 'conditions'=>['Stores.category'=>$user->store->category]])->all()->toArray();
            $stores = $this->Stores->fetchRankingByMonth($user->store->category);
        }

        // die(debug($stores));
        
        foreach($stores as $key=>$store):
            $stores[$key]['ranking'] = $key + 1;
            $store_key = $store['id']==$user['store_id']?$key:$store_key;
        endforeach;

        if($user->role_id == 8){
            $store_key = 0;
            $stores[0]['ranking'] = 0;
        }

        $this->loadComponent('FormatDate');
        foreach($user->store->points as $iey=>$point):
            $user->store->points[$iey]->date = $this->FormatDate->formatDate($point->created,'mes_ano');
        endforeach;

        // die(debug($user->store));
        
        if(isset($user->store->sales[0])){
            $percent = round(($user->store->sales[0]->quantity*100)/$user->store->sales[0]->goal);
        }else{
            $percent = 0;
        }
        $month = [1=>'Janeiro',2=>'Fevereiro',3=>'Março',4=>'Abril',5=>'Maio',6=>'Junho',7=>'Julho',8=>'Agosto',9=>'Setembro',10=>'Outubro',11=>'Novembro',12=>'Dezembro'];
        
        $this->loadModel('Posts');
        $post = $this->Posts->find('all',['contain'=>['Files'], 'limit'=>1,'order'=>['id DESC']])->first();
        $url = 'http://dev2.3aww.com.br/lafargemaua/uploads/files/';
        
        $this->loadModel('Pages');
        $page = $this->Pages->find('all', ['conditions'=>['slug'=>'about'],'limit'=>1])->first();
        $rules = $this->Pages->find('all', ['conditions'=>['slug'=>'rules'],'limit'=>1])->first();

        $this->loadModel('Sales');
        $sale_base = $this->Sales->find('all', ['limit'=>1])->first();
        
        $this->loadModel('PushLog');
        
        $place = 0;
        $push_configs = [
            'exist' => false
        ];
        
        // ligar push
        $push_ranking = true;
        $push_warning = false;

        // push
        if($push_ranking):
            $my_ranking = $this->Stores->getMyRanking(11, $user->store->category, $user->store_id);
            
            switch($my_ranking['position']):
                case 1:
                    $pushlog_history = $this->PushLog->find('all',['conditions'=>['push_uid'=>'001', 'user_id'=>$user->id, 'readed'=>1]])->all();
                    if(!count($pushlog_history)>0){
                        $pushlog = $this->PushLog->newEntity();
                        $pushdata = ['push_uid'=>'001', 'user_id'=>$user->id, 'readed'=>1];
                        $pushlog = $this->PushLog->patchEntity($pushlog, $pushdata);
                        // $this->PushLog->save($pushlog);
                        $push = true;
                        $place = 1;
                    }
                break;
                case 2:
                    $pushlog_history = $this->PushLog->find('all',['conditions'=>['push_uid'=>'001', 'user_id'=>$user->id, 'readed'=>1]])->all();
                    if(!count($pushlog_history)>0){
                        $pushlog = $this->PushLog->newEntity();
                        $pushdata = ['push_uid'=>'001', 'user_id'=>$user->id, 'readed'=>1];
                        $pushlog = $this->PushLog->patchEntity($pushlog, $pushdata);
                        // $this->PushLog->save($pushlog);
                        $push = true;
                        $place = 2;
                    }
                break;
                case 3:
                    $pushlog_history = $this->PushLog->find('all',['conditions'=>['push_uid'=>'001', 'user_id'=>$user->id, 'readed'=>1]])->all();
                    if(!count($pushlog_history)>0){
                        $pushlog = $this->PushLog->newEntity();
                        $pushdata = ['push_uid'=>'001', 'user_id'=>$user->id, 'readed'=>1];
                        $pushlog = $this->PushLog->patchEntity($pushlog, $pushdata);
                        // $this->PushLog->save($pushlog);
                        $push = true;
                        $place = 3;
                    }
                break;
                case 4:
                    $pushlog_history = $this->PushLog->find('all',['conditions'=>['push_uid'=>'001', 'user_id'=>$user->id, 'readed'=>1]])->all();
                    if(!count($pushlog_history)>0){
                        $pushlog = $this->PushLog->newEntity();
                        $pushdata = ['push_uid'=>'001', 'user_id'=>$user->id, 'readed'=>1];
                        $pushlog = $this->PushLog->patchEntity($pushlog, $pushdata);
                        // $this->PushLog->save($pushlog);
                        $push = true;
                        $place = 4;
                    }
                break;
                default:
                break;
            endswitch;

            if($user->role_id == 4):
                $push_configs = [
                    'exist'=>true,
                    'name'=>'new_warning',
                    'title'=>'PARABÉNS!',
                    'value'=>0,
                    'subtitle'=>'Você e sua equipe impressionaram nas vendas e garantiram o '.$place.'º lugar em novembro. Devido aos feriados de fim de ano, alguns vales poderão chegar até a primeira quinzena de janeiro.',
                    'description'=>'',
                    'background'=>$place==1?'#00985B':'#edf4f3', //edf4f3
                    'color'=>$place==1?'#5DFFB6':'#00985B',
                    'image_type'=>'external',
                    'image'=>'http://dev2.3aww.com.br/lafargemaua/img/'.$place.'-ranking.png',
                    'action'=>'Ranking',
                    'button_label'=>'Acompanhar Ranking',
                    'number_ranking' => 0,
                ];
            elseif($user->role_id == 6):
                $push_configs = [
                    'exist'=>true,
                    'name'=>'new_warning',
                    'title'=>'ATENÇÃO!',
                    'value'=>0,
                    'subtitle'=>'O último módulo de capacitação estará disponível em dezembro. Fique ligado, pois os 100 primeiros balconistas a concluírem todos os módulos ganharão um prêmio extra no valor de R$ 200, via Vale Presente. Não perca essa chance!',
                    'description'=>'',
                    'background'=>'#FCB415',
                    'color'=>'#E07C00',
                    'image_type'=>'external',
                    'image'=>'http://dev2.3aww.com.br/lafargemaua/img/push_100primeiros.png',
                    'action'=>'Ranking',
                    'button_label'=>'Acompanhar Ranking',
                    'number_ranking' => 0,
                ];
            endif;
            
        elseif($push_warning):
            $push_configs = [
                'exist'=>true,
                'name'=>'new_warning',
                'title'=>'AVISO!',
                'value'=>$place,
                'subtitle'=>'Em função dos feriados de novembro, a entrega de alguns cartões pode atrasar. Se você ainda não recebeu o seu, tudo bem! Ele está a caminho!',
                'description'=>'',
                'color'=>'#FCAD00',
                'image'=>'4-ranking',
                'action'=>'Ranking',
                'button_label'=>'Acompanhar Ranking',
                'number_ranking' => $place
            ];
        endif;

        // notification push
        $pushlog_history = $this->PushLog->find('all',['conditions'=>['push_uid'=>'001', 'user_id'=>$user->id, 'readed'=>1]])->all();
        if(!count($pushlog_history)>0){
            $pushlog = $this->PushLog->newEntity();
            $pushdata = ['push_uid'=>'001', 'user_id'=>$user->id, 'readed'=>1];
            $pushlog = $this->PushLog->patchEntity($pushlog, $pushdata);
            // $this->PushLog->save($pushlog);
            $push = true;
            $place = 5;
        }

        $this->set([
            'success' => true,
            'user' => [
                'username' => $identity['username'],
                'name' => $identity['name'],
                'email' => $user->email, 
                'loja' => $user->store->name,
                'phone' => $user->phone,
                'ranking' => $stores[$store_key]['ranking'],
                'pontuacao' => $user->store->total,
                'role_id' => $user->role->id,
                'role' => $user->role->name,
                'cpf' => $user->cpf,
            ],
            'store' => [
                'name' => $user->store->name,
                'points' => $user->store->total,
                'ranking' => $stores[$store_key]['ranking'],
            ],
            'sales' => [
                'quantity'=>isset($user->store->sales[0])?$user->store->sales[0]->quantity:0,
                'goal'=>isset($user->store->sales[0])?$user->store->sales[0]->goal:0,
                'month'=>isset($user->store->sales[0])?$user->store->sales[0]->month:0,
                'month_name'=>isset($user->store->sales[0])?$month[$user->store->sales[0]->month]:0,
                'percent'=> $percent,
                'year'=>'2018',
                'message' => "Quase lá"
            ],
            'points' => $user->store->points,
            'post' => [
                'id'=>$post->id,
                'title'=>$post->title,
                'description'=>$post->description,
                'url'=>$post->url,
                'image'=>$url.$post->files[0]->filename,
            ],
            'page' => [
                'id'=>$page->id,
                'slug'=>$page->slug,
                'title'=>$page->title,
                'description'=>$page->description,
                'url'=>$page->url,
                'movie_url'=>$page->content,
                'video_url'=>$page->url,
            ],
            'rules' => [
                'id'=>$rules->id,
                'slug'=>$rules->slug,
                'title'=>$rules->title,
                'description'=>$rules->description,
                'content'=>$rules->content,
                'url'=>$rules->url,
            ],
            'push' => $push_configs,
            'banner'=>'http://dev2.3aww.com.br/lafargemaua/img/lafarge_banner.png',
            
            'configs' => [
                'last_update' => isset($sale_base)?$sale_base->created:0,
                'clean_cache' => false,
            ],
            '_serialize' => ['success', 'user', 'store', 'points', 'sales', 'post', 'rules', 'push', 'configs','page','banner']
            // '_serialize' => ['push']
            ]
        );
    }

    public function basics(){
        $this->loadModel('Users');
        $identity = $this->Auth->identify();
        $user = $this->Users->get($identity['id'], ['contain'=> 'Roles']);

        $this->set([
            'success' => true,
            'user' => [
                'id'=>$user->id,
                'name'=>$user->name,
                'username'=>$user->username,
                'role'=>strtolower($user->role->name),
                'force_login'=>$user->force_login
            ],
            '_serialize' => ['success', 'user']
            ]
        );
    }
    
    public function posts(){
        $this->loadModel('Users');
        $identity = $this->Auth->identify();
        
        $this->set([
            'success' => true,
            'post' => [
                'id'=>$post->id,
                'title'=>$post->title,
                'description'=>$post->description,
                'url'=>$post->url,
                'image'=>$url.$post->files[0]->filename,
            ],
            '_serialize' => ['success', 'post']
            ]
        );
    }

    public function ranking(){
        $this->loadModel('Users');
        $identity = $this->Auth->identify();
        $user = $this->Users->get($identity['id'], ['contain'=>['Stores.Sales'=>['sort'=>'month DESC'], 'Stores.Points', 'Roles']]);
        // $stores = $this->Users->Stores->find('all', ['order'=>['total DESC'], 'conditions'=>['Stores.category'=>$user->store->category]])->all()->toArray();
        
        $this->loadModel('Stores');
        // $stores = $this->Stores->getAllRanking($user->store->category);
        $stores = $this->Stores->fetchRankingByMonth($user->store->category, 10);
        
        $store_key = null;
        
        foreach($stores as $key=>$store):
            $stores[$key]['ranking'] = $key + 1;
            $store_key = $store['id']==$user['store_id']?$key:$store_key;
        endforeach;
        // die(debug($stores));

        $this->set([
            'success' => true,
            'my_store' => $stores[$store_key],
            'stores' => $stores,
            '_serialize' => ['success', 'my_store', 'stores']
            ]
        );
    }

    public function general_ranking(){
        $this->loadModel('Users');
        $identity = $this->Auth->identify();
        $user = $this->Users->get($identity['id'], ['contain'=>['Stores.Sales'=>['sort'=>'month DESC'], 'Stores.Points', 'Roles']]);
        // $stores = $this->Users->Stores->find('all', ['order'=>['total DESC'], 'conditions'=>['Stores.category'=>$user->store->category]])->all()->toArray();
        
        $this->loadModel('Stores');
        // $stores = $this->Stores->getAllRanking($user->store->category);
        $stores = $this->Stores->fetchGeneralRanking($user->store->category);
        
        $store_key = null;
        
        foreach($stores as $key=>$store):
            $stores[$key]['ranking'] = $key + 1;
            $store_key = $store['id']==$user['store_id']?$key:$store_key;
        endforeach;
        // die(debug($stores));

        $this->set([
            'success' => true,
            'my_store' => $stores[$store_key],
            'stores' => $stores,
            '_serialize' => ['success', 'my_store', 'stores']
            ]
        );
    }

    public function contact(){
        $this->loadModel('Users');
        $identity = $this->Auth->identify();
        $user = $this->Users->get($identity['id']);

        $data = $this->request->data;
        $data['user_id'] = $user->id;

        $this->loadModel('Messages');
        $message = $this->Messages->newEntity();
        $message = $this->Messages->patchEntity($message, $data);
        $this->Messages->save($message);

        $this->set([
            'success' => true,
            'user' => $user,
            'data' => $data,
            '_serialize' => ['success', 'user', 'data']
            ]
        );
    }

    public function sendfeedback(){
        $this->loadModel('Users');
        $identity = $this->Auth->identify();
        $user = $this->Users->get($identity['id']);

        $data = $this->request->data;
        $data['user_id'] = $user->id;

        $this->loadModel('Questions');
        $question = $this->Questions->find('all', ['conditions'=>['id'=>$data['question_id']]])->first();
        $data['course_id'] = $question->course_id;

        $this->loadModel('Feedback');
        $feedback = $this->Feedback->newEntity();
        $feedback = $this->Feedback->patchEntity($feedback, $data);
        $this->Feedback->save($feedback);

        $this->set([
            'success' => true,
            'user' => $user,
            'data' => $data,
            '_serialize' => ['success', 'user', 'data']
            ]
        );
    }

    /** to pie */
    public function review($type=null){
        switch($type):
            default:
            case 'enabled_stores':
                $this->loadModel('Users');
                $count_all_stores = count($this->Users->find('all', ['conditions'=>['id >'=>10, 'role_id'=>4, 'active'=>1]])->all());
                $count_enabled_stores = count($this->Users->find('all', ['conditions'=>['id >'=>10, 'role_id'=>4, 'first_access'=>1, 'active'=>1]])->all());

                $this->set([
                    'success' => true,
                    'all_stores' => $count_all_stores,
                    'enabled_stores' => $count_enabled_stores,
                    '_serialize' => ['success', 'user', 'all_stores', 'enabled_stores']
                    ]
                );
            break;

            case 'module_user':
                $category_p = [];
                $category_m = [];
                $category_g = [];

                $category_p_all = [];
                $category_m_all = [];
                $category_g_all = [];

                $this->loadModel('Users');
                $count_users_cursed = $this->Users->find('all', ['conditions'=>['Users.id >'=>10, 'role_id'=>6, 'active'=>1, 'first_access'=>0], 'contain'=>['CourseProgress', 'Stores']])->all()->toArray();
                
                foreach($count_users_cursed as $key=>$user){
                    if($user->store->category=='p'){
                        array_push($category_p_all, $user);
                    }
                    else if($user->store->category=='m'){
                        array_push($category_m_all, $user);
                    }
                    else if($user->store->category=='g'){
                        array_push($category_g_all, $user);
                    }

                    if($user->course_progress == null){
                        unset($count_users_cursed[$key]);
                    }else{
                        if($user->store->category=='p'){
                            array_push($category_p, $user);
                        }
                        else if($user->store->category=='m'){
                            array_push($category_m, $user);
                        }
                        else if($user->store->category=='g'){
                            array_push($category_g, $user);
                        }
                    }
                }
                $count_users_cursed = count($count_users_cursed);

                $count_users = count($this->Users->find('all', ['conditions'=>['id >'=>10, 'role_id'=>6, 'first_access'=>0, 'active'=>1]])->all());

                $this->set([
                    'success' => true,
                    'users_cursed' => $count_users_cursed,
                    'count_users' => $count_users,
                    'amarelo' => count($category_p),
                    'verde' => count($category_m),
                    'preto' => count($category_g),
                    'amarelo_all' => count($category_p_all),
                    'verde_all' => count($category_m_all),
                    'preto_all' => count($category_g_all),
                    '_serialize' => ['success', 'user', 'users_cursed', 'count_users', 'amarelo', 'verde', 'preto', 'amarelo_all', 'verde_all', 'preto_all']
                    ]
                );
            break;
        endswitch;
    }
}