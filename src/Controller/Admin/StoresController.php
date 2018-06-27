<?php
namespace App\Controller\Admin;

use App\Controller\AppControllerAdmin;

class StoresController extends AppController
{
  public function index()
  {
    $stores = $this->paginate($this->Stores, [

    ]);

    $this->set(compact('stores'));
    $this->set('_serialize', ['stores']);
  }

  public function add()
	{
		$store = $this->Stores->newEntity();
		if ($this->request->is('post')) {
      $store = $this->Stores->patchEntity($store, $this->request->getData());
      // die(debug($store));
			if ($this->Stores->save($store)) {
        $this->Flash->success(__('Sua pergunta foi enviada com sucesso.'));
        $this->redirect(['action'=>'edit',$store->id]);
			}else{
				$this->Flash->error(__('Não foi possível enviar sua pergunta. Verifique os campos.'));
			}
    }
    
		$this->set(compact(['store']));
		$this->set('_serialize', ['store']);
  }
  
  public function edit($id = null)
  {
      $store = $this->Stores->get($id, [
      ]);
      if ($this->request->is(['patch', 'post', 'put'])) {
          $store = $this->Stores->patchEntity($store, $this->request->getData());

          if ($this->Stores->save($store)) {
              $this->Flash->success(__('Salvo com sucesso.'));
              return $this->redirect(['action' => 'index']);
          }
          $this->Flash->error(__('Não pôde ser salvo.'));
      }
      $this->set(compact('store'));
      $this->set('_serialize', ['store']);
  }


  public function delete($id = null)
  {
    $this->request->allowMethod(['post', 'delete']);
    $store = $this->Stores->get($id);
    if ($this->Stores->delete($store)) {
      $this->Flash->success(__('Removido com sucesso.'));
    } else {
      $this->Flash->error(__('Não pôde ser removido.'));
    }

    return $this->redirect(['action' => 'index']);
  }

  /** AJAX METHODS */
	public function changeStatus($id){
		$this->autoRender = false;
		$entity = $this->Stores->get($id);
		$status = $this->request->data['status'];
		$field = $this->request->data['field'];
		// echo $field;
		
		if($status == 'toggle'){
			if($entity[$field]==1){
				$status=0;
			}else{
				$status=1;
			}
		}
		
		$entity[$field]=$status;
		
		$post_data = ['Stores.'.$field=>0];
		
		$table = $this->Stores->patchEntity($entity, $post_data);
		$this->Stores->save($table);  //update record
	}

}