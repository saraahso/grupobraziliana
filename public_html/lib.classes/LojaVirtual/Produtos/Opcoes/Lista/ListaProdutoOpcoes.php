<?php

importar("Geral.Lista");
importar("LojaVirtual.Produtos.Opcoes.ProdutoOpcao");
importar("LojaVirtual.Produtos.Opcoes.Lista.ListaProdutoOpcaoGerados");

class ListaProdutoOpcoes extends Lista {
	
	const ID 					= 'id';
	const NOME 					= 'nome';
	const TIPO 					= 'tipo';
	const MULTI					= 'multi';
	const FILTRO				= 'filtro';
	const ABERTO 				= 'aberto';
	
	const VALOR_MULTI_TRUE		= 1;
	const VALOR_MULTI_FALSE		= 0;
	const VALOR_FILTRO_TRUE		= 1;
	const VALOR_FILTRO_FALSE	= 0;
	const VALOR_ABERTO_TRUE		= 1;
	const VALOR_ABERTO_FALSE	= 0;
	
	public function __construct(){
		
		parent::__construct('produtos_opcoes');
		
	}
	
	public function listar($ordem = "ASC", $campo = self::ID){
		
		$info = parent::listar($ordem, $campo);
		
		if(!empty($info)){ 	
		
			$temp = new ProdutoOpcao($info[self::ID]);
			
			$temp->nome 		= $info[self::NOME];
			$temp->tipo 		= $info[self::TIPO];
			$temp->multi 		= $info[self::MULTI] 	== self::VALOR_MULTI_TRUE ? true : false;
			$temp->filtro 		= $info[self::FILTRO] 	== self::VALOR_FILTRO_TRUE ? true : false;
			$temp->aberto 		= $info[self::ABERTO] 	== self::VALOR_ABERTO_TRUE ? true : false;		
			
			return $temp;
		
		}
		
	}
	
	public function inserir(ProdutoOpcao &$obj){
		
		$multi 	= $obj->multi 	? self::VALOR_MULTI_TRUE 	: self::VALOR_MULTI_FALSE;
		$filtro = $obj->filtro 	? self::VALOR_FILTRO_TRUE 	: self::VALOR_FILTRO_FALSE;
		$aberto = $obj->aberto 	? self::VALOR_ABERTO_TRUE 	: self::VALOR_ABERTO_FALSE;
		
		$this->con->executar("INSERT INTO ".Sistema::$BDPrefixo.$this->tabela."(".self::NOME.", ".self::TIPO.", ".self::MULTI.", ".self::FILTRO.", ".self::ABERTO.") VALUES('".$obj->nome."','".$obj->tipo."','".$multi."','".$filtro."','".$aberto."')");
		
		$id 	= $this->con->getId();
		
		$class 	= __CLASS__;
		$l 		= new $class;
		$l->condicoes('', $id, self::ID);
		
		$obj = $l->listar();
		
	}
	
	public function alterar(ProdutoOpcao $obj){
		
		$multi 	= $obj->multi 	? self::VALOR_MULTI_TRUE 	: self::VALOR_MULTI_FALSE;
		$filtro = $obj->filtro 	? self::VALOR_FILTRO_TRUE 	: self::VALOR_FILTRO_FALSE;
		$aberto = $obj->aberto 	? self::VALOR_ABERTO_TRUE 	: self::VALOR_ABERTO_FALSE;
		
		$where = "WHERE ".self::ID." = '".$obj->getId()."'";
		
		$this->con->alterar(Sistema::$BDPrefixo.$this->tabela, self::NOME, 		$obj->nome, $where);
		$this->con->alterar(Sistema::$BDPrefixo.$this->tabela, self::TIPO, 		$obj->tipo, $where);
		$this->con->alterar(Sistema::$BDPrefixo.$this->tabela, self::MULTI, 	$multi, $where);
		$this->con->alterar(Sistema::$BDPrefixo.$this->tabela, self::FILTRO, 	$filtro, $where);
		$this->con->alterar(Sistema::$BDPrefixo.$this->tabela, self::ABERTO, 	$aberto, $where);
		
	}
	
	public function deletar(ProdutoOpcao $obj){
				
		$where = "WHERE ".self::ID." = '".$obj->getId()."'";
		
		$lPOG = new ListaProdutoOpcaoGerados;
		$lPOG->condicoes('', $obj->getId(), ListaProdutoOpcaoGerados::OPCAO);
		if($lPOG->getTotal() == 0)
			$this->con->deletar(Sistema::$BDPrefixo.$this->tabela, $where);
		else
			throw new Exception('Produtos cadastrados com esta opção');
		
	}
	
}	

?>