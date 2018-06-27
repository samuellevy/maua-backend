<?php
namespace App\Controller\Admin;

use App\Controller\AppControllerAdmin;

class QuestionsController extends AppController
{
  public function index()
  {
    $questions = $this->paginate($this->Questions, [
      'contain'=>['Courses']
    ]);

    $this->set(compact('questions'));
    $this->set('_serialize', ['questions']);
  }

  public function add()
	{
    $question = $this->Questions->newEntity();
    $courses = $this->Questions->Courses->find('list');

		if ($this->request->is('post')) {
      $question = $this->Questions->patchEntity($question, $this->request->getData());
      // die(debug($question));
			if ($this->Questions->save($question)) {
        $this->Flash->success(__('Sua pergunta foi enviada com sucesso.'));
        $this->redirect(['action'=>'edit',$question->id]);
			}else{
				$this->Flash->error(__('Não foi possível enviar sua pergunta. Verifique os campos.'));
			}
    }
    
		$this->set(compact(['question', 'courses']));
		$this->set('_serialize', ['question', 'courses']);
  }
  
  public function edit($id = null)
  {
      $question = $this->Questions->get($id, [
          'contain' => ['Options']
      ]);
      $courses = $this->Questions->Courses->find('list');

      if ($this->request->is(['patch', 'post', 'put'])) {
          $question = $this->Questions->patchEntity($question, $this->request->getData());
          // die(debug($question));
          foreach($question->options as $key=>$option){
            if($option->title == ''){
              $entity = $this->Questions->Options->get($option->id);
              $this->Questions->Options->delete($entity);
              unset($question->options[$key]);
            }
          }
          if ($this->Questions->save($question)) {
              $this->Flash->success(__('Salvo com sucesso.'));
              return $this->redirect(['action' => 'index']);
          }
          $this->Flash->error(__('Não pôde ser salvo.'));
      }
      $this->set(compact('question', 'courses'));
      $this->set('_serialize', ['question', 'courses']);
  }


  public function delete($id = null)
  {
    $this->request->allowMethod(['post', 'delete']);
    $question = $this->Questions->get($id);
    if ($this->Questions->delete($question)) {
      $this->Flash->success(__('Removido com sucesso.'));
    } else {
      $this->Flash->error(__('Não pôde ser removido.'));
    }

    return $this->redirect(['action' => 'index']);
  }

  /** AJAX METHODS */
	public function changeStatus($id){
		$this->autoRender = false;
		$entity = $this->Questions->get($id);
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
		
		$post_data = ['Questions.'.$field=>0];
		
		$table = $this->Questions->patchEntity($entity, $post_data);
		$this->Questions->save($table);  //update record
	}

}
