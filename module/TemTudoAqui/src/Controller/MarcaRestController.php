<?php

namespace TemTudoAqui\Controller;

use Zend\View\Model\JsonModel,
		TemTudoAqui\Marca;

class MarcaRestController extends RestController
{

  public function __construct(){
		$this->entity	= 'TemTudoAqui\Marca';
	}
	
	// Listar - GET
	public function getList()
	{
		
		$em 		= $this->getServiceLocator()->get('Doctrine\ORM\EntityManager');
		$repo 	= $em->getRepository($this->entity);
		$entity	= new $this->entity;
		$idCat	= $this->getEvent()->getRouteMatch()->getParam('id-categoria');
		
		if(empty($idCat)){
			$data		= $repo->getCollection($this->getEntityWithValues(), $this->getParams(), $this->getFields());
		}else{
			$cat 	= $em->getRepository('TemTudoAqui\Categoria')->find($idCat);
			$data	= $repo->getCollectionWithCategoria($cat, $this->getEntityWithValues(), $this->getParams(), $this->getFields());
		}
		
		return new JsonModel(array('data'=>$data, 'success'=>true));

	}
	
	public function get($id)
	{
		$em 				= $this->getServiceLocator()->get('Doctrine\ORM\EntityManager');
		$repo 			= $em->getRepository($this->entity);
		$categoria	= null;
		if($this->getEvent()->getRouteMatch()->getParam('id-categoria')){
			$categoria 	= $em->getRepository('TemTudoAqui\Categoria')->find(
				$this->getEvent()->getRouteMatch()->getParam('id-categoria')
			);
		}
		$data = $repo->find($id);
		if($categoria){
			$data2 = $repo->getCollectionWithCategoria($categoria, new Marca(['id'=>$data->getId()]));
			if($data2['total'] === 0){
				$this->getResponse()->setStatusCode(404);
				$data = null;
			}else
				$data = $data->toArray();
		}else
			$data = $data->toArray();

		return new JsonModel(array('data'=>$data, 'success'=>true));
	}

}