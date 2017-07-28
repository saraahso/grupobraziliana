<?php

return array(
  'controllers' => array(
    'invokables' => array(
      'TemTudoAqui\Controller\URLRest' => 'TemTudoAqui\Controller\URLRestController',
      'TemTudoAqui\Controller\TextoRest' => 'TemTudoAqui\Controller\TextoRestController',
      'TemTudoAqui\Controller\MarcaRest' => 'TemTudoAqui\Controller\MarcaRestController',
      'TemTudoAqui\Controller\ImagemRest' => 'TemTudoAqui\Controller\ImagemRestController',
      'TemTudoAqui\Controller\CategoriaRest' => 'TemTudoAqui\Controller\CategoriaRestController',
      'TemTudoAqui\Controller\ProdutoRest' => 'TemTudoAqui\Controller\ProdutoRestController',
      'TemTudoAqui\Controller\VendedorRest' => 'TemTudoAqui\Controller\VendedorRestController'
    )
  ),
  'router' => array(
    'routes' => array(
      'api-url' => array(
        'type' => 'Segment',
        'options' => array(
          'route' => '/api/urls[/:id]',
          'defaults' => array(
            'controller' => 'TemTudoAqui\Controller\URLRest',
            'cache' => true
          )
        )
      ),
      'api-texto' => array(
        'type' => 'Segment',
        'options' => array(
          'route' => '/api/textos[/:id]',
          'defaults' => array(
            'controller' => 'TemTudoAqui\Controller\TextoRest',
            'cache' => true
          )
        )
      ),
      'api-marca' => array(
        'type' => 'Segment',
        'options' => array(
          'route' => '/api/marcas[/:id]',
          'defaults' => array(
            'controller' => 'TemTudoAqui\Controller\MarcaRest',
            'cache' => true
          )
        )
      ),
      'api-marca-produtos' => array(
        'type' => 'Segment',
        'options' => array(
          'route' => '/api/marcas/[:id-marca]/produtos[/:id]',
          'defaults' => array(
            'controller' => 'TemTudoAqui\Controller\ProdutoRest',
            'cache' => true
          ),
        ),
        'may_terminate' => false,
      ),
      'api-imagem' => array(
        'type' => 'Segment',
        'options' => array(
          'route' => '/api/imagens[/:id]',
          'defaults' => array(
            'controller' => 'TemTudoAqui\Controller\ImagemRest',
            'cache' => true
          )
        )
      ),
      'api-categoria' => array(
        'type' => 'Segment',
        'options' => array(
          'route' => '/api/categorias[/:id]',
          'defaults' => array(
            'controller' => 'TemTudoAqui\Controller\CategoriaRest',
            'cache' => true
          )
        )
      ),
      'api-categoria-produtos' => array(
        'type' => 'Segment',
        'options' => array(
          'route' => '/api/categorias/[:id-categoria]/produtos[/:id]',
          'defaults' => array(
            'controller' => 'TemTudoAqui\Controller\ProdutoRest',
            'cache' => true
          ),
        ),
        'may_terminate' => false,
      ),
      'api-categoria-marcas' => array(
        'type' => 'Segment',
        'options' => array(
          'route' => '/api/categorias/[:id-categoria]/marcas[/:id]',
          'defaults' => array(
            'controller' => 'TemTudoAqui\Controller\MarcaRest',
            'cache' => true
          ),
        ),
        'may_terminate' => false,
      ),
      'api-categoria-marcas-produtos' => array(
        'type' => 'Segment',
        'options' => array(
          'route' => '/api/categorias/[:id-categoria]/marcas/[:id-marca]/produtos[/:id]',
          'defaults' => array(
            'controller' => 'TemTudoAqui\Controller\ProdutoRest',
            'cache' => true
          ),
        ),
        'may_terminate' => false,
      ),
      'api-produtos' => array(
        'type' => 'Segment',
        'options' => array(
          'route' => '/api/produtos[/:id]',
          'defaults' => array(
            'controller' => 'TemTudoAqui\Controller\ProdutoRest',
            'cache' => true
          )
        )
      ),
      'api-vendedor' => array(
        'type' => 'Segment',
        'options' => array(
          'route' => '/api/vendedores[/:id]',
          'defaults' => array(
            'controller' => 'TemTudoAqui\Controller\VendedorRest',
            'cache' => true
          )
        )
      )
    )
  ),
  'view_manager' => array(
    'strategies' => array(
      'ViewJsonStrategy'
    )
  ),
  'doctrine' => array(
    'driver' => array(
      'TemTudoAqui_driver' => array(
        'class' => 'Doctrine\ORM\Mapping\Driver\AnnotationDriver',
        'cache' => 'array',
        'paths' => array(__DIR__ . '/../src')
      ),
      'orm_default' => array(
        'drivers' => array(
          'TemTudoAqui' => 'TemTudoAqui_driver'
        ),
      ),
    ),
  ),
);