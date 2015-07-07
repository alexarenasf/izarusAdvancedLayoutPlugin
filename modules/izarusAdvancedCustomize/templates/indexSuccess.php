<div class="page-header">
  <h1>Módulos</h1>
</div>
<h3>Permisos de Módulos por Perfil de Acceso</h3>
<?php include_component('izarusModalChild','view',array(
  'singular'=>'Perfil de Acceso',
  'plural'=>'Perfiles de Acceso',
  'class'=>'sfGuardPermission',
  'form_class'=>'izarusAdvancedLayoutModulesPermissionsForm',
  'form_partial'=>'izarusAdvancedCustomize/form_modules',
  'enabled_actions' => array(
    'add'=>false,
    'edit'=>true,
    'delete'=>false,
  ),
  'cols'=>array(
    'Perfil de Acceso'=>'#getName#',
    'Número de Módulos Permitidos'=>'_izarusAdvancedCustomize/td_nmodules',
  ),
  'query'=>array(
    'root'=>'p',
    'order_by' => 'p.name ASC',
    'where'=>array(
      array('p.enable_advanced = ?',1)
    ),
  ),
  'messages'=>array(
    'empty'=>'No ha ingresado %option.plural%',
    'add'=>'Agregar un %option.singular%',
    'add_title'=>'Agregar %option.singular%',
    'edit_title'=>'Editar %option.singular%',
    'delete_title'=>'Eliminar %option.singular%',
    'new'=>'Debe guardar para agregar %option.plural%'
  ),
  'call_js_function'=>'load_matrix',
)) ?>
<?php $polymorphisms = AdvancedLayout::getModules('',true,true); ?>
<?php if(count($polymorphisms) && count($permissions)){ ?>
<h3>Módulos con configuración avanzada</h3>
<div id="PolymorphismTable">
<?php include_partial('table_polymodule',array('polymorphisms'=>$polymorphisms)); ?>
</div>

<div class="modal fade" id="PolymorphismModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <form method="post" action="<?php echo url_for('izarusAdvancedCustomize/polyModuleForm'); ?>" id="PolymorphismModalForm">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true"><i class="glyphicon glyphicon-remove"></i></span></button>
          <h4 class="modal-title" id="myModalLabel">Configuración Avanzada de Módulo</h4>
        </div>
        <div class="modal-body form-horizontal">
          <i class="fa fa-spin fa-spinner"></i> Cargando...
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
          <button type="submit" class="btn btn-primary" disabled="disabled">Guardar Cambios</button>
        </div>
      </form>
    </div>
  </div>
</div>

<script>
function update_polymodule_buttons(){
  $('.btn-polymorphism').off('click').click(function(){
    var route = $(this).attr('data-href');
    $.get('<?php echo url_for('izarusAdvancedCustomize/polyModuleForm'); ?>?r='+route,{},function(data){
      $('#PolymorphismModal .modal-body').html(data);
      $('#PolymorphismModalForm .btn-primary').prop('disabled',false);
    });
  });
}

$('#PolymorphismModal').on('hidden.bs.modal', function () {
    $('#PolymorphismModal .modal-body').html('<i class="fa fa-spin fa-spinner"></i> Cargando...');
})

$('#PolymorphismModalForm').ajaxForm({
  'beforeSubmit': function(){
    $('#PolymorphismModalForm .alert-danger').addClass('hide');
    $('#PolymorphismModalForm .btn-primary').prop('disabled',true);
  },
  'success': function(responseText){
    if(responseText.trim()=='OK'){
      $('#PolymorphismModal').modal('hide');
      if (typeof load_table_polumodule == 'function') { load_table_polumodule(); }
    }else{
      $('#PolymorphismModalForm .alert-danger').removeClass('hide');
      $('#PolymorphismModalForm .btn-primary').prop('disabled',false);
    }
  }
});

function load_table_polumodule(){
  $.get('<?php echo url_for('izarusAdvancedCustomize/polyModuleTable'); ?>',{},function(data){
    $('#PolymorphismTable').html(data);
    update_polymodule_buttons();
  });
}
update_polymodule_buttons();
</script>

<?php } ?>
<h3>Resumen de Permisos</h3>
<div id="matrix-load">
  <?php include_partial('permissions_matrix',array('permissions'=>$permissions)); ?>
</div>

<script>
function load_matrix(){
  if (typeof load_table_polumodule == 'function') { load_table_polumodule(); }
  $("#matrix-load").load('<?php echo url_for('izarusAdvancedCustomize/permissionsMatrix'); ?>');
}
</script>
