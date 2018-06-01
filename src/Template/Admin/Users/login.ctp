<?php
/**
* @var \App\View\AppView $this
*/
?>
<div style="width: 400px; margin: auto;">
  <?= $this->Form->create() ?>
  <div class="row">
    <div class="col-md-12">
      <div class="card card-user">
        <div class="image">
          <?= $this->Html->image("Gerenciador.login-topbar.png");?>
        </div>
        <div class="content">
          <?php
          echo $this->Form->control('username', ['class'=>'form-control']);
          echo $this->Form->control('password', ['class'=>'form-control']);
          ?>
          <p>
            <?= $this->Flash->render() ?>
          </p>
        </div>
        <hr>
        <div class="text-center">
          <?= $this->Form->button(__('Login'), ['class'=>'btn btn-simple btn-fill vertical-indent', 'style'=>'margin: 0px 0px 5px 0;']) ?>
        </div>
      </div>
    </div>
  </div>
  <?= $this->Form->end() ?>
</div>
