<!DOCTYPE HTML>
<html>
<head>
    <meta charset="utf-8">
    <title>Huracan</title>
    

    <!-- JS Files -->
    <?= $this->Html->script('jquery.min.js') ?>
    <?= $this->Html->script('iefix.js') ?>
    <?= $this->Html->script('ext-2.2/adapter/ext/ext-base.js') ?>
    <?= $this->Html->script('ext-2.2/ext-all-debug.js') ?>
    <?= $this->Html->script('ext-2.2/Ext.ux.grid.RowActions.js') ?>
    <?= $this->Html->script('ext-2.2/Ext.ux.Toast.js') ?>
    <?= $this->Html->script('ext-2.2/HistoryClearableComboBox.js') ?>
    <?= $this->Html->script('core_common.js') ?>
    <?= $this->Html->script('calender.js') ?>

    <!-- CSS Files -->
    <?= $this->Html->css('reset.css') ?>
    <?= $this->Html->css('screen.css') ?>
    <?= $this->Html->css('calender.css') ?>

    <link rel="stylesheet" href="<?= $this->Url->build('/js/ext-2.2/resources/css/ext-all.css') ?>" />
    <link rel="stylesheet" href="<?= $this->Url->build('/js/ext-2.2/resources/css/xtheme-gray.css') ?>" />
    <link rel="stylesheet" href="<?= $this->Url->build('/js/ext-2.2/resources/css/Ext.ux.grid.RowActions.css') ?>" />

</head>

<body>
<div id="main_container">

    <!-- Header -->
    <header>
        <div class="bigHead2">

            <!-- Logo -->
            <div class="logoOther">
                <a href="<?= $this->Url->build(['controller'=>'Reports','action'=>'report_hsse_list']) ?>">
                    <img src="<?= $this->Url->build('/images/huracan_logo.png') ?>" alt="Huracan" title="Huracan">
                </a>
            </div>

            <!-- User & Support Info -->
            <div class="headRight">
                <div class="support">
                    <a href="javascript:void(0);"><?= date("l M d, Y") ?></a>
                </div>

                <div class="userId">
                    <?php
                    // Safe session access
                    $session = $this->request->getSession();
                    $adminData = $session->read('adminData');
                    $adminName = '';
                    if (!empty($adminData) && isset($adminData['first_name'], $adminData['last_name'])) {
                        $adminName = h($adminData['first_name'] . ' ' . $adminData['last_name']);
                    }
                    ?>
                    <a href="javascript:void(0);"><?= $adminName ?: 'Guest' ?></a>
                    <div class="clear"></div>
                </div>

                <div class="userId2">
                    <a href="<?= $this->Url->build(['controller'=>'AdminMasters','action'=>'logout']) ?>" title="Logout">
                        <img src="<?= $this->Url->build('/images/icon-logout.png') ?>" alt="Logout">
                    </a>
                </div>

                <div class="clear"></div>
            </div>

        </div>

        <!-- Navigation -->
        <div class="stepBase">
            <nav>
                <?= $this->element('primary_menu') ?>
            </nav>
        </div>
    </header>

    <!-- Body Content -->
    <div id="body_container">
        <div class="wrapall clearfix">
            <?= $this->fetch('content') ?>
        </div>
    </div>

    <!-- Footer -->
    <footer></footer>

</div>

<?php // $this->element('sql_dump'); ?>
</body>
</html>
