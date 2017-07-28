<?php

importar("Geral.Lista");
importar("LojaVirtual.Produtos.Produto");
importar("LojaVirtual.Produtos.Opcoes.ProdutoOpcaoGerado");
importar("LojaVirtual.Produtos.Opcoes.Lista.ListaProdutoOpcoes");
importar("LojaVirtual.Produtos.Opcoes.Lista.ListaProdutoOpcaoValores");

class ListaProdutoOpcaoGerados extends Lista {
	
	const ID		= 'id';
	const PRODUTO	= 'produto';
	const OPCAO 	= 'opcao';
	const VALOR 	= 'valor';
	
	public function __construct(){
		
		parent::__construct('produtos_opcoes_gerados');
		
	}
	
	public function listar($ordem = "ASC", $campo = self::ID){
		
		$info = parent::listar($ordem, $campo);
		
		if(!empty($info)){ 	
		
			$temp = new ProdutoOpcaoGerado($info[self::ID]);
			
			$lPO = new ListaProdutoOpcoes;
			$lPO->condicoes('', $info[self::OPCAO], ListaProdutoOpcoes::ID);
			if($lPO->getTotal() > 0){
				
				$temp->setOpcao($lPO->listar());
				
				if($temp->getOpcao()->multi){
				
					$lPOV = new ListaProdutoOpcaoValores;
					$lPOV->condicoes('', $info[self::VALOR], ListaProdutoOpcaoValores::ID);
					if($lPOV->getTotal() > 0){
						$temp->setValor($lPOV->listar());
					}
				
				}else{
					
					$pOV = new ProdutoOpcaoValor;
					$pOV->valor = $info[self::VALOR];
					$temp->setValor($pOV);
					
				}
			
			}
			
			return $temp;
		
		}
		
	}
	
	public function inserir(ProdutoOpcaoGerado &$obj, Produto $objP){
		
		$valor = $obj->getValor()->getId() != '' ? $obj->getValor()->getId() : $obj->getValor()->valor;
		
		$this->con->executar("INSERT INTO ".Sistema::$BDPrefixo.$this->tabela."(".self::PRODUTO.", ".self::OPCAO.", ".self::VALOR.") VALUES('".$objP->getId()."','".$obj->getOpcao()->getId()."','".$valor."')");
		
		$id 	= $this->con->getId();
		
		$class 	= __CLASS__;
		$l 		= new $class;
		$l->condicoes('', $id, self::ID);
		
		$obj = $l->listar();
		
	}
	
	public function alterar(ProdutoOpcaoGerado $obj){
		
		$valor = $obj->getValor()->getId() != '' ? $obj->getValor()->getId() : $obj->getValor()->valor;
		
		$where = "WHERE ".self::ID." = '".$obj->getId()."'";
		
		$this->con->alterar(Sistema::$BDPrefixo.$this->tabela, self::VALOR, $valor, $where);
		
	}
	
	public function deletar(ProdutoOpcaoGerado $obj){
				
		$where = "WHERE ".self::ID." = '".$obj->getId()."'";
		
		$this->con->deletar(Sistema::$BDPrefixo.$this->tabela, $where);
		
	}
	
}	

?>