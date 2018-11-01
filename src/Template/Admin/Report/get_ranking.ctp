<?php
/** retirar linha abaixo depois */
$month_name[8]='Agosto';
$month_name[9]='Setembro';
$month_name[10]='Outubro';
$month_name[11]='Novembro';
$month_name[12]='Dezembro';
?>
<div class="container-fluid">
  <div class="row">
    <div class="col-md-12">
      <div class="card">
        <div class="header">
          <h4 class="title">Ranking</h4>
        </div>
        <div class="content table-responsive table-full-width">
            <div class="tabs-module">
                <div class='tabs-header'>
                    <ul>
                    <?php foreach($months as $key=>$month):?>
                        <li data-id='<?=$month['name']?>'><?=$month_name[$month['name']]?></li>
                    <?php endforeach;?>
                    </ul>
                </div>
                
                <div >
                    <table class="table table-hover table-striped">
                        <thead>
                        <tr>
                            <th scope="col" style='width: 75px;'>#</th>
                            <th scope="col">ID</th>
                            <th scope="col">Loja</th>
                            <th scope="col">Pontuação</th>
                            <th scope="col">Percentual de vendas</th>
                        </tr>
                        </thead>

                    
                        <?php foreach($ranking as $month=>$item):?>
                            <tbody class="card" data-id='<?=$month?>'>
                                <?php foreach($item as $key=>$store):?>
                                    <tr>
                                        <td>#<?=++$key?></td>
                                        <td><?=$store['id'];?></td>
                                        <td><?=$store['name'];?></td>
                                        <td><?=$store['sum_points'];?></td>
                                        <td><?=$store['percentage'];?>%</td>
                                    </tr>
                                <?php endforeach;?>
                            </tbody>
                        <?php endforeach;?>
                        
                    </table>
                </div>
            </div>
        </div>
      </div>
    </div>
  </div>
</div>
