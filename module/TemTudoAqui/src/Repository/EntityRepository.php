<?php

namespace TemTudoAqui\Repository;

use Doctrine\ORM,
		Doctrine\ORM\Tools\Pagination\Paginator;

class EntityRepository extends ORM\EntityRepository 
{
	
	const		RESULT_OBJECT						= 1;
	const 	RESULT_ARRAY						= 2;
	
	private	$conditions							= [];
	private $disabledefaultFilters	= false;
	
	public function getCollection(\TemTudoAqui\AbstractEntity $entity, $params = [], $fields = [], $type = self::RESULT_ARRAY)
	{
		
		$metaData 		= $this->getClassMetadata();
		$alias			= $metaData->getTableName();
		$query			= $this->createQueryBuilder($alias);
		$maps 			= $metaData->getAssociationMappings();
		$attrs 			= $metaData->getFieldNames();
		$obj			= $entity->toArray();
		
		if(count($fields) > 0){
			$f = '';
			foreach($fields as $v){
				$f .= $alias.".{$v}, ";
			}
			$f = substr($f, 0, -2);
			$query->select($f);
		}
		
		if(!$this->disabledefaultFilters){
			
			foreach($attrs as $field){
				if(($obj[$field] !== null && $obj[$field] !== '') || $obj[$field] === 0){
					if($metaData->getTypeOfField($field) == 'integer'){
						$query->andWhere($alias.'.'.$field.' = :'.$field)
								->setParameter($field, $obj[$field]);		
					}elseif($metaData->getTypeOfField($field) == 'float'){
						if((float)((string)$obj[$field]) > 0)
								$query->andWhere($alias.'.'.$field.' = :'.$field)
										->setParameter($field, ((float)((string)$obj[$field])));
					}elseif($metaData->getTypeOfField($field) == 'string'){
						if(strpos((string) $obj[$field], "|(equals)") !== false)
							$query->orWhere($alias.'.'.$field.' = :'.$field)
								->setParameter($field, addslashes((string) str_replace("|(equals)", "", $obj[$field])));
						elseif(strpos((string) $obj[$field], "(equals)") !== false)
							$query->andWhere($alias.'.'.$field.' = :'.$field)
								->setParameter($field, addslashes((string) str_replace("(equals)", "", $obj[$field])));
						elseif(strpos((string) $obj[$field], "|") === 0)
							$query->orWhere($alias.'.'.$field.' LIKE :'.$field)
								->setParameter($field, '%'.addslashes(str_replace('|', '', (string) $obj[$field])).'%');
						else
							$query->andWhere($alias.'.'.$field.' LIKE :'.$field)
								->setParameter($field, '%'.addslashes((string) $obj[$field]).'%');
					}elseif($obj[$field] instanceof \DateTime){
						$query->andWhere($alias.'.'.$field.' LIKE :'.$field)
									->setParameter($field, $obj[$field]->format("Y-m-d H:i:s"));
					}elseif($metaData->getTypeOfField($field) == 'boolean'){
						$bool = $obj[$field];
						if((string) $obj[$field] == (string)'0')
							$obj[$field] = 0;
						elseif((string) $obj[$field] == (string)'1')
							$obj[$field] = 1;
						$query->andWhere($alias.'.'.$field.' LIKE :'.$field)
							->setParameter($field, $obj[$field]);
						$obj[$field] = $bool;
					}
				}
			}
			
			foreach($maps as $fieldName => $field){
				$am = $metaData->getAssociationMapping($fieldName);
				if($am['type'] == 1 || $am['type'] == 2 || $am['type'] == 4){
					$query->leftJoin("{$alias}.{$fieldName}", $fieldName);
					if(count($fields) <= 0)
						$query->addSelect($fieldName);
					if($am['type'] == 4){
						foreach($metaData->getIdentifier() as $order => $identifier){
							$query->addGroupBy("{$alias}.id");
						}
					}
				}
				if(($am['type'] == 2 || $am['type'] == 1) && $obj[$fieldName] != null){
					$id 	= null;
					$column = key($am['targetToSourceKeyColumns']);
					if($obj[$fieldName] instanceof \TemTudoAqui\AbstractEntity)
						$obj2	= $obj[$fieldName]->toArray();
					else
						$obj2	= $obj[$fieldName];
					if(!empty($obj2[$column])){
						$id = $obj2[$column];
						$query->andWhere($fieldName.".".$column." = ".$id);
					}elseif($obj2[$column] === 0){
						$query->andWhere($alias.".".$fieldName." = 0");
					}
				}elseif(is_string($obj[$fieldName]) || is_numeric($obj[$fieldName])){
					if((int)((string)$obj[$fieldName]) < 0){
						$query->andWhere($alias.'.'.$fieldName.' IS NULL');
					}
				}
			}
			
		}
		
		if(!empty($this->conditions)){
			$query = $this->conditions[0]($alias, $query);
		}
				
		if(isset($params['order'])){
			$order	= $params['order'];
			if(is_array($order))
				if(current($order))
					$query = $query->orderBy($alias.".".key($order), current($order));
		}
		
		if(isset($params['limit'])){
			$limit	= $params['limit'];
			$query = $query->setMaxResults($limit);
		}
		
		if(isset($params['offset'])){
			$offset	= $params['offset'];
			$query = $query->setFirstResult($offset);
		}
					
		$rs = [
			'total' => count(new Paginator($query))
		];
		
		if($type === self::RESULT_OBJECT){
			$rs = $query->getQuery()->getResult(\Doctrine\ORM\AbstractQuery::HYDRATE_OBJECT);
		}elseif($type === self::RESULT_ARRAY){
			$rs['result'] = $query->getQuery()->getResult(\Doctrine\ORM\AbstractQuery::HYDRATE_ARRAY);
			/*foreach($rs as $k => $v){
				foreach($maps as $fieldName => $field){
					$am = $metaData->getAssociationMapping($fieldName);
					if($am['type'] == 8){
						$queryA = $this->createQueryBuilder('a');
						$queryA->innerJoin('a.'.$fieldName, 'b')
							->addSelect('b');
						foreach($metaData->getIdentifier() as $order => $identifier){
							$queryA->andWhere("a.{$identifier} = :value{$order}")
								->setParameter("value{$order}", $v[$identifier]);
						}
						$rsA = $queryA->getQuery()->getResult(\Doctrine\ORM\AbstractQuery::HYDRATE_ARRAY);
						if(count($rsA) > 0)
							$rs[$k][$fieldName] = $rsA[0][$fieldName];
					}
				}
			}*/
		}
		
		return $rs;
			
	}
	
	public function getByUrl($url)
	{
		$query			= $this->createQueryBuilder('p');
		
		$query->innerJoin('p.url', 'u', ORM\Query\Expr\Join::WITH, 'u.id = p.url');
		$query->andWhere('u.url = :url')->setParameter('url', $url);
		
		return $query->getQuery()->getResult()[0];		
	}
	
	protected function setFilter($conditions, $disableDefaultFilters = false)
	{
		$this->conditions[0]					= $conditions;
		$this->disableDefaultFilters	= $disableDefaultFilters;
	}
	
	protected function executeFilter($alias, $query)
	{
		return $this->conditions[0]($alias, $query);
	}
	
}