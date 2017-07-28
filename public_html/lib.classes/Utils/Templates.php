<?php

importar('Utils.Arquivos');

class Templates {
       
	   protected $arquivo = '';
	   protected $parteRepeticao = array();
	   protected $formado = false;
	
	   protected $nomeAtualParticao = '';
	   
	   public $javaScript = '';
	   
	   public function __construct(Arquivos $arquivo){
		   
		   $this->arquivo = $arquivo;
		   
	   }
	   
	   public function createJavaScript(){
		   
		   return $this->javaScript = new Templates(Arquivos::__Create($this->cutParte('javaScript')));
		   
	   }
	   
	   public function trocar($variavel = '', $valor = ''){
	        
			$valor = (string) $valor;
			
			if(empty($variavel)) $this->arquivo->arquivo = preg_replace('!\{([.]?[a-zA-Z0-9_/-])*\}!', '', $this->arquivo->arquivo);
			
		    $this->arquivo->arquivo = str_replace('{'.$variavel.'}', $valor, $this->arquivo->arquivo);
		    
	   }
	   
	   public function cutParte($separavel){
		    
			$s = explode('{'.$separavel.'}', $this->arquivo->arquivo);
			$this->arquivo->arquivo = $s[0].(count($s) > 2 ? $s[2] : '');
			
			return count($s) > 1 ? $s[1] : '';
			
	   }
	   
	   public function condicao($separavel = '', $condicao = false){
	        
			$sep = $separavel;
			if(empty($separavel)){ $separavel = '\{condicao([.]?[a-zA-Z0-9_/-])*\}'; }else{ $separavel = '\{'.$separavel.'\}';}
			
		    $principal = preg_split('{'.$separavel.'}', $this->arquivo->arquivo);
			
			$partes = explode('{else:'.$sep.'}', count($principal) > 1 ? $principal[1] : '');
			
			if($condicao){
			   
			   $this->arquivo->arquivo = $principal[0].$partes[0].(count($principal) > 2 ? $principal[2] : '');
			   
			}elseif(!empty($partes[1])){
			   
			   $this->arquivo->arquivo = $principal[0].(count($partes) > 1 ? $partes[1] : '').(count($principal) > 2 ? $principal[2] : '');
			   
			}else{
			   
			   $this->arquivo->arquivo = $principal[0].(count($principal) > 2 ? $principal[2] : '');
			   
			}
		   
	   }
	   	
	   public function getParte($separavel){
		     
			$s = explode('{'.$separavel.'}', $this->arquivo->arquivo);
			return $s[1];
			 
	   }
		
	   public function createRepeticao($separavel){
	       
		   $s = explode('{'.$separavel.'}', $this->arquivo->arquivo);
		   $this->parteRepeticao[$separavel]['template'] = count($s) > 1 ? $s[1] : '';	
		   
		   $this->nomeAtualParticao = $separavel;
		   
	   }
	   
	   public function repetir($separavel = ''){
		   
		   if(empty($separavel)) $separavel = $this->nomeAtualParticao;
		   
		   $this->parteRepeticao[$separavel][count($this->parteRepeticao[$separavel])] = new Templates(Arquivos::__Create($this->parteRepeticao[$separavel]['template']));
		   
		   $this->parteRepeticao[$separavel]['atual'] = &$this->parteRepeticao[$separavel][count($this->parteRepeticao[$separavel])-1];
		   
	   }
	   
	   public function enterRepeticao($separavel = ''){
	       
		   if(empty($separavel)) $separavel = $this->nomeAtualParticao;
		   
		   return $this->parteRepeticao[$separavel]['atual'];
		   
	   }
	   
	   public function getRepeticoes($separavel = ''){		   
		   return $this->parteRepeticao[$separavel];		   
	   }
	   
	   public function changeRepeticao($separavel, $valor){
	       
		   $s = explode('{'.$separavel.'}', $this->arquivo->arquivo);
		   $this->arquivo->arquivo = $s[0].$valor.(count($s) > 2 ? $s[2] : '');
		   
	   }
	   
	   public function concluir(){
		   
		   reset($this->parteRepeticao);

		   if(count($this->parteRepeticao) > 0){
		      
			  do{
		           
				   reset($this->parteRepeticao[key($this->parteRepeticao)]);
				   
				   $arquivoFormado = '';
				   
				   while(next($this->parteRepeticao[key($this->parteRepeticao)])){
				   
				          if(key($this->parteRepeticao[key($this->parteRepeticao)]) <> 'atual')
				              $arquivoFormado .= $this->parteRepeticao[key($this->parteRepeticao)][key($this->parteRepeticao[key($this->parteRepeticao)])]->concluir();
				          
				   }
				   
				   $this->changeRepeticao(key($this->parteRepeticao), $arquivoFormado);
				   
			  }while(next($this->parteRepeticao));
		   
		   }
		   
		   $this->formado = true;
		   
		   $this->trocar();
		   $this->condicao();
		   
		   $this->arquivo->arquivo = str_replace('\\}', '}', $this->arquivo->arquivo);
		   
		   return $this->arquivo->arquivo;
		   
	   }
	   
	   public function mostrar(){
	       
		   if(!$this->formado) $this->concluir();
		   echo $this->arquivo->arquivo;
		   
	   }
	   
	   public function getArquivo(){
			return $this->arquivo;   
	   }
	
}

?>