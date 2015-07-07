<?php
class izarusAdvancedLayoutLateralMenuForm extends LateralMenuForm
{
  public function configure()
  {
    parent::configure();
    
    $this->widgetSchema['modules'] = new sfWidgetFormInputHidden();
    
    $this->widgetSchema['modules_mark'] = new sfWidgetFormChoice(array(
      'expanded' => true,
      'multiple' => true,
      'choices'  => AdvancedLayout::getModules('admin'),
    ));    
    
    $this->validatorSchema['modules_mark'] = new sfValidatorPass();
    
    $this->setDefaults(array(
      'modules_mark' => json_decode($this->getObject()->getModules())
    ));
    
    $this->validatorSchema->setPostValidator(new sfValidatorCallback(array('callback' => array($this, 'parseModules'))));
    
    
    $this->widgetSchema['permissions_list'] = new sfWidgetFormDoctrineChoice(array(
      'multiple' => true,
      'expanded' => true,
      'order_by'=> array('name','ASC'),
      'model' => 'sfGuardPermission'
    ));
    
    
    $this->widgetSchema->setLabels(array(
      'modules_mark' => 'Módulos',
      'menu_order' => 'Orden',
      'title'=> 'Título',
      'permissions_list' => 'Se muestra para'
    ));
    
  }
  
  public function parseModules($validator, $values) {
    $values['modules'] = json_encode($values['modules_mark']);
    return $values;
  }
  
}