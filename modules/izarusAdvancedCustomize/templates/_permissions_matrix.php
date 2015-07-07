<?php $permissions_array = AdvancedLayout::getPermissionsModules(); ?>
<table class="table table-bordered">
  <tr>
    <th>Módulo</th>
<?php foreach($permissions AS $p){ ?>
    <th class="text-center"><?php echo $p->getName(); ?></th>
<?php } ?>
  </tr>
<?php foreach(AdvancedLayout::getModules('admin',true) AS $action=>$m){ ?>
  <tr>
    <td><i class="fa fa-fw fa-<?php echo $m['icon']; ?>"></i> <?php echo $m['title'] ?></td>
<?php foreach($permissions AS $p){ ?>
    <td class="text-center"><i class="fa fa-fw fa-<?php echo ($permissions_array[$p->getId()] && in_array($action,$permissions_array[$p->getId()]))?'check text-success':'times text-danger'; ?>"></i></td>
<?php } ?>
  </tr>
<?php } ?>
</table>