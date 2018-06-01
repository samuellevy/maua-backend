<div class="container-fluid">
  <div class="row">
    <div class="col-md-12">
      <div class="card">
        <div class="header">
          <h4 class="title">Adicionar usuário</h4>
        </div>
        <div class="content">
          <?= $this->Form->create() ?>
            <div class="row">
              <div class="col-md-5">
                <div class="form-group">
                  <?php echo $this->Form->control('name', ['class'=>'form-control', 'label'=>'Nome completo']);?>
                </div>
              </div>
              <div class="col-md-3">
                <div class="form-group">
                  <?php echo $this->Form->control('username', ['class'=>'form-control', 'label'=>'Nome de usuário']);?>
                </div>
              </div>
              <div class="col-md-4">
                <div class="form-group">
                  <?php echo $this->Form->control('email', ['class'=>'form-control', 'label'=>'E-mail do usuário']);?>
                </div>
              </div>
            </div>

            <div class="row">
              <div class="col-md-12">
                <div class="form-group">
                  <?php echo $this->Form->control('role_id', ['class'=>'form-control', 'label'=>'Atribuição','options' => $roles, 'empty' => true]); ?>
                </div>
              </div>
            </div>

            <div class="row">
              <div class="col-md-6">
                <div class="form-group">
                  <?php echo $this->Form->control('password', ['class'=>'form-control', 'label'=>'Senha']);?>
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group">
                  <?php echo $this->Form->control('confirm_password', ['class'=>'form-control', 'type'=>'password', 'label'=>'Confirme sua senha']);?>
                </div>
              </div>
            </div>

            <?= $this->Form->button(__('Enviar'), ['class'=>'btn btn-info btn-fill pull-right']) ?>

            <div class="clearfix"></div>
          <?= $this->Form->end() ?>
        </div>
      </div>
    </div>
  </div>
</div>
