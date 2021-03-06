<?php
class advancedLayoutFilter extends sfFilter
{
  public function execute($filterChain)
  {
    if ($this->isFirstCall() && $this->getContext()->getUser()->isAuthenticated())
    {
      $profiles = $this->getContext()->getUser()->getGuardUser()->getPermissions();
      if(!count($profiles)){
        $this->getContext()->getController()->forward('izarusAdvancedCustomize','forbidden');
        throw new sfStopException();
      }
      
      if(count($profiles)==1)
        AdvancedLayout::setCurrentProfile($profiles[0]->getId());
      $profile = AdvancedLayout::getCurrentProfile();
      
      if(AdvancedLayout::isModuleAdvanced()){
        $action = $this->getContext()->getActionName(); 
        
        if(!$profile && $action <> 'selectProfile'){
          $this->getContext()->getController()->forward('izarusAdvancedCustomize','selectProfile');
          throw new sfStopException();
        }else{
          $polymorfism = AdvancedLayout::modulePolymorfism();
          
          if(!AdvancedLayout::userHasPermission()){
            $this->getContext()->getUser()->setAttribute('polymodule',false);
            $this->getContext()->getController()->forward('izarusAdvancedCustomize','forbidden');
            throw new sfStopException();
          }else if($polymorfism!==false){
            $this->getContext()->getUser()->setAttribute('polymodule',true);
            $this->getContext()->getController()->forward($polymorfism['module'],$polymorfism['action']);
            throw new sfStopException();
          }
          $this->getContext()->getUser()->setAttribute('polymodule',false);          
        }   
      }
    }
    $filterChain->execute();
  }
}