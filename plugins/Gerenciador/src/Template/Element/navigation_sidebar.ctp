<ul class="nav">
  <li>
    <a href="<?= $this->Url->build(["controller" => "Pages","action" => "index"]);?>" <?=$params['controller']=='Pages'?"class='active'":""?>>
      <i class="pe-7s-albums"></i>
      <p>Páginas</p>
    </a>
  </li>
  

  <li>
    <a href="<?= $this->Url->build(["controller" => "Convenios","action" => "index"]);?>" <?=$params['controller']=='Convenios'?"class='active'":""?>>
      <i class="pe-7s-share"></i>
      <p>Convênios</p>
    </a>
  </li>
  
  <li>
    <a href="<?= $this->Url->build(["controller" => "ServicesTabs","action" => "index"]);?>" <?=$params['controller']=='ServicesTabs'?"class='active'":""?>>
      <i class="pe-7s-copy-file"></i>
      <p>Abas de Serviços</p>
    </a>
  </li>

  <li>
    <a href="<?= $this->Url->build(["controller" => "Services","action" => "index"]);?>" <?=$params['controller']=='Services'?"class='active'":""?>>
      <i class="pe-7s-folder"></i>
      <p>Serviços</p>
    </a>
  </li>

  <li>
    <a href="<?= $this->Url->build(["controller" => "Specialties","action" => "index"]);?>" <?=$params['controller']=='Specialties'?"class='active'":""?>>
      <i class="pe-7s-note2"></i>
      <p>Especialidades</p>
    </a>
  </li>
  
  <li>
    <a href="<?= $this->Url->build(["controller" => "Team","action" => "index"]);?>" <?=$params['controller']=='Team'?"class='active'":""?>>
      <i class="pe-7s-users"></i>
      <p>Team</p>
    </a>
  </li>

  <li>
    <a href="<?= $this->Url->build(["controller" => "Departments","action" => "index"]);?>" <?=$params['controller']=='Departments'?"class='active'":""?>>
      <i class="pe-7s-portfolio"></i>
      <p>Departamentos</p>
    </a>
  </li>
  
  <li>
    <a href="<?= $this->Url->build(["controller" => "Timeline","action" => "index"]);?>" <?=$params['controller']=='Timeline'?"class='active'":""?>>
      <i class="pe-7s-network"></i>
      <p>Timeline</p>
    </a>
  </li>
  
  <li>
    <a href="<?= $this->Url->build(["controller" => "Users","action" => "index"]);?>" <?=$params['controller']=='Users'?"class='active'":""?>>
      <i class="pe-7s-user"></i>
      <p>Usuários</p>
    </a>
  </li>
  
</ul>
