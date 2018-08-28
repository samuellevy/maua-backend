<div class="container-fluid">
  <div class="row">
    <div class="col-md-12">
      <div class="card">
        <div class="header">
          <h4 class="title">Usuários</h4>
          <p class="category">Lista de todos os usuários</p>
        </div>

        <div class="search-box">
          <?= $this->Form->create("Ticket");?>
            <div class="input-group input-group-sm search-field">
            
              <?php $roles[0]='Todos'; ksort($roles);?>
              <?= $this->Form->input("role_search", ['type'=>'select', 'options'=>$roles, 'class'=>"form-control", 'label'=>false]);?>
              <div class="input-group-btn">
                <button type="submit" class="btn btn-default"><i class="fa fa-search"></i></button>
              </div>
            </div>
          <?= $this->Form->end(); ?>
          <?= $this->Form->create("SearchID");?>
            <div class="input-group input-group-sm search-field">
              <?= $this->Form->input("id_search", ['type'=>'number', 'class'=>"form-control", 'label'=>false, 'placeholder'=>'Procurar por ID']);?>
              <div class="input-group-btn">
                <button type="submit" class="btn btn-default"><i class="fa fa-search"></i></button>
              </div>
            </div>
          <?= $this->Form->end(); ?>
        </div>
          
          
        <div class="content table-responsive table-full-width">
          <table class="table table-hover table-striped">
            <thead>
              <tr>
                  <th scope="col"><?= $this->Paginator->sort('id') ?></th>
                  <th scope="col"><?= $this->Paginator->sort('store_id', ['label'=>'ID da Loja']) ?></th>
                  <th scope="col"><?= $this->Paginator->sort('name', ['label'=>'Nome da Loja']) ?></th>
                  <th scope="col"><?= $this->Paginator->sort('name', ['label'=>'Nome']) ?></th>
                  <th scope="col"><?= $this->Paginator->sort('email') ?></th>
                  <th scope="col"><?= $this->Paginator->sort('username') ?></th>
                  <th scope="col"><?= $this->Paginator->sort('phone') ?></th>
                  <th scope="col"><?= $this->Paginator->sort('role_id', ['label'=>'Função']) ?></th>
                  <th scope="col" class="actions"><?= __('opções') ?></th>
              </tr>
            </thead>
            <tbody>
              <?php $counter = 0; ?>
              <?php foreach ($users as $user): ?>
                <?php $counter++; ?>
                <tr>
                    <td><?= $this->Number->format($user->id) ?></td>
                    <?php foreach ($stores as $store):?>
                      <?php if($store->id == $user->store_id):?>
                        <td><?= h($store->id) ?></td>
                        <td><?= h($store->name) ?></td>
                      <?php endif;?>
                    <?php endforeach;?>
                    <td><?= h($user->name) ?></td>
                    <td><?= h($user->email) ?></td>
                    <td><?= h($user->username) ?></td>
                    <td><?= h($user->phone) ?></td>
                    <td><?= $user->role->name;?></td>
                    <td class="actions">
                        <?= $this->Html->link(__('Ver'), ['action' => 'view', $user->id]) ?>
                        <?= $this->Html->link(__('Editar'), ['action' => 'edit', $user->id]) ?>
                        <?= $this->Form->postLink(__('Remover'), ['action' => 'delete', $user->id], ['confirm' => __('Are you sure you want to delete # {0}?', $user->id)]) ?>
                    </td>
                </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
          <?php if($counter == 0):?>
            <p style="text-align: center;font-size: 16px;color: #929292;">ID não encontrada!</p>
          <?php endif; ?>

          <div class="paginator">
              <ul class="pagination">
                  <?= $this->Paginator->first('<< ' . __('primeiro')) ?>
                  <?= $this->Paginator->prev('< ' . __('anterior')) ?>
                  <?= $this->Paginator->numbers() ?>
                  <?= $this->Paginator->next(__('próximo') . ' >') ?>
                  <?= $this->Paginator->last(__('último') . ' >>') ?>
                  <li><a href="<?= $this->Url->build(["controller" => "Users", "action" => "add"]);?>">Novo</a></li>
              </ul>
              <p><?= $this->Paginator->counter(['format' => __('Page {{page}} of {{pages}}, showing {{current}} record(s) out of {{count}} total')]) ?></p>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
