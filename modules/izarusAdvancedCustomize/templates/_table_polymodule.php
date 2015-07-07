<?php $modules_permissions = AdvancedLayout::getModulesPermissions();  ?>
<?php $modules_poly = AdvancedLayout::getModulesPolymorphism(); ?>
<?php $polymorphisms = $sf_data->getRaw('polymorphisms');?>
<?php $modules = AdvancedLayout::getModules('',true); ?>
<table class="table table-bordered">
  <thead>
    <tr>
      <th>Módulo</th>
      <th>Perfil de Acceso</th>
      <th>Actúa como</th>
      <th></th>
    </tr>
  </thead>
  <tbody> 
<?php foreach($polymorphisms AS $mo){ ?>
    <tr>
      <td rowspan="<?php echo max(1,count($modules_permissions[$mo['route']])) ?>"><i class="fa fa-fw fa-<?php echo $mo['icon']; ?>"></i> <?php echo $mo['title']; ?></td>
      <td<?php echo (isset($modules_permissions[$mo['route']]) && count($modules_permissions[$mo['route']]))?'':' colspan="2"'; ?>><?php echo (isset($modules_permissions[$mo['route']]) && count($modules_permissions[$mo['route']]))?$modules_permissions[$mo['route']][0]->getName():'Ningun Perfil de Acceso tiene permisos a este módulo.'; ?></td>
<?php if(isset($modules_permissions[$mo['route']]) && count($modules_permissions[$mo['route']])){ ?>
      <td><?php 
if(isset($modules_poly[$modules_permissions[$mo['route']][0]->getId()][$mo['route']])){ ?>
<i class="fa fa-fw fa-<?php echo $modules[$modules_poly[$modules_permissions[$mo['route']][0]->getId()][$mo['route']]->getDestination()]['icon']; ?>"></i> <?php echo $modules[$modules_poly[$modules_permissions[$mo['route']][0]->getId()][$mo['route']]->getDestination()]['title']; ?>
<?php }else{ ?>
<i class="fa fa-fw fa-<?php echo $modules[$mo['polymorphism']['default_route']]['icon']; ?>"></i> <?php  echo $modules[$mo['polymorphism']['default_route']]['title']; ?>
<?php } ?></td>
<?php } ?>
      <td rowspan="<?php echo max(1,count($modules_permissions[$mo['route']])) ?>" class="text-right" style="width:1px; white-space:nowrap">
<?php if(isset($modules_permissions[$mo['route']])){ ?>
        <a data-href="<?php echo $mo['route']; ?>" class="btn btn-primary btn-xs btn-polymorphism" data-toggle="modal" data-target="#PolymorphismModal"><small class="glyphicon glyphicon-pencil"></small></a>
<?php } ?>
      </td>
    </tr>
<?php if(isset($modules_permissions[$mo['route']])){ ?>
<?php for($i=1;$i<count($modules_permissions[$mo['route']]);$i++){ ?>
    <tr>
      <td><?php echo $modules_permissions[$mo['route']][$i]->getName(); ?></td>
      <td><?php 
if(isset($modules_poly[$modules_permissions[$mo['route']][$i]->getId()][$mo['route']])){ ?>
<i class="fa fa-fw fa-<?php echo $modules[$modules_poly[$modules_permissions[$mo['route']][$i]->getId()][$mo['route']]->getDestination()]['icon']; ?>"></i> <?php echo $modules[$modules_poly[$modules_permissions[$mo['route']][$i]->getId()][$mo['route']]->getDestination()]['title']; ?>
<?php }else{ ?>
<i class="fa fa-fw fa-<?php echo $modules[$mo['polymorphism']['default_route']]['icon']; ?>"></i> <?php  echo $modules[$mo['polymorphism']['default_route']]['title']; ?>
<?php } ?></td>
    </tr>
<?php } ?>
<?php } ?>
<?php } ?>
  </tbody>
</table>