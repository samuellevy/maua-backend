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
            <div class="col-md-4">
              <div class="form-group">
                <?php echo $this->Form->control('name', ['class'=>'form-control', 'label'=>'Nome']);?>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-md-4">
              <div class="form-group">
                <?php echo $this->Form->control('title', ['class'=>'form-control', 'label'=>'Título']);?>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-md-3">
              <div class="form-group">
                <?php echo $this->Form->control('slug', ['class'=>'form-control', 'label'=>'Slug']);?>
              </div>
            </div>
          </div>
           <div class="row">
            <div class="col-md-3">
              <div class="form-group">
                <?php echo $this->Form->control('type', ['class'=>'form-control', 'label'=>'Tipo da página', 'options'=>[1=>'Com menu e footer', 2=>'Newsletter', 3=>'Vazia']]);?>
              </div>
            </div>
          </div>
          
          <div class="row">
            <div class="col-md-10">
              <div class="form-group">
                <label>Components</label><br/>
                <figure class="form-group">
                  <ul id="sortable" style="list-style:none;padding:0;">
                    <li style="cursor:move" data-sort="0">
                      <?php echo $this->Form->hidden('pages_components.0.id', ['class'=>'form-control']);?>
                      <?php echo $this->Form->hidden('pages_components.0.sort', ['class'=>'form-control sortfield']);?>
                      <?php echo $this->Form->hidden('pages_components.0.component_id', ['class'=>'form-control sortfield', 'value'=>1]);?>
                      <?php echo $this->Form->hidden('pages_components.0.sort', ['class'=>'form-control sortfield', 'value'=>1]);?>
                      <?php echo $this->Form->control('pages_components.0.content', ['class'=>'form-control ckeditor', 'label'=>'Conteúdo']);?>
                    </li>
                  </ul>
                </figure>
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
