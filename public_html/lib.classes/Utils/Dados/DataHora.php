<?php

class DataHora {
	
	private $data;
	
	public function __construct($data = ''){
		
		if(empty($data)) $data = date("YmdHis");
		$this->data = strtotime(str_replace('/', '-', $data));
		
	}
	
	public function diaSemanaExtenso(){
		
		$d = '';
		switch ($this->mostrar("N")){
			
			case 7:
				$d = 'Domingo';
            break;
            case 1:
				$d = 'Segunda';
            break;
            case 2:
				$d = 'Terça';
            break;
            case 3:
				$d = 'Quarta';
            break;
            case 4:
				$d = 'Quinta';
            break;
            case 5:
				$d = 'Sexta';
            break;
            case 6:
				$d = 'Sábado';
            break;
			
		}
		
		return $d;
		
	}
	
	public function mesExtenso(){
		
		$d = '';
		switch ($this->mostrar("m")){
			
			case 1:
				$d = 'Janeiro';
            break;
            case 2:
				$d = 'Fevereiro';
            break;
            case 3:
				$d = 'Março';
            break;
            case 4:
				$d = 'Abril';
            break;
            case 5:
				$d = 'Maio';
            break;
            case 6:
				$d = 'Junho';
            break;
            case 7:
				$d = 'Julho';
            break;
			case 8:
				$d = 'Agosto';
            break;
			case 9:
				$d = 'Setembro';
            break;
			case 10:
				$d = 'Outubro';
            break;
			case 11:
				$d = 'Novembro';
            break;
			case 12:
				$d = 'Dezembro';
            break;
			
		}
		
		return $d;
		
	}
	
	public function upDias($dias){
		
		$temp = $this->mostrar("YmdHis");
		$this->data += ((3600*24)*$dias);
		if($temp == $this->mostrar("YmdHis")) $this->data += ((3600*24));
		
		return $this;
		
	}
	
	public function downDias($dias){
		
		$temp = $this->mostrar("YmdHis");
		$this->data -= ((3600*24)*$dias);
		if($temp == $this->mostrar("YmdHis")) $this->data -= ((3600*24));
		
		return $this;
		
	}
	
	public function upHoras($hr, $minutos = 0, $segundos = 0){
		
		$this->data = strtotime(date("YmdHis", strtotime($this->mostrar("YmdHis")." +".$hr." hour +".$minutos." minute +".$segundos." second")));
		
		return $this;
		
	}
	
	public function downHoras($hr, $minutos = 0, $segundos = 0){
		
		$this->data = strtotime(date("YmdHis", strtotime($this->mostrar("YmdHis")." -".$hr." hour -".$minutos." minute -".$segundos." second")));
		
		return $this;
		
	}
	
	public function mostrar($tipo = 'd/m/Y'){
		
		return date($tipo, $this->data);
		
	}
	
	public function diferencaDias($data){
		
		$dif = ($this->tempo()-$data->tempo())/86400;
		if($dif == 0){
			
			return ($this->tempo("H:i:s")-$data->tempo("H:i:s"))/2073600;
			
		}else
		    return $dif;
		
	}
	
	public function diferencaHoras($data, $horamenos = '00:00', $horamais = '00:00') {  
    
	    $inicio = $data->mostrar("H:i");
	    $fim = $this->mostrar("H:i");
		
		if($fim < $inicio){
			$temp = explode(":", $fim);
			$fim = ($temp[0]+24).":".$temp[1];
		}
		
	    $data = $data->mostrar("d-m-Y");
	    $dataf = $this->mostrar("d-m-Y");
	
        if(!is_array($inicio)) $inicio = explode(":",$inicio);
        if(!is_array($fim)) $fim = explode(":",$fim);  
        
		if(!is_array($data)){
			
            if(strstr("-",$data{4}))
			    $data = explode("-",$data);  
            elseif(strstr("-",$data{2})){  
                $aux = explode("-",$data);  
                $data = array($aux[2],$aux[1],$aux[0]);  
            } 
			
        }else  
            if(strlen($data[0]) == 2) $data = array($data[2],$data[1],$data[0]);  
        
		if(!is_array($dataf)){ 
		
            if(strstr("-",$dataf{4}))  
                $dataf = explode("-",$dataf);  
            elseif(strstr("-",$dataf{2})){  
                $aux2 = explode("-",$dataf);  
                $dataf = array($aux2[2],$aux2[1],$aux2[0]);  
            }  
        }else  
            if(strlen($dataf[0]) == 2) $dataf = array($dataf[2],$dataf[1],$dataf[0]);  
          
        if(!is_array($horamenos)) $horamenos = explode(":",$horamenos);  
        if(!is_array($horamais)) $horamais = explode(":",$horamais);  
     
        $time_inicio = (($inicio[0]*60)*60)+($inicio[1]*60)+$inicio[2];  
        $time_fim    = (($fim[0]*60)*60)+($fim[1]*60)+$fim[2]; 
        $time_menos  = (($horamenos[0]*60)*60)+($horamenos[1]*60)+$horamenos[2]; 
        $time_mais   = (($horamais[0]*60)*60)+($horamais[1]*60)+$horamais[2];  
     
        $t[0] = floor(((($time_fim-$time_inicio)+$time_mais)-$time_menos)/60);  
        $t[1] = floor((((($time_fim-$time_inicio)+$time_mais)-$time_menos)/60)/60); 
        
		$h = $t[1];  
        $m = $t[0]-($t[1]*60);  
        
		if($data[0] != $dataf[0]){  
            $aux2 = ($dataf[0]-$data[0])*365;  
            $valor = $valor+$aux2;  
        }  
        if($data[1] != $dataf[1]){  
            $aux2 = ($dataf[1]-$data[1])*30;  
            $valor = $valor+$aux2;  
        }  
        if($data[2] != $dataf[2]) {                  
            $aux2 = $dataf[2]-$data[2];  
            $valor = $valor+$aux2;               
        } 
		
        $t[1] = $t[1]+$valor*24;  //$valor = numero de dias a mais                 
        
		if($h < 0) $h = $t[1];         
		if ($h < 10) $h = "0$h";  
        if ($m < 10) $m = "0$m";
		
        $dif = $h.":".$m; 
        
		return $dif;  
		
	}
	
	public function tempo($d = "Y-m-d"){
		
		return strtotime($this->mostrar($d));
		
	}
	
	public static function __Create($n){
		
		return new DataHora($n);
		
	}
	
}

?>