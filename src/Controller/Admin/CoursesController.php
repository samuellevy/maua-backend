<?php
namespace App\Controller\Admin;

use App\Controller\AppControllerAdmin;

class CoursesController extends AppController
{
  public function index()
  {
    $courses = $this->paginate($this->Courses, [

    ]);

    $this->set(compact('courses'));
    $this->set('_serialize', ['courses']);
  }

  public function add()
	{
		$course = $this->Courses->newEntity();
		if ($this->request->is('post')) {
      $course = $this->Courses->patchEntity($course, $this->request->getData());
      // die(debug($course));
			if ($this->Courses->save($course)) {
        $this->Flash->success(__('Sua pergunta foi enviada com sucesso.'));
        $this->redirect(['action'=>'edit',$course->id]);
			}else{
				$this->Flash->error(__('Não foi possível enviar sua pergunta. Verifique os campos.'));
			}
    }
    
		$this->set(compact(['course']));
		$this->set('_serialize', ['course']);
  }
  
  public function edit($id = null)
  {
      $course = $this->Courses->get($id, [
      ]);
      if ($this->request->is(['patch', 'post', 'put'])) {
          $course = $this->Courses->patchEntity($course, $this->request->getData());

          if ($this->Courses->save($course)) {
              $this->Flash->success(__('Salvo com sucesso.'));
              return $this->redirect(['action' => 'index']);
          }
          $this->Flash->error(__('Não pôde ser salvo.'));
      }
      $this->set(compact('course'));
      $this->set('_serialize', ['course']);
  }


  public function delete($id = null)
  {
    $this->request->allowMethod(['post', 'delete']);
    $course = $this->Courses->get($id);
    if ($this->Courses->delete($course)) {
      $this->Flash->success(__('Removido com sucesso.'));
    } else {
      $this->Flash->error(__('Não pôde ser removido.'));
    }

    return $this->redirect(['action' => 'index']);
  }

  /** AJAX METHODS */
	public function changeStatus($id){
		$this->autoRender = false;
		$entity = $this->Courses->get($id);
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
		
		$post_data = ['Courses.'.$field=>0];
		
		$table = $this->Courses->patchEntity($entity, $post_data);
		$this->Courses->save($table);  //update record
	}

}