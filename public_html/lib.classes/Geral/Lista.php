<?php

class Lista{
      
	  protected $condicao;
	  protected $condicaoID;
	  protected $join;
	  protected $dados;
	  protected $rands;
	  protected $ID;
	  protected	$group;
	  protected	$enableClearCache = true;
	  
	  protected $tabela;
	  protected	$pre;
	  protected	$parametros;
	  protected	$total;
	  protected $con;
	  protected	$consultado = false;
	  
	  const		URL 	= 'url';
	  const		TEXTO 	= 'texto';
	  
	  public function __construct($tabela = '', $pre = true){
	      
	  	  if(!empty($tabela)) $this->tabela = $tabela;
		  $this->pre = $pre;
		  $this->parametros = BDConsultas::__VariaveisPadroes();
		  
		  //$this->dados = new AbrirDados(($pre ? Sistema::$BDPrefixo : '').$this->tabela, $this->parametros);
		  $this->con = BDConexao::__Abrir();
		  
	  }
	  
	  
	  public function condicoes($array = '', $valor = '', $campo = '', $operador = '=', $join = ''){
	  	  
	  	  $this->parametros['pos'] = 0;
		  
		  $where = '';
		  
		  $this->consultado = false;
		  
	  	  if(!empty($array) && is_array($array)){
	  	  	 
	  	  	 if(empty($this->condicao)) $where = 'WHERE';
	  	  	
	  	  	 for($i = 1; $i <= count($array); $i++){
	  	  	 	 
	  	  	 	 $array[$i]['operador'] = empty($array[$i]['operador']) ? '=' : $array[$i]['operador'];
	  	  	 	 
				 if(isset($array[$i]['OR'])){
					 if(($i > 1 || !empty($this->condicao)) && $array[$i]['OR'])
						$where .= ' OR';
					 elseif($i > 1 || !empty($this->condicao))
						$where .= ' AND';
				 }elseif($i > 1 || !empty($this->condicao))
					$where .= ' AND';
				 	
				 
				 $where .= ' '.$array[$i]['campo'].' '.$array[$i]['operador'].' "'.str_replace("\"", "\'", $array[$i]['valor']).'"';
				 
				 
	  	  	 	
			 }

			 $this->condicao .= !empty($array['join']) ? $array['join'].$where : $where;
	  	  	 $this->con->executar("SELECT * FROM ".($this->pre ? Sistema::$BDPrefixo : '').$this->tabela." ".$this->condicao);
	  	  	
	  	  }elseif(!empty($campo)){
			 
	  	  	 if(empty($campo)){
	  	  	 	$this->con->executar("SELECT * FROM ".($this->pre ? Sistema::$BDPrefixo : '').$this->tabela." WHERE id = ".$valor);
	  	  	    $this->condicaoID = $valor;
	  	  	 }else{
				 
	  	  	 	$this->con->executar("SELECT * FROM ".($this->pre ? Sistema::$BDPrefixo : '').$this->tabela." WHERE ".$campo.' '.$operador.' "'.$valor.'"');
				$this->condicao = $join.' WHERE '.$campo.' '.$operador.' "'.$valor.'"';
	  	  	    
	  	  	 }
	  	  	 
	  	  }elseif(!empty($join)){
			  
			  $this->join = $join;
	  	  	  $this->con->executar($this->join);
			  
		  }else{
	  	  	
	  	  	 $this->con->executar("SELECT * FROM ".($this->pre ? Sistema::$BDPrefixo : '').$this->tabela);
	  	  	
	  	  }
	     
	  	  $this->total = $this->con->getTotal();
	  	  $this->parametros['pos'] = 0;
		  
		  return $this;
	  	  
	  }
	  
	  /*
	  public function condicoes(SQL $sql, $zerar = false){
			
			$this->sql = $sql;
			
			if(!$zerar) $sql->setTabela($this->tabela);
			
			$sql->setWhere($this->condicao." ".$sql->getWhere);
			
			$this->dados->abrirOpcoes($this->parametros, '', $sql->getSQL());
			$this->total = $this->dados->total;
			$this->parametros['pos'] = 0;
			
			return $this;
			
	  }
	  */
	  public function listar($ordem = "", $campo = '', $campoRand = 'id'){
		  
		  if($this->parametros['pos'] < $this->parametros['limite']){
		  
			  if(!empty($ordem))
					$order = " ORDER BY ".$campo." ".$ordem;
			  
			  if(!empty($this->join)){
					
					if(!empty($this->rands)){ 
						
						if(preg_match("!WHERE!", $this->join)){
	
							$this->join = str_replace("WHERE ", "WHERE ".$this->rands." AND ", $this->join);
							$inicio = 0;
							
						}else{
							
							$this->join .= " WHERE ".$this->rands;
							
						}
				  
					}
				  
			  }elseif(preg_match("!WHERE!", $this->condicao)){
				  
				  if(!empty($this->rands)){
						$this->condicao = str_replace("WHERE ", "WHERE ".$this->rands." AND ", $this->condicao);
						$inicio = 0;
				  }
				  
			  }elseif(!empty($this->rands)) $this->condicao = "WHERE ".$this->rands.(!empty($this->condicao) ? " AND ".$this->condicao : '');
				
			if(preg_match("!rand\(\)!", $sql)){
				$limit = " LIMIT 0, 1";
			}else{
				$limit = " LIMIT ".$this->parametros['pos'].", 1";
			}
				
			  if(!empty($this->join)){
				 $this->con->executar($this->join.($this->group ? ' GROUP BY '.$this->group : '').$order.$limit);
			  }elseif(!empty($this->condicao)){
				 $this->con->executar("SELECT * FROM ".($this->pre ? Sistema::$BDPrefixo : '').$this->tabela." ".$this->condicao.($this->group ? ' GROUP BY '.$this->group : '').$order.$limit);
			  }elseif(!empty($this->condicaoID)){
				 $this->con->executar("SELECT * FROM ".($this->pre ? Sistema::$BDPrefixo : '').$this->tabela." WHERE id = ".$this->condicaoID);
			  }else
				 $this->con->executar("SELECT * FROM ".($this->pre ? Sistema::$BDPrefixo : '').$this->tabela.($this->group ? ' GROUP BY '.$this->group : '').$order.$limit);
			  
			  $a = $this->con->getRegistro();
			  
			  if($campo == 'rand()' && !empty($a[$campoRand])){
				  $this->rands = " ".$campoRand." <> '".$a[$campoRand]."'";
			  }
			  
			  $this->parametros['pos']++;
			  
			  return $a;
		  
		  }
		  
	  }
	  
	  public function resgatarObjetos(&$a){
			
			if(!empty($a[self::URL])){
				
					$lU = new ListaURLs;
					$lU->condicoes('', $a[self::URL], ListaURLs::ID);
					if($lU->getTotal() > 0)
						$a[self::URL] = $lU->listar();
					else
						$a[self::URL] = new URL;
					
			  }else
					$a[self::URL] = new URL;
			
			$valor = !empty($a[self::TEXTO]) ? (int) $a[self::TEXTO] : 0;
			
			if(!empty($valor)){
				
					$lT = new ListaTextos;
					$lT->condicoes('', $a[self::TEXTO], ListaTextos::ID);
					
					if($lT->getTotal() > 0)
						$a[self::TEXTO] = $lT->listar();
					else
						$a[self::TEXTO] = new Texto;
					
			  }elseif(!empty($a[self::TEXTO]) && is_int($a[self::TEXTO]))
					$a[self::TEXTO] = new Texto;
			
	  }
	  
	  public function setGroup($campo){
		  $this->group = $campo;
	  }
	  
	  public function inserir(&$t){
	  		
			if($this->enableClearCache)
				$this->clearCache();
			
			if(method_exists($t, "getURL")){
				if($t->getURL()->url != ''){
				
					$lU 			= new ListaURLs;
					$url 			= $t->getURL();
					$url->tabela	= $this->tabela;
					$url->campo		= self::URL;
					$url->valor		= $t->getId();
					$lU->inserir($url);
					
					$t->setURL($url);
					
				}
			}
			
			if(method_exists($t, "getTexto")){
				if($t->getTexto()->titulo != '' || $t->getTexto()->texto != '' || $t->getTexto()->getImagem()->getImage()->nome != ''){
					
					$lT 			= new ListaTextos;
					$tx				= $t->getTexto();
					$lT->inserir($tx);
					$t->setTexto($tx);
					
				}
			}
			
	  }
	  
	  public function alterar(&$t){
	  		
			if($this->enableClearCache)
				$this->clearCache();
			
			if(method_exists($t, "getURL")){
				if($t->getURL()->url != ''){
				
					$lU 	= new ListaURLs;
					$url 	= $t->getURL();
					
					$url->tabela	= $this->tabela;
					$url->campo		= self::URL;
					$url->valor		= $t->getId();
					
					if($url->getId() != '')
						$lU->alterar($url);
					else
						$lU->inserir($url);
					
					$t->setURL($url);
					
				
				}
			}
			
			if(method_exists($t, "getTexto")){
				if($t->getTexto()->texto != '' || $t->getTexto()->getImagem()->getImage()->nome != ''){
				
					$lT 			= new ListaTextos;
					$tx 			= $t->getTexto();
					
					if($tx->getId() != '')
						$lT->alterar($tx);
					else
						$lT->inserir($tx);
					
					$t->setTexto($tx);
					
				
				}
			}
			
	  }
	  
	  public function deletar($t){
			
			if($this->enableClearCache)
				$this->clearCache();
			
			$lU = new ListaURLs;
			if(method_exists($t, "getURL"))
				$lU->deletar($t->getURL());
			
			$lT = new ListaTextos;
			if(method_exists($t, "getTexto"))
				$lT->deletar($t->getTexto());
			
	  }
	  
	  public function setParametros($valor, $tipo = 'pos'){
	  		
		  $this->parametros[$tipo] = $valor;
		  return $this;
			
	  }
	  
	  public function getParametros($tipo = 'pos'){
	  		
		  return $this->parametros[$tipo];
			
	  }
	  
	  public function getTotal(){
		  
		  return $this->total;
	  
	  }
	  
	  public function getId($tipo){
		
		  do{
			  $id = Strings::__CreateId($tipo);
			  $this->con->consultar(Sistema::$BDPrefixo.$this->tabela, "WHERE ".self::ID." = '".$id."'");
		  }while($this->con->registrosTotal() > 0);
		  
		  return $id;
		  
	  }
	  
	  public function resetCondicoes(){
			
			$this->condicao = '';
			$this->condicaoID = '';
			$this->join = '';
		  	$this->consultado = false;
			
			return $this;


			
	  }
	  
	  public function getTabela(){
			return $this->tabela;  
	  }
	  
	  public function clearCache($pasta = ''){
		 
		 
		  
	  }
	  
	  public function clearImageCache($pasta){
		 
		 if(!empty($pasta)){

			 $dir = dir($pasta);
			 while($a = $dir->read()){
				 
				 if($a != '..' && $a != '.'){
					if(preg_match("!^(.*)size-([0-9]*)-([0-9]*)\.([jpg|jpeg|JPG|JPEG|gif|GIF|png|PNG])!", $a))
						@unlink($pasta."/".$a);
					
				 }
				 
			 }
		 
		 }
		  
	  }
	  
	  public function enableClearCache(){
		 $this->enableClearCache = true; 
	  }
	  
	  public function disableClearCache(){
		 $this->enableClearCache = false; 
	  }
	  
	  public function close(){
		 $this->con->close(); 
	  }

        public function __descruct(){
            $this->close();
        }
	  
}

?>