<?php

namespace TemTudoAqui;

class Module
{
    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }

    public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__ . '/src',
                ),
            ),
        );
    }
    public function getServiceConfig()
    {
        return array(
            'factories' => array(
				'Cache' => function($sm){
					
					$cache = \Zend\Cache\StorageFactory::Factory([
						'adapter'	=> [
							'name'	=> 'Apc',
							'options'	=> [
								'ttl'	=> 10
							],
						],
						'plugins'	=> [
							'Serializer',
							'exception_handler'	=> [
								'throw_exceptions' => true
							]
						]
					]);
					return $cache;
				},
                'TemTudoAqui\Service\URL' => function($sm) {
                    return new Service\URL($sm->get('Doctrine\ORM\EntityManager'));
                }
            )
        );
    }
}
