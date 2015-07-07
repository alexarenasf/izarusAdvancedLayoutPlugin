 <div class="page-header">
  <h1>Permisos de Usuarios</h1>
</div>

<?php include_component('izarusModalChild','view',array(
  'singular'=>'Permiso',
  'plural'=>'Permisos',
  'class'=>'sfGuardPermission',
  'form_class'=>'izarusAdvancedLayoutUsersPermissionsForm',
  'modal_size'=>'lg',
  'enabled_actions' => array(
    'add'=>true,
    'edit'=>true,
    'delete'=>true,
  ),
  'cols'=>array(
    'Nombre'=>'#getName#',
    'Número de Usuarios'=>'_izarusAdvancedCustomize/td_nusuarios',
    'Perfil de Acceso' => '_izarusAdvancedCustomize/td_pacceso',
  ),
  'query'=>array(
    'root'=>'p',
    'order_by' => 'p.name ASC',
  ),
  'messages'=>array(
    'empty'=>'No ha ingresado %option.plural%',
    'add'=>'Agregar un %option.singular%',
    'add_title'=>'Agregar %option.singular%',
    'edit_title'=>'Editar %option.singular%',
    'delete_title'=>'Eliminar %option.singular%',
    'new'=>'Debe guardar para agregar %option.plural%'
  ),
)) ?>