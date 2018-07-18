


            <div class="container-fluid">
  <div class="row">
    <div class="col-md-12">
      <div class="card">
        <div class="header">
          <h4 class="title">Novo Post</h4>
        </div>
        <div class="content">
          <?= $this->Form->create($post, ['type'=>'file']) ?>

          <div class="row">
            <div class="col-md-4">
              <div class="form-group">
                <?php echo $this->Form->control('title', ['class'=>'form-control', 'label'=>'Nome', 'maxlength'=>'85']);?>
              </div>
            </div>
          </div>

          <div class="row">
            <div class="col-md-12">
              <div class="form-group">
                <?php echo $this->Form->control('url', ['class'=>'form-control', 'label'=>'Url']);?>
              </div>
            </div>
          </div>


          <div class="row">
            <div class="col-md-12">
              <div class="form-group">
                <?php echo $this->Form->control('description', ['class'=>'form-control', 'label'=>'Descrição', 'maxlength'=>'400']);?>
              </div>
            </div>
          </div>

          <div class="row">
            <div class="col-md-3">
              <div class="form-group">
                <label>Imagem</label><br/>
                <figure class="form-box-img">
                  <?php if(isset($post['files'][0])):?>
                    <button type="button" class="btn btn-danger btn-fill remove" data-uid="<?=$post['files'][0]['id'];?>">Remover</button>
                    <?php echo $this->Html->image('../uploads/files/'.$post['files'][0]['filename'], ['class'=>'form-img', 'data-uid'=>$post['files'][0]['id']]);?>
                  <?php else:?>
                    <img class="img-rounded form-img" src="http://via.placeholder.com/234x234">
                  <?php endif;?>
                  <?php echo $this->Form->file('files.0.filename', ['class'=>'form-file']);?>
                  <?php echo $this->Form->hidden('files.0.entity', ['class'=>'form-file', 'value'=>'Post']);?>
                  <?php echo $this->Form->hidden('files.0.obs', ['class'=>'form-file', 'value'=>'Miniatura']);?>
                </figure>
              </div>
            </div>

          <div class="row">
            <div class="col-md-9">
              <div class="form-group">
                <?= $this->Form->button(__('Send'), ['class'=>'btn btn-info btn-fill pull-right']) ?>
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
