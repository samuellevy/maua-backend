<div class="container-fluid">
  <div class="row">
    <div class="col-md-12">
      <div class="card">
        <div class="header">
          <h4 class="title">Nova página</h4>
        </div>
        <div class="content">
          <?= $this->Form->create($page) ?>
          <div class="row">
            <div class="col-md-3">
              <div class="form-group">
                <?php echo $this->Form->control('slug', ['class'=>'form-control', 'label'=>'Slug']);?>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-md-4">
              <div class="form-group">
                <?php echo $this->Form->control('title', ['class'=>'form-control', 'label'=>'Nome']);?>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-md-4">
              <div class="form-group">
                <?php echo $this->Form->control('description', ['class'=>'form-control', 'label'=>'Título']);?>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-md-4">
              <div class="form-group">
                <?php echo $this->Form->control('content', ['class'=>'form-control', 'label'=>'Conteúdo']);?>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-md-4">
              <div class="form-group">
                <?php echo $this->Form->control('url', ['class'=>'form-control', 'label'=>'Url']);?>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-md-10">
              <div class="form-group">
                <?= $this->Form->button(__('Salvar'), ['class'=>'btn btn-info btn-fill pull-right']) ?>
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
