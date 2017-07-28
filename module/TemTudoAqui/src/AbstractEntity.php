<?php

namespace TemTudoAqui;

use Zend\Stdlib\Hydrator;

abstract class AbstractEntity {
	
	public function __construct(array $options = array())
    {
        (new Hydrator\ClassMethods)->hydrate($options,$this);
    }
	
	public function toArray()
	{
		$obj = (new Hydrator\ClassMethods())->extract($this);
		/*foreach($obj as $k => $v){
			if($v instanceof AbstractEntity){
				try{
					$obj[$k] = $v->toArray();
				}catch(\Doctrine\ORM\EntityNotFoundException $e){
					$obj[$k] = $v;
				}
			}elseif($v instanceof \Doctrine\ORM\PersistentCollection){
				$data = [];
				foreach($v as $k2 => $v2){
					if(method_exists($v2, 'toArray')){
						$data[] = $v2->toArray();
					}
				}
				$obj[$k] = $data;
			}
		}*/
		return $obj;
  }
	
}