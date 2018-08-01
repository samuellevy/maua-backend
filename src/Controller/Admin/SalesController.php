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

      die(debug($stores));
    }

    $array1 = array("bola", "quadrado", "triangulo");
    $array2 = array("esfera", "quadrado", "triangulo");

    $result = array_diff($array1, $array2);
    print_r($result);

    $this->set(compact(['sales']));
		$this->set('_serialize', ['sales']);
  }
}