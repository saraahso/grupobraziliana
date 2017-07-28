<?php

namespace TemTudoAqui\Repository;

use Doctrine\ORM;

class ProdutoRepository extends EntityRepository 
{
		
	public function getCollection(\TemTudoAqui\AbstractEntity $entity, $params = [], $fields = [], $type = self::RESULT_ARRAY)
	{
		
		$this->setFilter(function($alias, $query){
			$query->innerJoin("{$alias}.categorias", 'c')
				->addSelect('c')
				->innerJoin('c.url', 'uc')
				->addSelect('uc');
			return $query;
		});
		
		$rs = parent::getCollection($entity, $params, $fields, $type);
				
		return $rs;
		
	}
	
	public function getCollectionWithCategoria(\TemTudoAqui\Categoria $categoria, $onlyWithImage = false, \TemTudoAqui\AbstractEntity $entity, $params = [], $fields = [], $type = self::RESULT_ARRAY)
	{
		
		$this->setFilter(function($alias, $query) use ($categoria, $onlyWithImage){
			if($categoria->getId() > 0){
				$query->innerJoin("{$alias}.categorias", 'c')
					->andWhere(
						$query->expr()->orX(
							"c.subreferencia LIKE :subreferencial", 
							"c.subreferencia = :subreferencia"
						)
					)
					->setParameter('subreferencial', $categoria->getSubreferencia().".%")
					->setParameter('subreferencia', $categoria->getSubreferencia())
					->addSelect('c')
					->innerJoin('c.url', 'uc')
					->addSelect('uc');
			}else{
				$query->innerJoin("{$alias}.categorias", 'c')
					->addSelect('c')
					->innerJoin('c.url', 'uc')
					->addSelect('uc');
			}
			if($onlyWithImage){
				$query->leftJoin("{$alias}.imagens", 'i')
					->andWhere("i.sessao = 'produtos'")
					->orderBy('i.id', "DESC");
			};
			return $query;
		});
		
		$rs = parent::getCollection($entity, $params, $fields, $type);
				
		return $rs;
			
	}
	
}