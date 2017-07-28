<?php

importar("Geral.Lista");
importar("LojaVirtual.Produtos.ProdutoCor");

class ListaProdutoCores extends Lista {
	
	const ID 			= 'id';
	const NOME 			= 'nome';
	const HEXADECIMAL	= 'hexadecimal';
	const IMAGEM		= 'imagem';
	
	public function __construct(){
		
		parent::__construct('produtos_cores');
		
	}
	
	public function listar($ordem = "ASC", $campo = self::ID){
		
		$info = parent::listar($ordem, $campo);
		
		if(!empty($info)){ 	
		
			$temp = new ProdutoCor($info[self::ID]);
			$temp->nome 		= $info[self::NOME];
			$temp->hexadecimal	= $info[self::HEXADECIMAL];
			
			if(!empty($info[self::IMAGEM])){ 
			
				$temp->setImagem(new Image(new Arquivos(Sistema::$caminhoURL.Sistema::$caminhoDataProdutoCores.$info[self::IMAGEM])));
			
			}
			
			return $temp;
		
		}
		
	}
	
	public function inserir(ProdutoCor &$m){

		if(!empty($m->getImagem()->url)){
			$m->getImagem()->open();
			$imagem = $m->getImagem()->saveImage(Sistema::$caminhoDiretorio.Sistema::$caminhoDataProdutoCores, $id);
		}
		
		$this->con->executar("INSERT INTO ".Sistema::$BDPrefixo.$this->tabela."(".self::NOME.", ".self::HEXADECIMAL.", ".self::IMAGEM.") VALUES('".$m->nome."','".$m->hexadecimal."','".$imagem."')");
		
		$id = $this->con->getId();
		
		$class 	= __CLASS__;
		$l 		= new $class;
		$l->condicoes('', $id, self::ID);
		
		$m = $l->listar();
		
	}
	
	public function alterar(ProdutoCor $m){
		
		$where = "WHERE ".self::ID." = '".$m->getId()."'";
		
		$this->con->alterar(Sistema::$BDPrefixo.$this->tabela, self::NOME, 			$m->nome, $where);
		$this->con->alterar(Sistema::$BDPrefixo.$this->tabela, self::HEXADECIMAL, 	$m->hexadecimal, $where);
		
		if(!empty($m->getImagem()->nome)){
			$m->getImagem()->open();
			$imagem = $m->getImagem()->saveImage(Sistema::$caminhoDiretorio.Sistema::$caminhoDataProdutoCores, $m->getId());
		}
		
		$this->con->alterar(Sistema::$BDPrefixo.$this->tabela, self::IMAGEM, 	$imagem, $where);
		
	}
	
	public function deletar(ProdutoCor $m){
				
		$where = "WHERE ".self::ID." = '".$m->getId()."'";
		
		Arquivos::__DeleteArquivo(Sistema::$caminhoDiretorio.Sistema::$caminhoDataProdutoCores.$m->getImagem()->nome.'.'.$m->getImagem()->extensao);
		
		$this->con->deletar(Sistema::$BDPrefixo.$this->tabela, $where);
		
	}
	
}	

?>