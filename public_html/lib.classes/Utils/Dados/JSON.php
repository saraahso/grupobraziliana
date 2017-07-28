<?php

class JSON {
	  
	private $vetor;
	private $js;
	
	public function __construct($v = ''){
		$this->vetor = $v;	
	}
	
	private static function _ConvertProcess($v = '', $withoutTitle = false){
	  
		if(is_array($v)){
		
			reset($v);
			$n = 0;
			
			do{
				
				$ismulti = false;
				if(is_array($v[key($v)])){
					if(!empty($v[key($v)][0]) && !empty($v[key($v)][count($v[key($v)])-1]) && count($v[key($v)]) > 1)
						$ismulti = true;
				}
				
				if(!is_array(current($v))) $js .= '"'.key($v).'": "'.current($v).'"';
				elseif($ismulti) $js .= '"'.key($v).'": ['.self::_ConvertProcess(current($v), true).']';
				elseif($withoutTitle) $js .= '{'.self::_ConvertProcess(current($v)).'}';
				elseif(is_array(current($v)) && count(current($v)) > 1) $js .= '"'.key($v).'": {'.self::_ConvertProcess(current($v), true).'}';
				else $js .= '"'.key($v).'": '.self::_ConvertProcess(current($v), true);
				
				$n++;
				
				if($n < count($v)) $js .= ', ';
				next($v);
			
			}while($n < count($v));
			
			return $js;
		
		}
	
	}
	
	public static function _Encode($v = ''){
		
		if(!empty($v)){
			$ismulti = false;
			if(is_array($v)){
				if(!empty($v[0]) && !empty($v[count($v)-1]) && count($v) > 0)
					$ismulti = true;
			}
			
			if($ismulti)
				return '['.self::_ConvertProcess($v, true).']';
			else
				return '{'.self::_ConvertProcess($v).'}';
		}
			
		//return json_encode($v);
		
	}
	
	public function encode(){
	 	return $this->js = self::_Encode($this->vetor);
	}
	
	public static function _Decode($v = ''){
		return json_decode($v);
	}
	
	public static function _Create($vetor){
		return new JSON($vetor);
	}
	  
}