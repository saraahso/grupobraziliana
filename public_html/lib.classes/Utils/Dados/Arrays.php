<?php

class Arrays {
	
	  private $a;
	
	  public function __construct($a){
		  
		  $this->a = $a;
		  
	  }
	  
	  public function verificarMatriz($a = ''){
		 
		  $temp = $this->a;
		  if(!empty($a)) $temp = $a;
			
		  if(is_array($temp)){
		  
		  	reset($temp);
		  	//echo count($temp)."<br>";
		  	for($i = 0; $i < count($temp); $i++){
				
					if($this->verificarMatriz(current($temp))){
						return true;
					}elseif(is_array(current($temp))){
						reset($temp);
						return true;
					}
					
					next($temp);
					
		  	}
		  	
		  	return false;
		 
		 }else
		 	return false;
		 
	  }
	  
	  public function ordenarMatriz($campo, $tipo = 'ASC'){
		  
		  $temp = $this->a;
		  
		  for($i = 0; $i < count($temp); $i++){
			  
			  $temp2[$i][$campo] = $temp[$i][$campo];
			  
			  reset($temp[$i]);
			  
			  for($k = 0; $k < count($temp[$i]); $k++){
				  
				  if($campo <> key($temp[$i])) $temp2[$i][key($temp[$i])] = current($temp[$i]);
				  next($temp[$i]);
				  
			  }
			  
		  }
		  
		  
		  @sort($temp2);
		  return $this->a = $temp2;
		  
	  }
	  
	  public function &getArray(){
		  
		  return $this->a;
		  
	  }
	  
	  public function reset(){
		  reset($this->a);
	  }
	  
	  public static function ArrayOrderBy(){
	    $args = func_get_args();
	    $data = (array) array_shift($args);
	    foreach ($args as $n => $field) {
	        if (is_string($field)) {
	            $tmp = array();
	            foreach ($data as $key => $row)
	                $tmp[$key] = $row[$field];
	            $args[$n] = $tmp;
	            }
	    }
	    $args[] = &$data;
	    call_user_func_array('array_multisort', $args);
	    return array_pop($args);
	}
	  
}

?>