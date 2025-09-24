<!DOCTYPE HTML>
<html>
<head>
<meta charset="utf-8">
<title>Huracan</title>
<?php echo $this->Html->script('jquery'); ?>
<?php echo $this->Html->css('reset'); ?>
<?php echo $this->Html->css('screen'); ?>
<?php echo $this->Html->script('iefix'); ?>
<?php echo $this->Html->script('ext-2.2/adapter/ext/ext-base'); ?>
<?php echo $this->Html->script('ext-2.2/ext-all-debug'); ?>
<?php echo $this->Html->script('ext-2.2/Ext.ux.grid.RowActions'); ?>
<?php echo $this->Html->script('ext-2.2/Ext.ux.Toast'); ?>
<?php echo $this->Html->script('ext-2.2/HistoryClearableComboBox'); ?>
<?php echo $this->Html->script('core_common'); ?>
<link rel='StyleSheet' href='<?php echo $this->webroot?>js/ext-2.2/resources/css/ext-all.css' />
<link rel='StyleSheet' href='<?php echo $this->webroot?>js/ext-2.2/resources/css/xtheme-gray.css' />
<link rel='StyleSheet' href='<?php echo $this->webroot?>js/ext-2.2/resources/css/Ext.ux.grid.RowActions.css' />
<?php echo $this->Html->css('calender'); ?>
<?php echo $this->Html->script('calender'); ?>

</head>

<body>
<div id="main_container">
<!--header -->
<header>
<div class="bigHead2">
<div class="logoOther">
<a href="<?php echo $this->webroot; ?>Reports/report_hsse_list"><img src="<?php echo $this->webroot; ?>images/huracan_logo.png" alt="Huracan" title="Huracan"></a>
</div>
<div class="headRight">
<div class="support">
<a href="javscript:void(0);"><?php echo $today =date("l M d, Y"); ?></a></div>
<div class="userId">
<a href="javscript:void(0);"><?php echo $_SESSION['adminData']['AdminMaster']['first_name']." ".$_SESSION['adminData']['AdminMaster']['last_name']; ?></a>
 <div class="clear"></div>
 </div>
<div class="userId2">
<a href="<?php echo $this->webroot; ?>AdminMasters/logout" title="Logout"><img src="<?php echo $this->webroot; ?>images/icon-logout.png"></a></div>
<div class="clear"></div>
 </div>
</div>
<div class="stepBase">
<!--navigation -->
<nav>
<?php echo $this->Element('primary_menu'); ?>
</nav>
<!--navigation -->
</div>
</header>
<!--header -->
<div id="body_container">
<div class="wrapall clearfix">
<?php echo $content_for_layout; ?>
</div>
</div>
<footer>
</footer>
</div>
<?php // echo $this->element("sql_dump"); ?>
</body>
</html>
