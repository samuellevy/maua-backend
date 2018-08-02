<?php
namespace App\Controller\Admin;

use App\Controller\AppControllerAdmin;

class SalesController extends AppController
{
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
      foreach($stores as $key=>$store){
        if($key==0){

        }else{
          array_push($sales, ['store_id'=>$store[0],'month'=>8, 'goal' => $store[4], 'quantity' => $store[5]]);
          array_push($sales, ['store_id'=>$store[0],'month'=>9, 'goal' => $store[6], 'quantity' => $store[7]]);
          array_push($sales, ['store_id'=>$store[0],'month'=>10, 'goal' => $store[8], 'quantity' => $store[9]]);
          array_push($sales, ['store_id'=>$store[0],'month'=>11, 'goal' => $store[10], 'quantity' => $store[11]]);
          array_push($sales, ['store_id'=>$store[0],'month'=>12, 'goal' => $store[12], 'quantity' => $store[13]]);
        }
      }

      $this->Sales->truncate();
      try{
        foreach($sales as $sale){
          $salesEntity = $this->Sales->newEntity();
          $salesEntity = $this->Sales->patchEntity($salesEntity, $sale);
          $this->Sales->save($salesEntity);
        }
        $this->Flash->success(__('Atualizado com sucesso!'));
        $sales = $this->Sales->newEntity();
      }catch(Exception $e){
        $this->Flash->success(__('Houve um erro.'));
      }
    }

    $this->set(compact(['sales']));
		$this->set('_serialize', ['sales']);
  }
}