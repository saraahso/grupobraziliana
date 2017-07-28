<?php

importar("Geral.Lista");
importar("LojaVirtual.Produtos.ProdutoPedra");

class ListaProdutoPedras extends Lista {
	
	const ID 			= 'id';
	const NOME 			= 'nome';
	const IMAGEM		= 'imagem';
	
	public function __construct(){
		
		parent::__construct('produtos_pedras');
		
	}
	
	public function listar($ordem = "ASC", $campo = self::ID){
		
		$info = parent::listar($ordem, $campo);
		
		if(!empty($info)){ 	
		
			$temp = new ProdutoPedra($info[self::ID]);
			$temp->nome 		= $info[self::NOME];
			
			if(!empty($info[self::IMAGEM])){ 
			
				$temp->setImagem(new Image(new Arquivos(Sistema::$caminhoURL.Sistema::$caminhoDataProdutoPedras.$info[self::IMAGEM])));
			
			}
			
			return $temp;
		
		}
		
	}
	
	public function inserir(ProdutoPedra &$m){

		if(!empty($m->getImagem()->url)){
			$m->getImagem()->open();
			$imagem = $m->getImagem()->saveImage(Sistema::$caminhoDiretorio.Sistema::$caminhoDataProdutoPedras, $id);
		}
		
		$this->con->executar("INSERT INTO ".Sistema::$BDPrefixo.$this->tabela."(".self::NOME.", ".self::IMAGEM.") VALUES('".$m->nome."','".$imagem."')");
		
		$id = $this->con->getId();
		
		$class 	= __CLASS__;
		$l 		= new $class;
		$l->condicoes('', $id, self::ID);
		
		$m = $l->listar();
		
	}
	
	public function alterar(ProdutoPedra $m){
		
		$where = "WHERE ".self::ID." = '".$m->getId()."'";
		
		$this->con->alterar(Sistema::$BDPrefixo.$this->tabela, self::NOME, 			$m->nome, $where);
		
		if(!empty($m->getImagem()->nome)){
			$m->getImagem()->open();
			$imagem = $m->getImagem()->saveImage(Sistema::$caminhoDiretorio.Sistema::$caminhoDataProdutoPedras, $m->getId());
		}
		
		$this->con->alterar(Sistema::$BDPrefixo.$this->tabela, self::IMAGEM, 	$imagem, $where);
		
	}
	
	public function deletar(ProdutoPedra $m){
				
		$where = "WHERE ".self::ID." = '".$m->getId()."'";
		
		Arquivos::__DeleteArquivo(Sistema::$caminhoDiretorio.Sistema::$caminhoDataProdutoPedras.$m->getImagem()->nome.'.'.$m->getImagem()->extensao);
		
		$this->con->deletar(Sistema::$BDPrefixo.$this->tabela, $where);
		
	}
	
}	

?>