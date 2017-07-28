<?php

importar("Geral.Lista");
importar("LojaVirtual.Produtos.Opcoes.ProdutoOpcao");
importar("LojaVirtual.Produtos.Opcoes.ProdutoOpcaoValor");
importar("LojaVirtual.Produtos.Opcoes.Lista.ListaProdutoOpcaoGerados");

class ListaProdutoOpcaoValores extends Lista {
	
	const ID 		= 'id';
	const OPCAO		= 'opcao';
	const VALOR 	= 'valor';
	const COR	 	= 'cor';
	const IMAGEM 	= 'imagem';
	
	public function __construct(){
		
		parent::__construct('produtos_opcoes_valores');
		
	}
	
	public function listar($ordem = "ASC", $campo = self::ID){
		
		$info = parent::listar($ordem, $campo);
		
		if(!empty($info)){ 	
		
			$temp = new ProdutoOpcaoValor($info[self::ID]);
			
			$temp->valor 		= $info[self::VALOR];	
			$temp->cor	 		= $info[self::COR];	
			
			if(!empty($info[self::IMAGEM]))
				$temp->setImagem(new Image(new Arquivos(Sistema::$caminhoURL.Sistema::$caminhoDataProdutoOpcoes.$info[self::IMAGEM])));
			
			return $temp;
		
		}
		
	}
	
	public function inserir(ProdutoOpcaoValor &$obj, ProdutoOpcao $objP){
		
		if(!empty($obj->getImagem()->url)){
			$obj->getImagem()->open();
			$imagem = $obj->getImagem()->saveImage(Sistema::$caminhoDiretorio.Sistema::$caminhoDataProdutoOpcoes);
		}else
			$imagem = '';
		
		$this->con->executar("INSERT INTO ".Sistema::$BDPrefixo.$this->tabela."(".self::OPCAO.", ".self::VALOR.", ".self::COR.", ".self::IMAGEM.") VALUES('".$objP->getId()."','".$obj->valor."','".$obj->cor."','".$imagem."')");
		
		$id 	= $this->con->getId();
		
		$class 	= __CLASS__;
		$l 		= new $class;
		$l->condicoes('', $id, self::ID);
		
		$obj = $l->listar();
		
	}
	
	public function alterar(ProdutoOpcaoValor $obj){
		
		$where = "WHERE ".self::ID." = '".$obj->getId()."'";
		
		$this->con->alterar(Sistema::$BDPrefixo.$this->tabela, self::VALOR, $obj->valor, $where);
		$this->con->alterar(Sistema::$BDPrefixo.$this->tabela, self::COR, 	$obj->cor, $where);
		
		if(!empty($obj->getImagem()->url)){
			$obj->getImagem()->open();
			$imagem = $obj->getImagem()->saveImage(Sistema::$caminhoDiretorio.Sistema::$caminhoDataProdutoOpcoes);
			$this->con->alterar(Sistema::$BDPrefixo.$this->tabela, self::IMAGEM, 	$imagem, $where);
		}
		
	}
	
	public function deletar(ProdutoOpcaoValor $obj){
				
		$where = "WHERE ".self::ID." = '".$obj->getId()."'";
		
		$lPOV = new ListaProdutoOpcaoValores;
		
		$lPOG = new ListaProdutoOpcaoGerados;
		$lPOG->condicoes('', $obj->getId(), ListaProdutoOpcaoGerados::VALOR);
		
		if($lPOG->getTotal() == 0){
			Arquivos::__DeleteArquivo(Sistema::$caminhoDiretorio.Sistema::$caminhoDataProdutoOpcoes.$m->getImagem()->nome.'.'.$m->getImagem()->extensao);
			$this->con->deletar(Sistema::$BDPrefixo.$this->tabela, $where);
			$this->con->deletar(Sistema::$BDPrefixo.$lPOV->getTabela(), "WHERE ".ListProdutoOpcaoValores::OPCAO." = '".$obj->getId()."'");
		}else
			throw new Exception('Produtos cadastrados com este valor');
		
		
		
	}
	
}	

?>