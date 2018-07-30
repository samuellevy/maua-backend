<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="header">
                    <h4 class="title"><?= h($user->name) ?></h4>
                </div>
                <div class="content table-responsive table-full-width">
                        
                        <table class="table table-hover table-striped">
                                <tr>
                                    <th scope="row"><?= __('Email') ?></th>
                                    <td><?= h($user->email) ?></td>
                                </tr>
                                <tr>
                                    <th scope="row"><?= __('Username') ?></th>
                                    <td><?= h($user->username) ?></td>
                                </tr>
                                <tr>
                                    <th scope="row"><?= __('Password') ?></th>
                                    <td><?= h($user->password) ?></td>
                                </tr>
                                <tr>
                                    <th scope="row"><?= __('Role') ?></th>
                                    <td><?= $user->has('role') ? $this->Html->link($user->role->name, ['controller' => 'Roles', 'action' => 'view', $user->role->id]) : '' ?></td>
                                </tr>
                                <!-- <tr>
                                    <th scope="row"><?= __('Id') ?></th>
                                    <td><?= $this->Number->format($user->id) ?></td>
                                </tr> -->
                                <tr>
                                    <th scope="row"><?= __('Created') ?></th>
                                    <td><?= h($user->created) ?></td>
                                </tr>
                                <tr>
                                    <th scope="row"><?= __('Modified') ?></th>
                                    <td><?= h($user->modified) ?></td>
                                </tr>
                        </table>
                </div>
            </div>
        </div>
    </div>
</div>
