<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="content table-responsive table-full-width">
                
                <?php 
                $registered_stores = 0;
                $registered_users = 0;
                $logged_users = 0;
                foreach ($stores as $store){
                    if(isset($store->users[0]) && $store->users[0]->first_access == 0){
                        $registered_stores++;
                        foreach ($store->users as $user){
                            if($user->role_id == 6){
                                $registered_users++;
                                if($user->first_access==0){
                                    $logged_users++;
                                }
                            }
                        }
                    }
                }
                ?>
                <div class="card" style="padding: 10px;">
                    <p style="margin: 5px;">Lojas Registradas: <span style="font-weight:bolder"><?= $registered_stores;?></span> <span style="padding: 0 10px">|</span> Funcionários Registradas: <span style="font-weight:bolder"><?= $registered_users;?></span> <span style="padding: 0 10px">|</span> Funcionários que logaram: <span style="font-weight:bolder"><?= $logged_users;?></span> </p>
                </div>
                
                <?php foreach ($stores as $store): ?>
                <?php if(isset($store->users[0]) && $store->users[0]->first_access == 0): ?>
                <div class="card">
                    <h4 style="padding: 10px;">#<?= $store->id; ?> - <?= $store->name; ?></h4>
                    <table class="table table-hover table">
                        <thead>
                            <th>ID</th>
                            <th>Nome</th>
                            <th>Função</th>
                            <th>E-mail</th>
                        </thead>
                        <tbody>
                            <?php foreach ($store->users as $user): ?>
                            
                            <tr class="<?=$user->first_access==0?'green ':'';?><?=$user->active==0?'red ':'';?><?=$user->role_id==4?'bolder':''?>">
                                <td style="width: 5%"><?= $user->id; ?></td>
                                <td style="width: 40%"><?= $user->name; ?></td>
                                <td style="width: 40%"><?= $user->role->name; ?></td>
                                <td style="width: 40%"><?= $user->email; ?></td>
                            </tr>
                            
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                <?php endif;?>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</div>