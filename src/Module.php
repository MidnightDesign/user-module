<?php

namespace Midnight\UserModule;

use Zend\ModuleManager\Feature\DependencyIndicatorInterface;
use Zend\Mvc\ModuleRouteListener;
use Zend\Mvc\MvcEvent;

class Module implements DependencyIndicatorInterface
{
    public function onBootstrap(MvcEvent $e)
    {
        $eventManager = $e->getApplication()->getEventManager();
        $moduleRouteListener = new ModuleRouteListener();
        $moduleRouteListener->attach($eventManager);

        $t = $e->getTarget();

        $t->getEventManager()->attach(
            $t->getServiceManager()->get('ZfcRbac\View\Strategy\RedirectStrategy')
        );
    }

    public function getConfig()
    {
        return include dirname(__DIR__) . '/config/module.config.php';
    }

    /**
     * Expected to return an array of modules on which the current one depends on
     *
     * @return array
     */
    public function getModuleDependencies()
    {
        return array('DoctrineORMModule');
    }
}
