<?php
namespace App\Controller\Admin;

use App\Controller\AppControllerAdmin;

class SalesController extends AppController
{
  public function percent($value, $total){
    if($value!=null){
      $percent = ($value*100)/$total;
    }
    else{
      $percent = 0;
    }
    // die(debug($value));
    
    return round($percent, 0);
  }

  public function load(){
    $sales = $this->Sales->newEntity();
    if ($this->request->is('post')) {
      $stores = Array();
      $data = $this->request->getData();
      
      $file = fopen($data['file']['tmp_name'], 'r');
      while (($line = fgetcsv($file)) !== false)
      {
        $stores[] = $line;
      }
      fclose($file);
      
      $sales = [];
      $this->loadModel('Stores');
      $this->loadModel('Users');
      $all_stores = $this->Stores->find('all');
      foreach($stores as $key=>$store){
        if($key==0){
          
        }else{
          array_push($sales, ['store_id'=>$store[0], 'cnpj'=>$store[3], 'month'=>8, 'goal' => $store[4], 'quantity' => $store[5]]);
          array_push($sales, ['store_id'=>$store[0], 'cnpj'=>$store[3], 'month'=>9, 'goal' => $store[6], 'quantity' => $store[7]]);
          array_push($sales, ['store_id'=>$store[0], 'cnpj'=>$store[3], 'month'=>10, 'goal' => $store[8], 'quantity' => $store[9]]);
          array_push($sales, ['store_id'=>$store[0], 'cnpj'=>$store[3], 'month'=>11, 'goal' => $store[10], 'quantity' => $store[11]]);
          array_push($sales, ['store_id'=>$store[0], 'cnpj'=>$store[3], 'month'=>12, 'goal' => $store[12], 'quantity' => $store[13]]);

          if(!$this->search_store($all_stores, $store[0])){
            $store[1] = str_replace('Amarelo', 'p', $store[1]);
            $store[1] = str_replace('Verde', 'm', $store[1]);
            $store[1] = str_replace('Preto', 'g', $store[1]);
            $newStore = [
              'id'=>$store[0],
              'name'=>$store[2],
              'category'=>$store[1],
              'total'=>0,
              'status'=>1
            ];
            $storeEntity = $this->Stores->newEntity();
            $storeEntity = $this->Stores->patchEntity($storeEntity, $newStore);
            $this->Stores->save($storeEntity);
            
            $newUser = [
              'username'=>$store[3],
              'password'=>'quementendevende',
              'first_access'=>1,
              'active'=>1,
              'role_id'=>4,
              'store_id'=>$store[0],
            ];
            $userEntity = $this->Users->newEntity();
            $userEntity = $this->Users->patchEntity($userEntity, $newUser);
            $this->Users->save($userEntity);
            // die(debug($newStore));
          }

          if($this->percent($store[13], $store[12]) >= 100 && $this->percent($store[13], $store[12]) <= 115){
            $this->processPoints(['action'=>'setPoint', 'store_id'=>$store[0], 'points'=>50, 'percent'=>$this->percent($store[13], $store[12]), 'type'=>'meta', 'month'=>'12']);
          }
          if($this->percent($store[13], $store[12]) > 115 && $this->percent($store[13], $store[12]) <= 145){
            $this->processPoints(['action'=>'setPoint', 'store_id'=>$store[0], 'points'=>75, 'percent'=>$this->percent($store[13], $store[12]), 'type'=>'meta', 'month'=>'12']);
          }
          if($this->percent($store[13], $store[12]) > 145){
            $this->processPoints(['action'=>'setPoint', 'store_id'=>$store[0], 'points'=>100, 'percent'=>$this->percent($store[13], $store[12]), 'type'=>'meta', 'month'=>'12']);
          }
        }
      }
      
      
      $this->Sales->truncate();
      try{
        foreach($sales as $sale){
          $salesEntity = $this->Sales->newEntity();
          $salesEntity = $this->Sales->patchEntity($salesEntity, $sale);
          $this->Sales->save($salesEntity);
        }
        $sales = $this->Sales->newEntity();
        return $this->redirect(['controller'=>'pages','action' => 'updatePoints']);
      }catch(Exception $e){
        $this->Flash->success(__('Houve um erro.'));
      }
    }
    
    $this->set(compact(['sales']));
    $this->set('_serialize', ['sales']);
    
  }

  public function search_store($stores, $store_id){
    $return = false;
    foreach ($stores as $key => $item):
      if($item->id == $store_id){
        $return = true;
      }
    endforeach;

    return $return;
  }
  
  public function processPoints($arguments=null){
    // $arguments = ['setPoint', 1, 2];
    
    switch($arguments['action']):
      case 'setPoint':
        $this->loadModel('Users');
        $lojista = $this->Users->find('all', ['conditions'=>['Users.store_id'=>$arguments['store_id'],'Users.active'=>true,'Users.role_id'=>4]])->first();
        $pointing = $arguments['points'];

        $this->loadModel('Points');
        $actual_points = $this->Points->find('all', ['conditions'=>['Points.store_id'=>$arguments['store_id'],'Points.type'=>$arguments['type'],'Points.month'=>$arguments['month']]])->first();
        // die(debug($actual_points));

        $this->loadModel('Points');
        $data['title'] = 'Meta mensal atingida '.$arguments['percent'].'%';
        $data['point'] = $pointing;
        $data['user_id'] = $lojista->id;
        $data['store_id'] = $arguments['store_id'];
        $data['type'] = 'meta';
        $data['month'] = 12;

        if($actual_points!=null){
          $point = $this->Points->get($actual_points->id);
        }
        else{
          $point = $this->Points->newEntity();
        }
        
        $point = $this->Points->patchEntity($point, $data);
        $this->Points->save($point);
        
        $this->loadModel('Stores');
        $store = $this->Stores->get($arguments['store_id']);
        $total_store = $store->total;
        $store = $this->Stores->patchEntity($store, ['total'=>$total_store+$pointing]);
        // die(debug($store->total));
        $this->Stores->save($store);
        return true;
      break;
    endswitch;
  }
}