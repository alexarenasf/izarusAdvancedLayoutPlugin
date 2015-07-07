<?php $module = $sf_data->getRaw('module'); ?>
<?php $modules_permissions = AdvancedLayout::getModulesPermissions(); ?>
<?php $modules_poly = AdvancedLayout::getModulesPolymorphism(); ?>
<div class="alert alert-info text-justify">
Al utilizar un determinado Perfil de Acceso, el módulo <i class="fa fa-fw fa-<?php echo $module['icon']; ?>"></i><b><?php echo $module['title']; ?></b> puede actuar como otro módulo compatible. Al hacer esto se puede cambiar el acceso a formularios de edición avanzados por unos formularios de edición más básicos.</b>
</div>
<div class="alert alert-danger text-justify hide">
Ocurrió un error al intentar guardar los cambios.
</div>
<input type="hidden" name="r" value="<?php echo $module['route']; ?>" />
<div class="row">
<?php foreach($modules_permissions[$module['route']] AS $p){ ?>
  <div class="col-sm-6">
    <div class="form-group">
      <label for="for" class="col-xs-4 control-label">Perfil de Acceso</label>
      <div class="col-xs-8">
        <p class="form-control-static"><?php echo $p->getName(); ?></p>
      </div>
    </div>
    <div class="form-group">
      <label for="for" class="col-xs-4 control-label">Actúa como</label>
      <div class="col-xs-8">
        <select name="perfil_acceso[<?php echo $p->getId(); ?>][destination]" class="form-control">
          <?php foreach($module['polymorphism']['actions'] AS $mo){ ?>
          <option value="<?php echo $mo['route']?>"<?php echo ((isset($modules_poly[$p->getId()][$module['route']]) && $modules_poly[$p->getId()][$module['route']]->getDestination() == $mo['route']) || (!isset($modules_poly[$p->getId()][$module['route']]) && $module['polymorphism']['default_route'] == $mo['route']))?' selected="selected"':''; ?>><?php echo $mo['title']; ?></option>
          <?php } ?>
        </select>
      </div>
    </div>
    <div class="form-group">
      <label for="perfil_acceso_<?php echo $p->getId(); ?>_use_destination_title" class="col-xs-4 control-label">Usar título de "Actúa como"</label>
      <div class="col-xs-8">
        <input type="checkbox" id="perfil_acceso_<?php echo $p->getId(); ?>_use_destination_title" name="perfil_acceso[<?php echo $p->getId(); ?>][use_destination_title]"<?php echo (isset($modules_poly[$p->getId()][$module['route']]) && $modules_poly[$p->getId()][$module['route']]->getUseDestinationTitle())?' checked="checked"':''; ?>>
      </div>
    </div>
  </div>
<?php } ?>
</div>
