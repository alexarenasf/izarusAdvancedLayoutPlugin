<?php

class izarusAdvancedCustomizeActions extends sfActions
{
  public function executeIndex(sfWebRequest $request){
    $this->permissions = sfGuardPermissionTable::getInstance()->createQuery('p')->where('p.enable_advanced = ?',1)->orderBy('p.name ASC')->execute();
  }
  
  public function executePermissionsMatrix(sfWebRequest $request){
    $data = array();
    $data['permissions'] = sfGuardPermissionTable::getInstance()->createQuery('p')->where('p.enable_advanced = ?',1)->orderBy('p.description ASC')->execute();
    return $this->renderPartial('permissions_matrix',$data);
  }
  
  public function executeSelectProfile(sfWebRequest $request){
    if($request->getParameter('profile_name',false)){
      AdvancedLayout::setCurrentProfile($request->getParameter('profile_name'));
      $this->redirect('@homepage');
    }
  }
  
  public function executeForbidden(sfWebRequest $request){
  
  }  
  
  public function executePolyModuleForm(sfWebRequest $request){
    $route = $request->getParameter('r',false);
    
    if($request->isMethod('post')){
      $perfil_acceso = $request->getParameter('perfil_acceso');
      
      if(!$route || !$perfil_acceso)
        return $this->renderText('ERROR');
      
      $conn = sfContext::getInstance()->getDatabaseManager()->getDatabase('doctrine')->getDoctrineConnection();   
      try { 
        $conn->beginTransaction();
        ModulesPolymorphismTable::getInstance()->findBySource($route)->delete();
        
        foreach($perfil_acceso AS $sf_guard_permission_id => $form){
          $mp = new ModulesPolymorphism();
          $mp->setSfGuardPermissionId($sf_guard_permission_id);
          $mp->setSource($route);
          $mp->setDestination($form['destination']);
          $mp->setUseDestinationTitle(isset($form['use_destination_title']));
          $mp->save();
        }
         
        $conn->commit();
        return $this->renderText('OK');
      }catch(Doctrine_Exception $e){
        $conn->rollback();
        return $this->renderText('ERROR');
      }
       
    }
    
    $polymorphisms = AdvancedLayout::getModules('',true,true);
    $modules_permissions = AdvancedLayout::getModulesPermissions();
    if(!count($polymorphisms) || !$route || !isset($polymorphisms[$route]))
      die;

    $data = array();
    $data['module'] = $polymorphisms[$route];
    $data['permissions'] = AdvancedLayout::getModulesPermissions();
    return $this->renderPartial('form_polymodule',$data);
  }
  
  public function executePolyModuleTable(sfWebRequest $request){  
    $data = array();
    $data['polymorphisms'] = AdvancedLayout::getModules('',true,true);
    return $this->renderPartial('table_polymodule',$data);
  }
  
  public function executePermissions(sfWebRequest $request){
  
  }
  
  public function executeLateral(sfWebRequest $request){
  
  }
}
