<?php
class izarusAdvancedLayoutUsersPermissionsForm extends sfGuardPermissionForm
{
  public function configure()
  {
    parent::configure();
    $this->useFields(array('name','enable_advanced','users_list'));
    
    $user_list_options = array(
      'model' => 'sfGuardUser',
      'order_by'=>array('first_name','ASC'),
      'renderer_class' => 'izarusWidgetFormBootstrapSelectDoubleList',
      'renderer_options' => array(
        'label_unassociated' => 'No asociados',
        'label_associated' => 'Asociados',
        ),
      );
    
    if(method_exists(sfGuardUserTable::getInstance(),'AdvancedLayoutList'))
      $user_list_options['table_method'] = 'AdvancedLayoutList';
    
    $this->widgetSchema['users_list'] = new sfWidgetFormDoctrineChoice($user_list_options);
    
    $this->widgetSchema->setLabels(array(
      'name' => 'Nombre',
      'enable_advanced' => 'Personalización Activada',
      'users_list' => 'Usuarios con este permiso',
    ));

    $this->widgetSchema->setHelps(array(
      'enable_advanced'=>'Si está activada esta opción entonces será posible utilizar este permiso como Perfil de Acceso.',
    ));
    
  }

}