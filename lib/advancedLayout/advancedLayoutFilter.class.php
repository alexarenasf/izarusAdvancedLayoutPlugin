<?php
class advancedLayoutFilter extends sfFilter
{
  public function execute($filterChain)
  {
    if ($this->isFirstCall() && $this->getContext()->getUser()->isAuthenticated() && AdvancedLayout::isModuleAdvanced())
    {
      $profiles = $this->getContext()->getUser()->getGuardUser()->getPermissions();
      if(count($profiles)==1)
        AdvancedLayout::setCurrentProfile($profiles[0]->getId());
      $profile = AdvancedLayout::getCurrentProfile();
      
      $action = $this->getContext()->getActionName(); 
      if(!$profile && $action <> 'selectProfile'){
        $this->getContext()->getController()->forward('izarusAdvancedCustomize','selectProfile');
        throw new sfStopException();
      }else{
        $polymorfism = AdvancedLayout::modulePolymorfism();
        
        if(!AdvancedLayout::userHasPermission()){
          $this->getContext()->getController()->forward('izarusAdvancedCustomize','forbidden');
          throw new sfStopException();
        }else if($polymorfism!==false){
          $this->getContext()->getController()->forward($polymorfism['module'],$polymorfism['action']);
          throw new sfStopException();
        }        
      }      
    }
    $filterChain->execute();
  }
}