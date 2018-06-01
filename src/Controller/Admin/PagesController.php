<?php
namespace App\Controller\Admin;

use App\Controller\AppControllerAdmin;

class PagesController extends AppController
{

  public function home(){

  }

  public function index()
  {
    $pages = $this->paginate($this->Pages, [

    ]);

    $this->set(compact('pages'));
    $this->set('_serialize', ['pages']);
  }


  public function view($id = null)
  {
    $page = $this->Pages->get($id, [
      'contain' => []
    ]);

    $this->set('page', $page);
    $this->set('_serialize', ['page']);
  }

  public function add()
  {
    $page = $this->Pages->newEntity();
    if ($this->request->is('post')) {
      $page = $this->Pages->patchEntity($page, $this->request->getData());
      $page->type = 1;
      if ($this->Pages->save($page)) {
        $this->Flash->success(__('Salvo com sucesso.'));

        return $this->redirect(['action' => 'index']);
      }
      $this->Flash->error(__('Não pôde ser salvo.'));
    }
    $this->set(compact('page'));
    $this->set('_serialize', ['page']);
  }

  public function edit($id = null)
  {
    $page = $this->Pages->get($id, [
      'contain' => [
        'PagesComponents'=>[
           'sort'=>['sort'=>'asc']
        ]
      ]
    ]);
    if ($this->request->is(['patch', 'post', 'put'])) {
      $page = $this->Pages->patchEntity($page, $this->request->getData());
        // die(debug($page));
      if ($this->Pages->save($page)) {
        $this->Flash->success(__('Salvo com sucesso.'));

        return $this->redirect(['action' => 'index']);
      }
      $this->Flash->error(__('Não pôde ser salvo.'));
    }
    $this->set(compact('page'));
    $this->set('_serialize', ['page']);
  }


  public function delete($id = null)
  {
    $this->request->allowMethod(['post', 'delete']);
    $page = $this->Pages->get($id);
    if ($this->Pages->delete($page)) {
      $this->Flash->success(__('Removido com sucesso.'));
    } else {
      $this->Flash->error(__('Não pôde ser removido.'));
    }

    return $this->redirect(['action' => 'index']);
  }
}
