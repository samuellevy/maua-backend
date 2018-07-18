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
    <a href="<?= $this->Url->build(["controller" => "Specialties","action" => "index"]);?>" <?=$params['controller']=='Specialties'?"class='active'":""?>>
      <i class="pe-7s-note2"></i>
      <p>x Vendas</p>
    </a>
  </li>
  
  <li>
    <a href="<?= $this->Url->build(["controller" => "pages","action" => "index"]);?>" <?=$params['controller']=='Team'?"class='active'":""?>>
      <i class="pe-7s-users"></i>
      <p>Páginas</p>
    </a>
  </li>
  
  <li>
    <a href="<?= $this->Url->build(["controller" => "Users","action" => "index"]);?>" <?=$params['controller']=='Users'?"class='active'":""?>>
      <i class="pe-7s-user"></i>
      <p>Usuários</p>
    </a>
  </li>
  
</ul>
