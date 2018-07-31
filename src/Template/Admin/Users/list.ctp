<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="content table-responsive table-full-width">
                <?php foreach ($stores as $store): ?>
                <?php if(count($store->users)>1): ?>
                <div class="card">
                    <h4 style="padding: 10px;">#<?= $store->id; ?> - <?= $store->name; ?></h4>
                    <table class="table table-hover table">
                        <thead>
                            <th>ID</th>
                            <th>Nome</th>
                            <th>E-mail</th>
                        </thead>
                        <tbody>
                            <?php foreach ($store->users as $user): ?>
                            
                            <tr class="<?=$user->first_access==0?'green':'';?>">
                                <td style="width: 5%"><?= $user->id; ?></td>
                                <td style="width: 40%"><?= $user->name; ?></td>
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