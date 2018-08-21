<div class="container-fluid">
  <div class="row">
    <div class="col-md-12">
      <div class="card">
        <div class="header">
          <div class="email-options">
            <?= $this->Form->postLink(__('Marcar como Não Lida'), ['action' => 'naolida', $message->id], ['class'=>'button-default']) ;?>
            <?= $this->Form->postLink(__('Excluir'), ['action' => 'delete', $message->id], ['confirm' => __('Are you sure you want to delete # {0}?', $message->id), 'class'=>'button-default']) ;?>
          </div>
          <p><span style="color: rgba(0, 0, 0, 0.5)">De: </span><?=$message->user->name;?></p>
          <p><span style="color: rgba(0, 0, 0, 0.5)">Loja: </span><?=$message->user->store->name;?></p>
          <p><span style="color: rgba(0, 0, 0, 0.5)">Email: </span><?=$message->user->email;?></p>
          <p><span style="color: rgba(0, 0, 0, 0.5)">Telefone: </span><?=$message->user->phone;?></p>
        </div>
        <div class="content">
          <div class="row">
            <div class="col-md-12">
              <p><span style="color: rgba(0, 0, 0, 0.5)">Mensagem: </span></p>
              <h4 style="margin: 10px 0 30px;"><?=$message->text;?></h4>
              <p><span style="color: rgba(0, 0, 0, 0.5)">Recebida em <?=$message->created;?></span></p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <?= $this->Form->postLink(__('Voltar'), ['action' => 'return'], ['class'=>'button-default']) ;?>
</div>
