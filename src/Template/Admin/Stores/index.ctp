<div class="container-fluid">
  <div class="row">
    <div class="col-md-12">
      <div class="card">
        <div class="header">
          <h4 class="title">Perguntas</h4>
          <p class="category">Lista de todos os itens</p>
        </div>
        <div class="content table-responsive table-full-width">
          <table class="table table-hover table-striped">
            <thead>
              <tr>
                <th scope="col"><?= $this->Paginator->sort('id') ?></th>
                <th scope="col"><?= $this->Paginator->sort('name', ['label'=>'Título']) ?></th>
                <th scope="col"><?= $this->Paginator->sort('category', ['label'=>'Categoria']) ?></th>
                <th scope="col"><?= $this->Paginator->sort('status', ['label'=>'Status']) ?></th>
                <th scope="col" class="actions"><?= __('Opções') ?></th>
              </tr>
            </thead>
            <tbody>
              <?php //die(debug($stores));?>
              <?php foreach ($stores as $store): ?>
                <tr>
                  <td><?= $this->Number->format($store->id) ?></td>
                  <td><?= $store->name ?></td>
                  <td><?= $store->category ?></td>
                  <td>
                    <div class="switch__container">
                      <input id="switch-flat-s-<?=$store->id?>" class="switch switch--flat" type="checkbox" <?=$store->status==1?"checked":""?> onclick="changeStatus('stores','status',<?=$store->id?>);">
                      <label for="switch-flat-s-<?=$store->id?>" class="general-switch"></label>
                    </div>
                  </td>
                  <td class="actions">
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
              <li><a href="<?= $this->Url->build(["controller" => "stores", "action" => "add"]);?>">Novo</a></li>
            </ul>
            <p><?= $this->Paginator->counter(['format' => __('Page {{page}} of {{pages}}, showing {{current}} record(s) out of {{count}} total')]) ?></p>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
