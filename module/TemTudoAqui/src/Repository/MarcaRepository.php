<?php

namespace TemTudoAqui\Repository;

use Doctrine\ORM;

class MarcaRepository extends EntityRepository 
{
	
	public function getCollectionWithCategoria(\TemTudoAqui\Categoria $categoria, \TemTudoAqui\AbstractEntity $entity, $params = [], $fields = [], $type = self::RESULT_ARRAY)
	{
		
		$this->setFilter(function($alias, $query) use ($categoria){
			if($categoria->getId() > 0){
				$query->innerJoin("{$alias}.produtos", 'p')
					->innerJoin("p.categorias", 'c')
					->andWhere(
						$query->expr()->orX(
							"c.subreferencia LIKE :subreferencial", 
							"c.subreferencia = :subreferencia"
						)
					)
					->setParameter('subreferencial', $categoria->getSubreferencia().".%")
					->setParameter('subreferencia', $categoria->getSubreferencia())
					->groupBy('p.marca');
			}
			return $query;
		});
		
		$rs = parent::getCollection($entity, $params, $fields, $type);
				
		return $rs;
			
	}
	
}