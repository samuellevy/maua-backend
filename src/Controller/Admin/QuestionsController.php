<?php
namespace App\Controller\Admin;

use App\Controller\AppControllerAdmin;

class QuestionsController extends AppController
{
  public function index()
  {
    $questions = $this->paginate($this->Questions, [

    ]);

    $this->set(compact('questions'));
    $this->set('_serialize', ['questions']);
  }

  public function add()
	{
		$question = $this->Questions->newEntity();
		if ($this->request->is('post')) {
			$question = $this->Questions->patchEntity($question, $this->request->getData());
			if ($this->Questions->save($question)) {
				$this->Flash->success(__('Sua pergunta foi enviada com sucesso.'));
			}else{
				$this->Flash->error(__('Não foi possível enviar sua pergunta. Verifique os campos.'));
			}
    }
    
		$this->set(compact(['question']));
		$this->set('_serialize', ['question']);
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
}
