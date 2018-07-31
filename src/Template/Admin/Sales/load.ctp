<div class="container-fluid">
  <div class="row">
    <div class="col-md-12">
      <div class="card">
        <div class="header">
          <h4 class="title">Atualizar vendas</h4>
        </div>
        <div class="content">
          <?= $this->Form->create($sales, ['type'=>'file']) ?>
          
          <div class="row">
            <div class="col-md-12">
              <div class="form-group">
              <?php echo $this->Form->control('file', ['type'=>'file']);?>
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
