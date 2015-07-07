<?php

class izarusAdvancedLayoutPluginConfiguration extends sfPluginConfiguration
{
  public function initialize()
  {
    $this->dispatcher->connect('routing.load_configuration', array('izarusAdvancedLayoutPluginRouting', 'listenToRoutingLoadConfigurationEvent'));
  }
}