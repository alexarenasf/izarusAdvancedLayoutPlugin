 <?php

class izarusAdvancedLayoutPluginRouting
{
  static public function listenToRoutingLoadConfigurationEvent(sfEvent $event)
  {
    $event->getSubject()->prependRoute('izarus_advanced_layout_customize', new sfRoute('/customize/modules', array('module' => 'izarusAdvancedCustomize', 'action' => 'index')));
    $event->getSubject()->prependRoute('izarus_advanced_layout_users', new sfRoute('/customize/permissions', array('module' => 'izarusAdvancedCustomize', 'action' => 'permissions')));
    $event->getSubject()->prependRoute('izarus_advanced_layout_lateral', new sfRoute('/customize/lateral', array('module' => 'izarusAdvancedCustomize', 'action' => 'lateral')));
    $event->getSubject()->prependRoute('izarus_advanced_layout_select_profile', new sfRoute('/p', array('module' => 'izarusAdvancedCustomize', 'action' => 'selectProfile')));
  }
}

