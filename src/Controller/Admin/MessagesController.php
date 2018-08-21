<?php
namespace App\Controller\Admin;

use App\Controller\AppControllerAdmin;

class MessagesController extends AppController
{
  public function index()
  {
    $messages = $this->paginate($this->Messages,
      ['order'=>['Messages.id DESC'],
      'contain'=>['Users.Stores', 'Users.Roles'],
    ]);
    $messages = $messages->toArray();
    // die(debug($messages));
    $this->set(compact('messages'));
    $this->set('_serialize', ['messages']);
  }


  public function delete($id = null){
    $this->request->allowMethod(['post', 'delete']);
    $message = $this->Messages->get($id);
    if ($this->Messages->delete($message)) {
      $this->Flash->success(__('The message has been deleted.'));
    } else {
      $this->Flash->error(__('The message could not be deleted. Please, try again.'));
    }
    return $this->redirect(['action' => 'index']);
  }

    public function naolida($id = null)
  {
    $message = $this->Messages->get($id);
    $naolida=0;
    $message->status=$naolida;
    $this->Messages->save($message);
    $post_data = ['Message.read'=>0];
    $table = $this->Messages->patchEntity($message, $post_data);
    $this->Messages->save($table);  //update record
    return $this->redirect(['action' => 'index']);
  }

  public function read($id = null)
  {
    $message = $this->Messages->get($id, ['contain'=>['Users.Stores', 'Users.Roles']]);
    // die(debug($message));
    $lida=1;
    $message->status=$lida;
    $this->set('message', $message);
    $this->set('_serialize', ['message']);
    $post_data = ['Message.read'=>1];
    $table = $this->Messages->patchEntity($message, $post_data);
    $this->Messages->save($table);  //update record
  }

  public function return(){
    return $this->redirect(['action' => 'index']);
  }
}