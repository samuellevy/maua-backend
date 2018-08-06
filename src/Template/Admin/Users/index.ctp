<div class="container-fluid">
  <div class="row">
    <div class="col-md-12">
      <div class="card">
        <div class="header">
          <h4 class="title">Usuários</h4>
          <p class="category">Lista de todos os usuários</p>
        </div>
        <?= $this->Form->create("Ticket");?>
          <div class="input-group input-group-sm role-search">
          
          <?php $roles[0]='Todos'; ksort($roles);?>
            <?= $this->Form->input("role_search", ['type'=>'select', 'options'=>$roles, 'class'=>"form-control", 'label'=>false]);?>
            <div class="input-group-btn">
              <button type="submit" class="btn btn-default"><i class="fa fa-search"></i></button>
            </div>
          </div>
        <?= $this->Form->end(); ?>
        <div class="content table-responsive table-full-width">
          <table class="table table-hover table-striped">
            <thead>
              <tr>
                  <th scope="col"><?= $this->Paginator->sort('id') ?></th>
                  <th scope="col"><?= $this->Paginator->sort('name', ['label'=>'Nome']) ?></th>
                  <th scope="col"><?= $this->Paginator->sort('email') ?></th>
                  <th scope="col"><?= $this->Paginator->sort('username') ?></th>
                  <th scope="col"><?= $this->Paginator->sort('role_id', ['label'=>'Função']) ?></th>
                  <th scope="col" class="actions"><?= __('opções') ?></th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($users as $user): ?>
              <tr>
                  <td><?= $this->Number->format($user->id) ?></td>
                  <td><?= h($user->name) ?></td>
                  <td><?= h($user->email) ?></td>
                  <td><?= h($user->username) ?></td>
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
