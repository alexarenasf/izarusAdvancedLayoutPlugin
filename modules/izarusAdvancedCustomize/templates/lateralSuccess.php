 <div class="page-header">
  <h1>Menú Lateral</h1>
</div>

 <?php include_component('izarusModalChild','view',array(
  'singular'=>'Grupo de Módulos',
  'plural'=>'Grupos de Módulos',
  'class'=>'LateralMenu',
  'form_class'=>'izarusAdvancedLayoutLateralMenuForm',
  'enabled_actions' => array(
    'add'=>true,
    'edit'=>true,
    'delete'=>true,
  ),
  'query'=>array(
    'root'=>'lm',
    'order_by'=>'lm.menu_order ASC'
  ),
  'cols'=>array(
    'Orden'=>'#getMenuOrder#',
    'Título'=>'<b>#getTitle#</b>',
    'Se muestra para los Perfiles'=>'_izarusAdvancedCustomize/td_permissions',
    'Módulos'=>'_izarusAdvancedCustomize/td_modules',
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