<?php

namespace TemTudoAqui\Controller;

use Zend\View\Model\JsonModel;

class CategoriaRestController extends RestController
{

	public function __construct(){
		$this->entity	= 'TemTudoAqui\Categoria';
	}
	
	public function get($id)
	{
		$em 	= $this->getServiceLocator()->get('Doctrine\ORM\EntityManager');
		$repo = $em->getRepository($this->entity);
		
		$data = $repo->find($id);
		$subs	= [];
		foreach($data->getCategorias() as $cat)
			$subs[] = $cat->toArray();
		$data = $data->toArray();
		$data['categorias'] = $subs;
		return new JsonModel(array('data'=>$data, 'success'=>true));
	}

}