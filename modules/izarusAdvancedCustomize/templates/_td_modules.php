<?php $modules = AdvancedLayout::getModules('admin',true); ?>
<?php if(count(json_decode($sf_data->getRaw('lateral_menu')->getModules()))){ ?>
<?php foreach(json_decode($sf_data->getRaw('lateral_menu')->getModules(),true) AS $m){ ?>
<?php if(isset($modules[$m])){ ?>
<i class="fa fa-fw fa-<?php echo $modules[$m]['icon'] ?>"></i> <?php echo $modules[$m]['title'] ?><br>
<?php }}}else{ ?>
<i class="fa fa-fw fa-times"></i> Ninguno
<?php } ?>