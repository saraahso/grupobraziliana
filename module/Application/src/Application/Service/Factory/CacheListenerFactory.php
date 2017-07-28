<?php

namespace Application\Service\Factory;
 
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Application\Model\CacheListener;
 
class CacheListenerFactory implements FactoryInterface {
 
    public function createService(ServiceLocatorInterface $serviceLocator) {
        return new CacheListener($serviceLocator->get('Zend\Cache'));
    }
 
}