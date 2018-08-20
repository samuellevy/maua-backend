<div class="container-fluid">
  <div class="row">
    <div class="col-md-12">
      <div class="card">
        <div class="header">
          <h4 class="title">Caixa de Mensagens</h4>
        </div>
        <div class="content table-responsive table-full-width">
          <table class="table table-hover table-striped messages">
            <!-- <thead>
              <tr>
                <th scope="col"><?= $this->Paginator->sort('id') ?></th>
                <th scope="col"><?= $this->Paginator->sort('email') ?></th>
                <th scope="col"><?= $this->Paginator->sort('phone') ?></th>
                <th scope="col"><?= $this->Paginator->sort('message') ?></th>
                <th scope="col"><?= $this->Paginator->sort('status') ?></th>
                <th scope="col"><?= $this->Paginator->sort('name') ?></th>
                <th scope="col"><?= $this->Paginator->sort('subject') ?></th>
                <th scope="col"><?= $this->Paginator->sort('institute') ?></th>
                <th scope="col"><?= $this->Paginator->sort('created') ?></th>
                <th scope="col" class="actions"><?= __('Actions') ?></th>
              </tr>
            </thead> -->
            <tbody>
              <?php foreach ($messages as $message): ?>

                  <tr <?=$message->status == 0?"class='unread'":""?> onclick="window.location.href='<?=$this->Url->build(['action' => 'read', $message->id]);?>'">
                    <?php if ($message->status == 0):?>
                      <td>Não lida</td>
                    <?php elseif ($message->status == 1):?>
                      <td>Lida</td>
                    <?php endif ?>

                    <td>
                    <?php 
                    if (strlen($message->text) > 90){
                      $str = substr($message->text, 0, 86) . '...';
                      echo $str;
                    } else{
                      echo $message->text;
                    }
                    ?>
                    </td>

                    <td>
                    <?php if(isset($message->user)):?>
                      <?= $message->user->name ?>
                      <?php else: echo "?";?>
                    <?php endif;?>
                    </td>

                    <td>
                    <?php if(isset($message->user)):?>
                      <?= $message->user->store->name; ?>
                      <?php else: echo "?";?>
                    <?php endif;?>
                    </td>

                    <td><?= $message->created ?></td>
                  </tr>
                </a>
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
            </ul>
            <p><?= $this->Paginator->counter(['format' => __('Page {{page}} of {{pages}}, showing {{current}} record(s) out of {{count}} total')]) ?></p>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
