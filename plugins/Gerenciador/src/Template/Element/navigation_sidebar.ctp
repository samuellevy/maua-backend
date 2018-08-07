<ul class="nav">
  <li>
    <a href="<?= $this->Url->build(["controller" => "courses","action" => "index"]);?>" <?=$params['controller']=='courses'?"class='active'":""?>>
      <i class="pe-7s-note2"></i>
      <p>Cursos</p>
    </a>
  </li>
  
  
  <li>
    <a href="<?= $this->Url->build(["controller" => "questions","action" => "index"]);?>" <?=$params['controller']=='questions'?"class='active'":""?>>
      <i class="pe-7s-share"></i>
      <p>Questões</p>
    </a>
  </li>
  
  <li>
    <a href="<?= $this->Url->build(["controller" => "stores","action" => "index"]);?>" <?=$params['controller']=='stores'?"class='active'":""?>>
      <i class="pe-7s-copy-file"></i>
      <p>Lojas</p>
    </a>
  </li>
  
  <li>
    <a href="<?= $this->Url->build(["controller" => "posts","action" => "index"]);?>" <?=$params['controller']=='posts'?"class='active'":""?>>
      <i class="pe-7s-folder"></i>
      <p>Blog</p>
    </a>
  </li>
  
  <li>
    <a href="<?= $this->Url->build(["controller" => "sales","action" => "load"]);?>" <?=$params['controller']=='Sales'?"class='active'":""?>>
      <i class="pe-7s-note2"></i>
      <p>Vendas</p>
    </a>
  </li>
  
  <li>
    <a href="<?= $this->Url->build(["controller" => "pages","action" => "index"]);?>" <?=$params['controller']=='Team'?"class='active'":""?>>
      <i class="pe-7s-users"></i>
      <p>Páginas</p>
    </a>
  </li>
  
  <li class="sidebar-dropdown" data-id="1">
    <a>
      <i class="pe-7s-news-paper"></i>
      <p>Usuários</p>
      <i class="i-absolute pe-7s-angle-right i-absolute-transform"></i>
    </a>
    <ul class="sidebar-dropdown">
      <li>
        <a href="<?= $this->Url->build(["controller" => "users","action" => "index"]);?>" <?=$params['controller']=='Users'?"class='active'":""?>>
          <i class="pe-7s-angle-right"></i>
          <p>Todos</p>
        </a>
      </li>
      <li>
        <a href="<?= $this->Url->build(["controller" => "users","action" => "list"]);?>" <?=$params['controller']=='Users'?"class='active'":""?>>
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
    <a href="<?= $this->Url->build(["controller" => "CSV","action" => "index"]);?>" <?=$params['controller']=='CSV'?"class='active'":""?>>
      <i class="pe-7s-download"></i>
      <p>Gerar Relatório de Vendas</p>
    </a>
  </li>
  
</ul>