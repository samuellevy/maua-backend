<?php $site_name = "VER | Gerenciador"; ?>
<!DOCTYPE html>
<html>
<head>
    <?= $this->Html->charset() ?>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
    <meta http-equiv="cache-control" content="no-cache" />
    <meta http-equiv="expires" content="0" />
    <meta http-equiv="expires" content="Tue, 01 Jan 1980 1:00:00 GMT" />
    <meta http-equiv="pragma" content="no-cache" />

    <title>
        <?=$site_name?> - <?= $this->fetch('title') ?>
    </title>
    <link rel="icon" href="<?=$this->Url->image('Site.../images/favicon.png');?>">

    <?= $this->Html->css('bootstrap.min.css') ?>
    <?= $this->Html->css('animate.min.css') ?>
    <?= $this->Html->css('light-bootstrap-dashboard.css') ?>
    <link href="http://maxcdn.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.min.css" rel="stylesheet">
    <?= $this->Html->css('pe-icon-7-stroke.css') ?>
    <?= $this->Html->css('main.css?build='.uniqid()) ?>

    <?= $this->fetch('meta') ?>
    <?= $this->fetch('css') ?>
    <?= $this->fetch('script') ?>
</head>
<body>
  <div class="wrapper">

        <div class="content">
          <?= $this->fetch('content') ?>
        </div>

  </div>
</body>
</html>
