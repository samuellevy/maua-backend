<div class="container-fluid">
  <div class="row">
    <div class="col-md-12">
      <div class="card">
        <div class="header">
          <h4 class="title">Cursos</h4>
          <p class="category">Lista de todos os itens</p>
        </div>
        <div class="content table-responsive table-full-width">
          <table class="table table-hover table-striped">
            <thead>
              <tr>
                <th scope="col"><?= $this->Paginator->sort('id') ?></th>
                <th scope="col"><?= $this->Paginator->sort('title', ['label'=>'Título']) ?></th>
                <th scope="col"><?= $this->Paginator->sort('subtitle', ['label'=>'Subtítulo']) ?></th>
                <th scope="col"><?= $this->Paginator->sort('status', ['label'=>'Status']) ?></th>
                <th scope="col" class="actions"><?= __('Opções') ?></th>
              </tr>
            </thead>
            <tbody>
              <?php //die(debug($stores));?>
              <?php foreach ($stores as $store): ?>
              <?php if(isset($store->users[0]->username)):?>
                <tr>
                <td><?= $store->name ?></td>
                  <td><?= $store->users[0]->username ?></td>
                  <td><?= $this->Number->format($store->id) ?></td>
                  <td>
                  <?php foreach($store->users as $user):?>
                   <?=$user->name;?>, 
                  <?php endforeach;?>
                  </td>
                </tr>
              <?php endif;?>
              <?php endforeach; ?>
            </tbody>
          </table>

         
        </div>
      </div>
    </div>
  </div>
</div>
