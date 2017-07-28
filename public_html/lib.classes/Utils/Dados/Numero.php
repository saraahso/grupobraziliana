<?php

class Numero {

	public $num;
	
	public function __construct($num = 0){
		
		$this->num = $num;
		
		   
	}
	
	public function limparNumero(){
		
		$valor = $this->num;
		
		$valor = str_replace('-', '', $valor);
		$valor = str_replace("/", '', $valor);
		$valor = str_replace("\(", '', $valor);
		$valor = str_replace(")", '', $valor);
		$valor = str_replace("\[", '', $valor);
		$valor = str_replace("]", '', $valor);
		$valor = str_replace("_", '', $valor);
		$valor = str_replace(" ", '', $valor);
		
		if(preg_match("!,!", $valor)){
			$valor = str_replace(".", "", $valor);
			$valor = str_replace(",", ".", $valor);
		}
		
		$this->num = $valor;
		
		return $this;
		
	}
	
	public function moeda(){
        /*
		$n = explode(',', $this->num);
		
		if(count($n) > 1){
		
			if(preg_match("\.", $n[0]) && count($n) >= 2){
				
				$this->num = str_replace('.', ',', $n[0]);
				$this->num .= '.'.$n[1];
				
			}elseif(count($n) == 2 && $this->num > 0){
				
				$this->num = $n[0].'.'.$n[1];	
			}
		
		}*/
		
		$m = number_format($this->num, 2, ',', '.');
		
		return $m;
	
	}
	
	public function formatar($decimal = '.', $milhares = '', $qtdDecimais = 2){
		
		/*$n = explode(',', $this->num);
		
		if(count($n) > 1){
		
			if(str("\.", $n[0])){
				
				$n[0] = str_replace('.', ',', $n[0]);
				
			}
			
			if(count($n) == 2){
				
				$this->num = $n[0].'.'.$n[1];	
			}
		
		}*/
		$this->limparNumero();
		$m = @number_format($this->num, $qtdDecimais, $decimal, $milhares);
		
		return $m;
	
	}
	
	public function __toString(){
		return $this->num;
	}
   	
	public static function __CreateNumero($v){
		return new Numero($v);
	}

}

?>