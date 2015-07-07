<?php
class izarusAdvancedLayoutModulesPermissionsForm extends sfGuardPermissionForm
{
  public function configure()
  {
    parent::configure();
    $this->useFields(array('modules'));
    
    $this->widgetSchema['modules'] = new sfWidgetFormInputHidden();
    
    $this->widgetSchema['modules_mark'] = new sfWidgetFormChoice(array(
      'expanded' => true,
      'multiple' => true,
      'choices'  => AdvancedLayout::getModules(),
    ));    
    
    $this->validatorSchema['modules_mark'] = new sfValidatorPass();
    
    $this->widgetSchema->setLabels(array(
      'modules_mark' => 'Modulos Permitidos',
    ));
    
    
    $this->setDefaults(array(
      'modules_mark' => json_decode($this->getObject()->getModules())
    ));
    
    $this->validatorSchema->setPostValidator(new sfValidatorCallback(array('callback' => array($this, 'parseModules'))));
    
  }
  
  public function parseModules($validator, $values) {
    $values['modules'] = json_encode($values['modules_mark']);
    return $values;
  }
}