<?php

importar("Geral.Lista");
importar("Geral.Usuario");

class ListaUsuarios extends Lista {
	
	const ID			= 'id';
	const NIVEL			= 'nivel';
	const NOME			= 'nome';
	const LOGIN			= 'login';
	const SENHA			= 'senha';
	const TEXTO			= 'texto';
	const ENSINO		= 'ensino';
	const IMAGEM		= 'imagem';
	
	public function __construct(){
		
		parent::__construct('usuarios');
		
	}
	
	public function listar($ordem = "ASC", $campo = self::ID){
		
		$info = parent::listar($ordem, $campo);
		
		if(!empty($info)){ 
		
			$temp = new Usuario($info[self::ID]);
			
			$temp->nivel		= $info[self::NIVEL];
			$temp->nome			= $info[self::NOME];
			$temp->login		= $info[self::LOGIN];
			$temp->senha		= $info[self::SENHA];
			$temp->texto		= $info[self::TEXTO];
			$temp->ensino		= $info[self::ENSINO];		
			
			if(!empty($info[self::IMAGEM])){				
				$temp->setImagem(new Image(new Arquivos(Sistema::$caminhoDataUsuarios.$info[self::IMAGEM])));					
			}
			
			return $temp;
		
		}
		
	}
	
	public function inserir(Usuario &$u){
		
		if($u->getImagem()->nome != ''){
			$u->getImagem()->open();
			$imagem = $u->getImagem()->saveImage(Sistema::$caminhoDiretorio.Sistema::$caminhoDataUsuarios);
		}
		
		$this->con->executar("INSERT INTO ".Sistema::$BDPrefixo.$this->tabela."(".self::NIVEL.", ".self::NOME.", ".self::LOGIN.", ".self::SENHA.", ".self::TEXTO.", ".self::IMAGEM.", ".self::ENSINO.") VALUES('".$u->nivel."','".$u->nome."','".$u->login."','".$u->senha."','".$u->texto."','".$imagem."','".$u->ensino."')");
		
		$id = $this->con->getId();
		
		$class 	= __CLASS__;
		$l		= new $class;
		$l->condicoes('', $id, self::ID);
		
		$u = $l->listar();
		
	}
	
	public function alterar(Usuario $u){
		
		$where = "WHERE ".self::ID." = '".$u->getId()."'";
		
		if($u->getImagem()->nome != ''){
			$u->getImagem()->open();
			$imagem = $u->getImagem()->saveImage(Sistema::$caminhoDiretorio.Sistema::$caminhoDataUsuarios);
			$this->con->alterar(Sistema::$BDPrefixo.$this->tabela, self::IMAGEM,	$imagem, $where);
		}
		
		$this->con->alterar(Sistema::$BDPrefixo.$this->tabela, self::NIVEL,		$u->nivel, $where);
		$this->con->alterar(Sistema::$BDPrefixo.$this->tabela, self::NOME, 		$u->nome, $where);
		$this->con->alterar(Sistema::$BDPrefixo.$this->tabela, self::LOGIN,		$u->login, $where);
		$this->con->alterar(Sistema::$BDPrefixo.$this->tabela, self::SENHA,		$u->senha, $where);
		$this->con->alterar(Sistema::$BDPrefixo.$this->tabela, self::TEXTO, 	($u->texto), $where);
		$this->con->alterar(Sistema::$BDPrefixo.$this->tabela, self::ENSINO, 	$u->ensino, $where);		
		
	}
	
	public function deletar(Usuario $u){
		
		$where = "WHERE ".self::ID." = '".$u->getId()."'";
		
		Arquivos::__DeleteArquivo(Sistema::$caminhoDiretorio.Sistema::$caminhoDataUsuarios.$u->getImagem()->nome.".".$u->getImagem()->extensao);
		
		$this->con->deletar(Sistema::$BDPrefixo.$this->tabela, $where);
		
	}
	
}	

?>