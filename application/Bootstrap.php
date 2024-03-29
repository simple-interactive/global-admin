<?php

class Bootstrap extends Zend_Application_Bootstrap_Bootstrap
{
    /**
     * Making configuration available from the Registry
     * @return void
     */
    protected function _initConfig()
    {
        Zend_Registry::set('config', $this->getOptions());
    }

    /**
     * Initialize pseudo-namespaces,
     * auto-loader and resource groups
     *
     * @return void
     */
    protected function _initAutoloader()
    {
        $moduleLoader = new Zend_Application_Module_Autoloader([
            'namespace' => 'Default',
            'basePath'  => APPLICATION_PATH.'/modules/default'
        ]);

        $moduleLoader->addResourceTypes([
            'controller' => [
                'namespace' => 'Controller',
                'path' => 'controllers'
            ]
        ]);

        $appResources = new Zend_Loader_Autoloader_Resource([
            'basePath'  => APPLICATION_PATH,
            'namespace' => 'App',
        ]);

        $appResources->addResourceTypes([
            'mappers'     => ['namespace' => 'Map', 'path' => 'mappers'],
            'models'      => ['namespace' => 'Model', 'path' => 'models'],
            'plugins'     => ['namespace' => 'Plugin', 'path' => 'plugins'],
            'services'    => ['namespace' => 'Service', 'path' => 'services'],
            'controllers' => ['namespace' => 'Controller', 'path' => 'modules/default/controllers'],
            'traits'      => ['namespace' => 'Trait', 'path' => 'traits']
        ]);
    }

    /**
     * @throws Zend_Exception
     */
    protected function _initDb()
    {
        $config = Zend_Registry::get('config');
        Mongostar_Model::setConfig($config['db']);
    }

}

