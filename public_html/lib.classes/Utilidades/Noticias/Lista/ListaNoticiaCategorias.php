<?php

importar("Geral.Lista");
importar("Utilidades.Noticias.NoticiaCategoria");

class ListaNoticiaCategorias extends Lista {
	
	const ID					= 'id';
	const ORDEM					= 'ordem';
	
	public function __construct(){
		
		parent::__construct('noticias_categorias');
		
	}
	
	public function listar($ordem = "ASC", $campo = self::ID){
		
		$info = parent::listar($ordem, $campo);
		
		if(!empty($info)){ 
		
			$temp 			= new NoticiaCategoria($info[self::ID]);
			
			parent::resgatarObjetos($info);
			
			$temp->ordem	= $info[self::ORDEM];
			
			$temp->setURL($info[parent::URL]);
			if(is_object($info[parent::TEXTO]))
				$temp->setTexto($info[parent::TEXTO]);
		
			return $temp;
		
		}
		
	}
	
	public function inserir(NoticiaCategoria &$t){
		
		parent::inserir($t);
		
		$this->con->executar("INSERT INTO ".Sistema::$BDPrefixo.$this->tabela."(".self::ORDEM.", ".parent::URL.", ".parent::TEXTO.") VALUES('".$t->ordem."','".$t->getURL()->getId()."','".$t->getTexto()->getId()."')");
		
		$id = $this->con->getId();
		
		$class 	= __CLASS__;
		$l 		= new $class;
		$l->condicoes('', $id, self::ID);
		
		$t = $l->listar();
		
		parent::alterar($t);
		
	}
	
	public function alterar(NoticiaCategoria $t){
		
		parent::alterar($t);
		
		$where = "WHERE ".self::ID." = '".$t->getId()."'";
		
		$this->con->alterar(Sistema::$BDPrefixo.$this->tabela, self::ORDEM, 			$t->ordem, $where);
		$this->con->alterar(Sistema::$BDPrefixo.$this->tabela, parent::URL,				$t->getURL()->getId(), $where);
		$this->con->alterar(Sistema::$BDPrefixo.$this->tabela, parent::TEXTO,			$t->getTexto()->getId(), $where);
		
	}
	
	public function deletar(NoticiaCategoria $t){
		
		if($t->getNoticias()->getTotal() > 0)
			throw new Exception("Está categoria possui noticias cadastradas, não foi possível removê-la!");
		else{
		
			parent::deletar($t);
		
			$where = "WHERE ".self::ID." = '".$t->getId()."'";
			
			$this->con->deletar(Sistema::$BDPrefixo.$this->tabela, $where);

		}
		
	}
	
}

?>