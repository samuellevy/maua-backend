<ul class="nav">

  <li>
    <a href="<?= $this->Url->build(["controller" => "Messages","action" => "index"]);?>" <?=$params['controller']=='Messages'?"class='active'":""?>>
      <i class="pe-7s-mail"></i>
      <p>Mensagens</p>
    </a>
  </li>

  <li>
    <a href="<?= $this->Url->build(["controller" => "Courses","action" => "index"]);?>" <?=$params['controller']=='Courses'?"class='active'":""?>>
      <i class="pe-7s-note2"></i>
      <p>Cursos</p>
    </a>
  </li>
  
  
  <li>
    <a href="<?= $this->Url->build(["controller" => "Questions","action" => "index"]);?>" <?=$params['controller']=='Questions'?"class='active'":""?>>
      <i class="pe-7s-share"></i>
      <p>Questões</p>
    </a>
  </li>
  
  <li>
    <a href="<?= $this->Url->build(["controller" => "Stores","action" => "index"]);?>" <?=$params['controller']=='Stores'?"class='active'":""?>>
      <i class="pe-7s-copy-file"></i>
      <p>Lojas</p>
    </a>
  </li>
  
  <li>
    <a href="<?= $this->Url->build(["controller" => "Posts","action" => "index"]);?>" <?=$params['controller']=='Posts'?"class='active'":""?>>
      <i class="pe-7s-folder"></i>
      <p>Blog</p>
    </a>
  </li>
  
  <li>
    <a href="<?= $this->Url->build(["controller" => "Sales","action" => "load"]);?>" <?=$params['controller']=='Sales'?"class='active'":""?>>
      <i class="pe-7s-note2"></i>
      <p>Vendas</p>
    </a>
  </li>
  
  <li>
    <a href="<?= $this->Url->build(["controller" => "Pages","action" => "index"]);?>" <?=$params['controller']=='Pages'?"class='active'":""?>>
      <i class="pe-7s-users"></i>
      <p>Páginas</p>
    </a>
  </li>
  
  <li class="sidebar-dropdown" data-id="1">
    <a <?=$params['controller']=='Users'?"class='active'":""?>>
      <i class="pe-7s-news-paper"></i>
      <p>Usuários</p>
      <i class="i-absolute pe-7s-angle-right i-absolute-transform"></i>
    </a>
    <ul class="sidebar-dropdown">
      <li>
        <a href="<?= $this->Url->build(["controller" => "Users","action" => "index"]);?>" <?=$params['controller']=='Users'&&$params['action']=="index"?"class='active'":""?>>
          <i class="pe-7s-angle-right"></i>
          <p>Todos</p>
        </a>
      </li>
      <li>
        <a href="<?= $this->Url->build(["controller" => "Users","action" => "list"]);?>" <?=$params['controller']=='Users'&&$params['action']=="list"?"class='active'":""?>>
          <i class="pe-7s-angle-right"></i>
          <p>Listar</p>
        </a>
      </li>
    </ul>
  </li>

    <li>
    <a href="<?= $this->Url->build(["controller" => "report","action" => "index"]);?>" <?=$params['controller']=='Report'?"class='active'":""?>>
      <i class="pe-7s-note2"></i>
      <p>Relatório Quiz</p>
    </a>
  </li>

  <li>
    <a href="<?= $this->Url->build(["controller" => "Report","action" => "resultados"]);?>">
      <i class="pe-7s-download"></i>
      <p>Gerar CSV Resultados</p>
    </a>
  </li>

  <li>
    <a href="<?= $this->Url->build(["controller" => "Report","action" => "participantes"]);?>">
      <i class="pe-7s-download"></i>
      <p>Gerar CSV Participantes</p>
    </a>
  </li>
  
</ul>