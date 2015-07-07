 <div class="form-group">
  <label for="for" class="col-xs-<?php echo sfConfig::get('app_bootstrap_admin_labelcols',3) ?> control-label">Perfil de Acceso</label>
  <div class="col-xs-<?php echo sfConfig::get('app_bootstrap_admin_fieldcols',9) ?>">
    <p class="form-control-static"><?php echo $form->getObject()->getName(); ?></p>
  </div>
</div>

<?php echo $form; ?>