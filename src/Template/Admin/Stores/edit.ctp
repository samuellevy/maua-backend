<div class="container-fluid">
  <div class="row">
    <div class="col-md-12">
      <div class="card">
        <div class="header">
          <h4 class="title">Nova questão</h4>
        </div>
        <div class="content">
          <?= $this->Form->create($store) ?>
          
          <div class="row">
            <div class="col-md-12">
              <div class="form-group">
                <?php echo $this->Form->control('name', ['class'=>'form-control', 'label'=>'Título']);?>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-md-12">
              <div class="form-group">
                <?php echo $this->Form->control('category', ['class'=>'form-control', 'label'=>'Categoria', 'options'=>['p'=>'P','m'=>'M','g'=>'G']]);?>
              </div>
            </div>
          </div>
          
          <div class="row">
            <div class="col-md-12">
              <div class="form-group">
                <Label>Usuários</Label>
                <?php echo $this->Form->hidden('value');?>
                <div class="options">
                  <table class="table">
                    <tbody>
                      <?php foreach($store->users as $key=>$user):?>
                      <tr data-id="<?=$user->id;?>" class="user_block">
                        <td><?=$user->name?></td>
                        <td class="td-actions text-right">
                          <button model-id="<?=$store->id;?>"  data-id="<?=$user->id;?>" type="button" rel="tooltip" class="btn btn-danger btn-simple btn-link btn-remove">
                            <i class="fa fa-times"></i>
                          </button>
                        </td>
                      </tr>
                      <?php endforeach;?>
                    </tbody>
                  </table>
                </div>
              </div>
            </div>
          </div>
          
          <div class="row">
            <div class="col-md-12">
              <div class="form-group">
                <?= $this->Form->button(__('Send'), ['class'=>'btn btn-info btn-fill pull-left']) ?>
              </div>
            </div>
          </div>
          
          <div class="clearfix"></div>
          <?= $this->Form->end() ?>
        </div>
      </div>
    </div>
  </div>
</div>
