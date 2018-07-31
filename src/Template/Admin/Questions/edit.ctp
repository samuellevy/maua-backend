<div class="container-fluid">
  <div class="row">
    <div class="col-md-12">
      <div class="card">
        <div class="header">
          <h4 class="title">Nova questão</h4>
        </div>
        <div class="content">
          <?= $this->Form->create($question) ?>
          
          <div class="row">
            <div class="col-md-12">
              <div class="form-group">
                <?php echo $this->Form->control('title', ['class'=>'form-control', 'label'=>'Título']);?>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-md-12">
              <div class="form-group">
                <?php echo $this->Form->control('explanation', ['class'=>'form-control', 'label'=>'Sobre resposta']);?>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-md-12">
              <div class="form-group">
                <?php echo $this->Form->hidden('points', ['class'=>'form-control', 'label'=>'Valor da questão', 'value'=>1]);?>
              </div>
            </div>
          </div>

          <div class="row">
            <div class="col-md-12">
              <div class="form-group">
                <?php echo $this->Form->control('course_id', ['class'=>'form-control', 'label'=>'Aula', 'options'=>$courses]);?>
              </div>
            </div>
          </div>
          
          <div class="row">
            <div class="col-md-12">
              <div class="form-group">
                <Label>Opções</Label>
                <?php echo $this->Form->hidden('value');?>
                <div class="options">
                  <?php foreach($question->options as $key=>$option):?>
                      <div class="option" data-id="<?=$key?>">
                        <input type="radio" name="value" value="<?=$key?>"
                        <?=$question->value==$key?'checked':'';?>/>
                        <input placeholder="Nova opção" class="form-control questionOption" name="options[<?=$key?>][title]" value="<?=$option->title?>"/>
                        <input type="hidden" name="options[<?=$key?>][id]" value="<?=$option->id?>"/>
                      </div>
                  <?php endforeach;?>

                </div>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-md-12">
              <div class="form-group">
                <button type="button" class="btn btn-fill" data-function="addNewOption">Adicionar nova opção</button>
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
