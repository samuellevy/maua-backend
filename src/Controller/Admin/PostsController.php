<?php
namespace App\Controller\Admin;
use App\Controller\AppControllerAdmin;

/**
* Posts Controller
*
* @property \App\Model\Table\PostsTable $Posts
*/
class PostsController extends AppController
{
  public function index()
  {
    $posts = $this->paginate($this->Posts, [
      'contain'=>[
        
      ],
      'order'=>[
        'id'=>'DESC'
      ]
    ]);

    //die(debug($posts));

    $this->set(compact('posts'));
    $this->set('_serialize', ['posts']);
  }

  public function add()
  {
    $post = $this->Posts->newEntity();

    if ($this->request->is('post')) {
      $data = $this->request->getData();
      $data['author_id']=$this->Auth->user('id');
      $data['created'] = date("Y-m-d H:i:s");  ;
     
      $post = $this->Posts->patchEntity($post, $data,[
        'associated' => [
          'Files'
        ]
      ]);

      if ($this->Posts->save($post)) {
        $this->Flash->success(__('Salvo com sucesso.'));

        return $this->redirect(['action' => 'index']);
      }
      $this->Flash->error(__('Não pôde ser salvo.'));
    }
    $this->set(compact('post'));
    $this->set('_serialize', ['post']);
  }

  public function view($id = null)
  {
    $post = $this->Posts->get($id, [
      'contain' => [
        'Files'
      ]
    ]);

    $this->set('post', $post);
    $this->set('_serialize', ['post']);
  }

  public function edit($id = null)
  {
    $post = $this->Posts->get($id, [
      // 'fields'=>'id',
      'contain' => [
        'Files'
      ]
    ]);

    
    if ($this->request->is(['patch', 'post', 'put'])) {
      $post = $this->Posts->patchEntity($post, $this->request->getData());

      foreach($post->files as $key_file=>$file){
        if($file->filename==''){
          unset($post->files[$key_file]);
        }
      }

      if ($this->Posts->save($post)) {
        $this->Flash->success(__('Salvo com sucesso.'));

        return $this->redirect(['action' => 'index']);
      }
      $this->Flash->error(__('Não pôde ser salvo.'));
    }

    // die(debug($post));
    $this->set(compact('post'));
    $this->set('_serialize', ['post']);
  }

  public function delete($id = null)
  {
    $this->request->allowMethod(['post', 'delete']);
    $post = $this->Posts->get($id);
    if ($this->Posts->delete($post)) {
      $this->Flash->success(__('Removido com sucesso.'));
    } else {
      $this->Flash->error(__('Não pôde ser removido.'));
    }

    return $this->redirect(['action' => 'index']);
  }

  /* AJAX methods */

  public function delete_file($file_id = null, $post_id=null){
    $this->autoRender = false;
    $entity = $this->Posts->Files->get($file_id);

    if($this->Posts->Files->delete($entity)){
      echo('1');
    }
  }
}
