<div class="container-fluid">
  <div class="row">
    <div class="col-md-12">
      <div class="card">
        <div class="header">
          <h4 class="title">Relatório</h4>
          <p class="category"></p>
        </div>
        <div class="content table-responsive table-full-width">
          <table class="table table-hover table-striped">
            <thead>
              <tr>
                  <th scope="col"><?= $this->Paginator->sort('id') ?></th>
                  <th scope="col"><?= $this->Paginator->sort('cnpj') ?></th>
                  <th scope="col"><?= $this->Paginator->sort('name') ?></th>
                  <th scope="col"><?= $this->Paginator->sort('balconistas') ?></th>
                  <th scope="col"><?= $this->Paginator->sort('terminaram o quiz') ?></th>
                  <th scope="col" class="actions"><?= __('opções') ?></th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($stores as $store): ?>
              <tr>
                  <td><?= $this->Number->format($store->id) ?></td>
                  <td><?= isset($store->users[0])?$store->users[0]->username : "-"; ?></td>
                  <td><?= $store->name; ?></td>
                  <td style="width: 25% !important;"><?php 
                  foreach ($store->users as $user):
                    if($user->role_id == 6){
                        echo $user->name.", ";
                    }endforeach;?>
                  </td>
                  <td style="width: 25% !important;"><?php 
                  foreach ($store->users as $user):
                    if(($user->role_id == 6) && (isset($user->course_progress[0])) && ($user->course_progress[0]->progress == 1)){
                      echo $user->name.", ";
                    } endforeach;?>
                  </td>
                  <td class="actions">
                      <?= $this->Html->link(__('Ver'), ['action' => 'view', $store->id]) ?>
                      <?= $this->Html->link(__('Editar'), ['action' => 'edit', $store->id]) ?>
                      <?= $this->Form->postLink(__('Remover'), ['action' => 'delete', $store->id], ['confirm' => __('Are you sure you want to delete # {0}?', $store->id)]) ?>
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