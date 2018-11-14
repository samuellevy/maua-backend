<?php
namespace App\Controller\Admin;

use App\Controller\AppControllerAdmin;

class PagesController extends AppController
{
  public function home(){

  }

  public function index()
  {
    $pages = $this->paginate($this->Pages, [

    ]);

    $this->set(compact('pages'));
    $this->set('_serialize', ['pages']);
  }


  public function view($id = null)
  {
    $page = $this->Pages->get($id, [
      'contain' => []
    ]);

    $this->set('page', $page);
    $this->set('_serialize', ['page']);
  }

  public function add()
  {
    $page = $this->Pages->newEntity();
    if ($this->request->is('post')) {
      $page = $this->Pages->patchEntity($page, $this->request->getData(),[
        'associated' => [
          'Files'
        ],
      ]);
      if ($this->Pages->save($page)) {
        $this->Flash->success(__('Salvo com sucesso.'));

        return $this->redirect(['action' => 'index']);
      }
      $this->Flash->error(__('Não pôde ser salvo.'));
    }
    $this->set(compact('page'));
    $this->set('_serialize', ['page']);
  }

  public function edit($id = null)
  {
    $page = $this->Pages->get($id, [
      'contain' => ['Files']
    ]);
    if ($this->request->is(['patch', 'post', 'put'])) {
      $page = $this->Pages->patchEntity($page, $this->request->getData(),[
        'associated' => [
          'Files'
        ]
      ]);
        
      if ($this->Pages->save($page)) {
        $this->Flash->success(__('Salvo com sucesso.'));

        return $this->redirect(['action' => 'index']);
      }
      $this->Flash->error(__('Não pôde ser salvo.'));
    }
    $this->set(compact('page'));
    $this->set('_serialize', ['page']);
  }


  public function delete($id = null)
  {
    $this->request->allowMethod(['post', 'delete']);
    $page = $this->Pages->get($id);
    if ($this->Pages->delete($page)) {
      $this->Flash->success(__('Removido com sucesso.'));
    } else {
      $this->Flash->error(__('Não pôde ser removido.'));
    }

    return $this->redirect(['action' => 'index']);
  }

  // atualizar pontuacao
  public function updatePoints(){
    $this->loadModel('Stores');
    $stores = $this->Stores->find('all', ['contain'=>['Points'=>['conditions'=>['month'=>11]]]])->all()->toArray();

    foreach($stores as $key=>$store){
      $total = 0;
      foreach($store->points as $point){
        $total += $point->point;
      }
      $stores[$key]->total = $total;
      $entity = $this->Stores->get($store->id);
      $entity = $this->Stores->patchEntity($entity, $store->toArray());
      $this->Stores->save($entity);
    }
    
    $this->Flash->success(__('Atualizado com sucesso!'));
    return $this->redirect(['controller'=>'sales','action' => 'load']);
  }


  public function updateCourseProgress(){
    /**
     * 1 - identificar quantidade de usuarios por loja
     * 2 - identifica quantidade de course progress
     * 3 - se for igual, roda por course_progress e verifica sse os usuarios tem active = 1
     */

    $this->loadModel('Stores');
    $this->loadModel('Points');
    $stores = $this->Stores->find('all', ['contain'=>['Users'=>['conditions'=>['Users.active'=>true,'Users.role_id'=>6]], 'Users.CourseProgress'=>['conditions'=>['CourseProgress.course_id'=>4]]]])->all();
    $store_active=0;
    $cp_active_users=0;
    $coursed_stores=0;

    foreach($stores as $store){
      $count_active_users = count($store->users);
      $pointed = false;
      $counted_cp_store = 0;

      foreach($store->users as $user){
        $count_cp = count($user->course_progress);
        if($count_cp>0){
          $cp_active_users++;
          $counted_cp_store++;
        }
      }
      
      if($count_active_users>0){
        if($counted_cp_store == $count_active_users){
          $coursed_stores++; //registra

          $data = ['title'=>'Todos os funcionários concluíram o módulo', 'point'=>25, 'user_id'=>$user->id, 'store_id'=>$user->store_id, 'type'=>'completed_module', 'month'=> 11, 'status'=>1];
          // die(debug($data));
          $point = $this->Points->newEntity();
          $point = $this->Points->patchEntity($point, $data);
          $this->Points->save($point);

        }
        echo($counted_cp_store . ' - ' . $count_active_users);
        echo("</br>");
        $store_active++;
      }
    }
    echo("</br>");
    echo('Lojas pontuantes:' . $coursed_stores);
    echo("</br>");
    echo('Usuarios ativos:' . $cp_active_users);
    echo("</br>");
    echo('Lojas ativas' . $store_active);
    // die(debug($stores));


    die(debug('Chegou aqui'));
  }

  public function updateUsersPoints(){
    $this->loadModel('Stores');
    $this->loadModel('Users');
    $this->loadModel('Points');
    $stores = $this->Stores->find('all', ['contain'=>['Users'=>['conditions'=>['Users.active'=>true,'Users.role_id'=>6]]]])->all();
    

    foreach($stores as $store){
      if(count($store->users)>0){
        $owner = $this->Users->find('all', ['conditions'=>['Users.active'=>true, 'Users.role_id'=>4, 'Users.store_id'=>$store->id]])->first();
        $data = ['title'=>'Cadastro de funcionários', 'point'=>20, 'user_id'=>$owner->id, 'store_id'=>$store->id, 'type'=>'new_user', 'month'=> 10, 'status'=>1];
        // die(debug($data));
        $point = $this->Points->newEntity();
        $point = $this->Points->patchEntity($point, $data);
        $this->Points->save($point);

      }
    }

    die(debug($stores));
  }

  public function endMonth($month=null){
    $this->loadModel('Stores');
    $this->loadModel('Points');
    $stores = $this->Stores->find('all', ['contain'=>['Users'=>['conditions'=>['Users.role_id'=>4]]],'conditions'=>['Stores.id >'=>10]])->all();

    foreach($stores as $key=>$store){
      if(isset($store->users[0])){
        $store_properties = $this->Stores->getMyRanking($store->category, $store->id, date('m')-1);
        echo($store->id . ' - ' . $store_properties['position']."\n");
        $data = ['title'=>'Outubro '.$store_properties['position'].'º lugar/'.$store_properties['total'].' pts', 'point'=>0, 'user_id'=>$store->users[0]->id, 'store_id'=>$store->id, 'type'=>'module_closure', 'month'=> $month, 'status'=>1];
        $point = $this->Points->newEntity();
        $point = $this->Points->patchEntity($point, $data);
        $this->Points->save($point);
      }
    }

    die(debug($stores));
  }

  public function fixCategories(){

  }
}
