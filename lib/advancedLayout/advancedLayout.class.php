<?php 

class AdvancedLayout{

  public static function getModules($app = '', $return_array=false,$return_polymorphism=false){
    if(empty($app))
      $app = basename(dirname(dirname(sfContext::getInstance()->getModuleDirectory())));

    $modules = array();
    $modules_poly = array();
    $modules_array = array();
    $modules_poly_array = array();
    $modules_dir = sfConfig::get('sf_apps_dir').DIRECTORY_SEPARATOR.$app.DIRECTORY_SEPARATOR.'/modules';
    
    if ($explorador = opendir($modules_dir)) {
    while (false !== ($lectura = readdir($explorador))) {
        if ($lectura != "." && $lectura != ".." && is_dir($modules_dir.DIRECTORY_SEPARATOR.$lectura)) {
          $layout_info = $modules_dir.DIRECTORY_SEPARATOR.$lectura.DIRECTORY_SEPARATOR.'config/layout.yml';
          if(file_exists($layout_info)){
            foreach(sfYaml::load($layout_info) AS $action=>$m){
              if(isset($m['title']) && isset($m['icon'])){
                if(isset($m['polymorphism'])){
                  if(!isset($m['polymorphism']['global']) || (isset($m['polymorphism']['global']) && $m['polymorphism']['global']!='true')){
                    foreach($m['polymorphism']['actions'] AS $i=>$a)
                      $m['polymorphism']['actions'][$i] = $lectura.'/'.$a;
                    $m['polymorphism']['default_route'] = $lectura.'/'.$m['polymorphism']['default'];
                  }else{
                    $m['polymorphism']['default_route'] = $m['polymorphism']['default'];
                  }
                }
                
                $m['route'] = $lectura.'/'.$action;
                
                $modules[$m['title']] = $lectura.'/'.$action;
                $modules_array[$lectura.'/'.$action] = $m;

                if($return_polymorphism && isset($m['polymorphism'])){
                  $modules_poly_array[$lectura.'/'.$action] = $m;
                  $modules_poly[$m['title']] = $lectura.'/'.$action;
                }
              }
            }
          }
        }
    }
    closedir($explorador);  
    
    } 
    
    ksort($modules_poly);
    
    if($return_polymorphism){
      foreach($modules_poly_array AS $route=>$module){
        foreach($modules_poly_array[$route]['polymorphism']['actions'] AS $i=>$po){
          $modules_poly_array[$route]['polymorphism']['actions'][$i] = $modules_array[$po];
        }
      }
      
      if($return_array)
        return $modules_poly_array;
      return $modules_poly;
    }
    
    ksort($modules);
    $modules = array_flip($modules);
    
    $modules_array_ord = array();
    foreach($modules AS $action=>$m)
      $modules_array_ord[$action] = $modules_array[$action];
    
    if($return_array)
      return $modules_array_ord;
    return $modules;
  }
  
  public static function isModuleAdvanced(){
    $layout_info = sfContext::getInstance()->getModuleDirectory().DIRECTORY_SEPARATOR.'config/layout.yml';
    if(!file_exists($layout_info))
      return false;
    return true;
  }
  
  public static function getPermissionsModules(){
    $permissions = array();
    foreach(sfGuardPermissionTable::getInstance()->findAll() AS $p){
      $permissions[$p->getId()] = json_decode($p->getModules(), true);
    }
    return $permissions;
  }
  
  public static function getModulesPermissions(){
    $modules = array();
    $permissions = array();
    foreach(sfGuardPermissionTable::getInstance()->findAll() AS $p){
      $permissions[$p->getId()] = json_decode($p->getModules(), true);
    }
    
    foreach($permissions AS $permission_id => $ms){
      if(count($ms)){
        foreach($ms AS $m){
          if(!isset($modules[$m]))
            $modules[$m] = array();
          $modules[$m][] = sfGuardPermissionTable::getInstance()->findOneById($permission_id);
        }
      }
    }
    return $modules;
  }
  
  public static function userHasPermission(){
    $profile = AdvancedLayout::getCurrentProfile();
  
    $permission = sfGuardPermissionTable::getInstance()->findOneById($profile);
    if(!$permission)
      return false;
    $modules = json_decode($permission->getModules(), true);
    if(!count($modules))
      return false;
    
    $module = sfContext::getInstance()->getModuleName();
    $action = sfContext::getInstance()->getActionName();
       
    $layout_info = sfContext::getInstance()->getModuleDirectory().DIRECTORY_SEPARATOR.'config/layout.yml';
    if(file_exists($layout_info)){
      $yaml = sfYaml::load($layout_info);      
      if(isset($yaml[$action]) && isset($yaml[$action]['use_permission']) && isset($yaml[$yaml[$action]['use_permission']]))
        $action = $yaml[$action]['use_permission'];
      elseif(!isset($yaml[$action]) && isset($yaml['index']))
        $action = 'index';
    }
    
    $route = $module.'/'.$action;
    
    if(!in_array($route,$modules))
      return false;
    
    return true;
  }
  
  public static function modulePolymorfism($route_ext=''){
    if(empty($route_ext) && !AdvancedLayout::isModuleAdvanced())
      return false;
    
    if(empty($route_ext)){
      $module = sfContext::getInstance()->getModuleName();
      $action = sfContext::getInstance()->getActionName();
    }else{
      $route_ext = explode('/',$route_ext);
      if(count($route_ext)<2)
        return false;
      $module = $route_ext[0];
      $action = $route_ext[1];
    }
    
    sfContext::getInstance()->getUser()->setAttribute('original_module',$module);
    sfContext::getInstance()->getUser()->setAttribute('original_action',$action);
    
    $layout_info = sfContext::getInstance()->getModuleDirectory().DIRECTORY_SEPARATOR.'config/layout.yml';
    if(file_exists($layout_info)){
      $yaml = sfYaml::load($layout_info);
      if(!isset($yaml[$action]) && isset($yaml['index']))
        $action = 'index';
    }
    
    $route = $module.'/'.$action;
    
    $modules_polymorfism = AdvancedLayout::getModules('',true,true);
    if(!isset($modules_polymorfism[$route]))
      return false;
    
    $mp = ModulesPolymorphismTable::getInstance()->findOneBySfGuardPermissionIdAndSource(AdvancedLayout::getCurrentProfile(),$route);
    
    if($mp){
      $mp_array = explode('/',$mp->getDestination());
      
      if($mp->getUseDestinationTitle())
        sfContext::getInstance()->getUser()->setAttribute('original_action',$mp_array[1]);
        
      if($mp->getDestination() == $route)
        return false;
      
      return array(
        'module'=>$mp_array[0],
        'action'=>$mp_array[1],
        'use_title'=>$mp->getUseDestinationTitle(),
      );
    }else if(isset($modules_polymorfism[$route]['polymorphism']['default'])){
      if(isset($modules_polymorfism[$route]['polymorphism']['global']) && $modules_polymorfism[$route]['polymorphism']['global']=='true'){
        $global = explode('/',$modules_polymorfism[$route]['polymorphism']['default']);
        return array(
          'module'=>$global[0],
          'action'=>$global[1],
          'use_title'=>false,
        );
      }
    
      return array(
        'module'=>$module,
        'action'=>$modules_polymorfism[$route]['polymorphism']['default'],
        'use_title'=>false,
      );
    }
    
    return false;
  }
  
  public static function getModulesPolymorphism(){
    $modules = array();
    foreach(ModulesPolymorphismTable::getInstance()->findAll() AS $mp){
      $modules[$mp->getPermission()->getId()][$mp->getSource()] = $mp;
    }
    return $modules;
  }
  
  public static function getLateralMenu(){
    $permission = AdvancedLayout::getCurrentProfile();
    $app = basename(dirname(dirname(sfContext::getInstance()->getModuleDirectory())));
    $menus = LateralMenuTable::getInstance()->createQuery('lm')->leftJoin('lm.Permissions p')->where('p.id = ?',$permission)->orderBy('lm.menu_order')->execute();
    $permissions = AdvancedLayout::getPermissionsModules();
    $modules = AdvancedLayout::getModules($app, true);
    
    $menu_arr = array();
    foreach($menus AS $me){
      $m = array();
      $m['title'] = $me->getTitle();
      $m['modules'] = array();
      foreach(json_decode($me->getModules(),true) AS $mo){
        if(isset($permissions[$permission]) && isset($modules[$mo])){
          if(in_array($mo,$permissions[$permission])){
            $m['modules'][$mo] = $modules[$mo];
            $poly = AdvancedLayout::modulePolymorfism($mo);
            if($poly!==false && isset($poly['use_title']) && $poly['use_title'])
              $m['modules'][$mo]['title'] = $modules[$poly['module'].'/'.$poly['action']]['title'];
          }
        }
      }
      $menu_arr[] = $m;
    }
    
    return $menu_arr;
  }
  
  public static function setCurrentProfile($profile){ 
    $p = sfGuardPermissionTable::getInstance()->findOneById($profile);
    if($p)
      sfContext::getInstance()->getUser()->setAttribute('current_permission',$p->getId());
  }
  
  public static function getCurrentProfile(){ 
    return sfContext::getInstance()->getUser()->getAttribute('current_permission',false);
  }
  
  public static function isCurrentProfile($equal){ 
    return (sfContext::getInstance()->getUser()->getAttribute('current_permission',false) == $equal);
  }
  
  public static function getSuperAdminMenu(){
    $menu = array(
      array(
        'title'=>'Permisos de Usuarios',
        'icon'=>'user',
        'route'=>'@izarus_advanced_layout_users',
      ),
      array(
        'title'=>'Módulos',
        'icon'=>'asterisk',
        'route'=>'@izarus_advanced_layout_customize',
      ),
      array(
        'title'=>'Menú Lateral',
        'icon'=>'list',
        'route'=>'@izarus_advanced_layout_lateral',
      )
    );
    
    if(sfContext::getInstance()->getUser()->getGuardUser()->getIsSuperAdmin())
      return $menu;
    return array();
  }
  
  public static function include_title(){
    $app_view = dirname(dirname(sfContext::getInstance()->getModuleDirectory())).DIRECTORY_SEPARATOR.'config/view.yml';
    $layout_info = sfContext::getInstance()->getModuleDirectory().DIRECTORY_SEPARATOR.'config/layout.yml';    
    
    echo '<title>';
    
    if(file_exists($app_view)){
      $view = sfYaml::load($app_view);
      if(isset($view['default']['metas']['title'])){
        echo $view['default']['metas']['title'];
      }
    }
    
    $action = sfContext::getInstance()->getUser()->getAttribute('original_action',sfContext::getInstance()->getActionName());
    
    if(file_exists($layout_info)){
      $yaml = sfYaml::load($layout_info);
      if(isset($yaml[$action]['title']) || isset($yaml['index']['title'])){
        echo (isset($view['default']['title_separator']))?$view['default']['title_separator']:' - ';
        
        if(isset($yaml[$action]['title']))
          echo $yaml[$action]['title'];
        else if(isset($yaml['index']['title']))
          echo $yaml['index']['title'];
      }
    }
    echo '</title>';
  }
  
  public static function current_route($route){
    $modules = self::getModules('',false);
    $module = sfContext::getInstance()->getModuleName();
    $action = sfContext::getInstance()->getUser()->getAttribute('polymodule')?sfContext::getInstance()->getUser()->getAttribute('original_action'):sfContext::getInstance()->getActionName();
      
    $layout_info = sfContext::getInstance()->getModuleDirectory().DIRECTORY_SEPARATOR.'config/layout.yml';
    if(file_exists($layout_info)){
      $yaml = sfYaml::load($layout_info);      
      if(isset($yaml[$action]) && isset($yaml[$action]['use_permission']) && isset($yaml[$yaml[$action]['use_permission']]))
        $action = $yaml[$action]['use_permission'];
      elseif(!isset($yaml[$action]) && isset($yaml['index']))
        $action = 'index';
    }
    
    if(!isset($modules[$module.'/'.$action]))
      $action = 'index';
      
    return ($module.'/'.$action == $route);
  }
  
}