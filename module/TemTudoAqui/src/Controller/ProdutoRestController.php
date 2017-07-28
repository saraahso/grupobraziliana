<?php

namespace TemTudoAqui\Controller;

use Zend\View\Model\JsonModel,
    TemTudoAqui\Categoria;

class ProdutoRestController extends RestController
{

  public function __construct()
  {
    $this->entity = 'TemTudoAqui\Produto';
  }

  // Listar - GET
  public function getList()
  {

    $em = $this->getServiceLocator()->get('Doctrine\ORM\EntityManager');
    $repo = $em->getRepository($this->entity);
    $entity = new $this->entity;
    $idCat = $this->getEvent()->getRouteMatch()->getParam('id-categoria');
    $idMar = $this->getEvent()->getRouteMatch()->getParam('id-marca');

    if (empty($idCat) && empty($idMar)) {
      $data = $repo->getCollection($this->getEntityWithValues(), $this->getParams(), $this->getFields());
    } else {
      if(!empty($idCat))
        $cat = $em->getRepository('TemTudoAqui\Categoria')->find($idCat);
      else
        $cat = new Categoria;

      $prod = $this->getEntityWithValues();
      if (!empty($idMar)) {
        $mar = $em->getRepository('TemTudoAqui\Marca')->find($idMar);
        $prod->setMarca($mar);
      }
      $data = $repo->getCollectionWithCategoria($cat, false, $prod, $this->getParams(), $this->getFields());
    }

    return new JsonModel(array('data' => $data, 'success' => true));

  }

  public function get($id)
  {
    $em = $this->getServiceLocator()->get('Doctrine\ORM\EntityManager');
    $repo = $em->getRepository($this->entity);
    $categoria = null;
    $marca = null;
    if ($this->getEvent()->getRouteMatch()->getParam('id-categoria'))
      $categoria = $em->getRepository('TemTudoAqui\Categoria')->find(
        $this->getEvent()->getRouteMatch()->getParam('id-categoria')
      );

    if ($this->getEvent()->getRouteMatch()->getParam('id-marca'))
      $marca = $em->getRepository('TemTudoAqui\Marca')->find(
        $this->getEvent()->getRouteMatch()->getParam('id-marca')
      );
    $data = $repo->find($id);
    if ($categoria || $marca) {
      if ($categoria) {
        $belong = false;
        foreach ($data->getCategorias() as $cat) {
          if (preg_match('!^' . addslashes($categoria->getSubreferencia()) . '(.*)$!', $cat->getSubreferencia()))
            $belong = true;
        }
        if (!$belong) {
          $this->getResponse()->setStatusCode(404);
          return new JsonModel(array('data' => null, 'success' => true));
        }
      }
      if ($marca) {
        if ($data->getMarca()->getId() != $marca->getId()) {
          $this->getResponse()->setStatusCode(404);
          return new JsonModel(array('data' => null, 'success' => true));
        }
      }
      $data = $data->toArray();
    } else
      $data = $data->toArray();
    return new JsonModel(array('data' => $data, 'success' => true));
  }

}