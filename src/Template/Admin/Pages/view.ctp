<?php
/**
  * @var \App\View\AppView $this
  */
?>
<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('Edit Affiliate'), ['action' => 'edit', $affiliate->id]) ?> </li>
        <li><?= $this->Form->postLink(__('Delete Affiliate'), ['action' => 'delete', $affiliate->id], ['confirm' => __('Are you sure you want to delete # {0}?', $affiliate->id)]) ?> </li>
        <li><?= $this->Html->link(__('List Affiliates'), ['action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Affiliate'), ['action' => 'add']) ?> </li>
    </ul>
</nav>
<div class="affiliates view large-9 medium-8 columns content">
    <h3><?= h($affiliate->name) ?></h3>
    <table class="vertical-table">
        <tr>
            <th scope="row"><?= __('Name') ?></th>
            <td><?= h($affiliate->name) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Country') ?></th>
            <td><?= h($affiliate->country) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Email') ?></th>
            <td><?= h($affiliate->email) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Id') ?></th>
            <td><?= $this->Number->format($affiliate->id) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Created') ?></th>
            <td><?= h($affiliate->created) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Modified') ?></th>
            <td><?= h($affiliate->modified) ?></td>
        </tr>
    </table>
    <div class="row">
        <h4><?= __('Description') ?></h4>
        <?= $this->Text->autoParagraph(h($affiliate->description)); ?>
    </div>
</div>
