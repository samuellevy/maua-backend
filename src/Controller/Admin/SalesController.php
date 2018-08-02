<?php
namespace App\Controller\Admin;

use App\Controller\AppControllerAdmin;

class SalesController extends AppController
{
  public function load(){
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
      foreach($sales as $sale){
        $salesEntity = $this->Sales->newEntity();
        $salesEntity = $this->Sales->patchEntity($salesEntity, $sale);
        $this->Sales->save($salesEntity);
      }
      
      // foreach($stores as $key=>$store){
      //   if($key==0){

      //   }else{
      //     $stores[$key]['sales']=[];
      //     array_push($stores[$key]['sales'], ['month'=>8, 'goal' => $store[4], 'quantity' => $store[5]]);
      //     array_push($stores[$key]['sales'], ['month'=>9, 'goal' => $store[6], 'quantity' => $store[7]]);
      //     array_push($stores[$key]['sales'], ['month'=>10, 'goal' => $store[8], 'quantity' => $store[9]]);
      //     array_push($stores[$key]['sales'], ['month'=>11, 'goal' => $store[10], 'quantity' => $store[11]]);
      //     array_push($stores[$key]['sales'], ['month'=>12, 'goal' => $store[12], 'quantity' => $store[13]]);
      //     // $stores[$key]['sales']=['quantity'=>$store[4]];
      //   }
      // }

      // $sales->store_id = 2;
      // $this->Sales->truncate();
      // $this->Sales->save($sales);
      die(debug($sales));
      // die(debug($this->Sales->truncate()));
    }

    

    $array1 = array(0=>"bola", 1=>"quadrado", 2=>"triangulo");
    $array2 = array(0=>"esfera", 1=>"quadrado", 2=>"triangulo");
    $result = array_diff($array1, $array2);
    print_r($result);

    $this->set(compact(['sales']));
		$this->set('_serialize', ['sales']);
  }
}