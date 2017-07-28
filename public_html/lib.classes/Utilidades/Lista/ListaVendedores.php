<?php

importar("Geral.Lista");
importar("Utilidades.Vendedor");

class ListaVendedores extends Lista {

	const ID			= 'id';
	const NOME			= 'nome';
	const EMAIL			= 'email';
	const MSN			= 'msn';
	const SKYPE			= 'skype';
	const VOIP			= 'voip';
	const TELEFONE		= 'telefone';
	const RAMAL			= 'ramal';
	const IMAGEM		= 'imagem';
	const ORDEM			= 'ordem';
	
	public function __construct(){
		
		parent::__construct('vendedores');
		
	}
	
	public function listar($ordem = "ASC", $campo = self::ID){
		
		$info = parent::listar($ordem, $campo);
		
		if(!empty($info)){ 
		
			$temp = new Vendedor($info[self::ID]);
			
			parent::resgatarObjetos($info);
			
			$temp->nome		= $info[self::NOME];
			$temp->email	= $info[self::EMAIL];
			$temp->msn		= $info[self::MSN];
			$temp->skype	= $info[self::SKYPE];
			$temp->voip		= $info[self::VOIP];
			$temp->telefone	= $info[self::TELEFONE];
			$temp->ramal	= $info[self::RAMAL];	
			$temp->ordem	= $info[self::ORDEM];	
			
			if(!empty($info[self::IMAGEM])){
				
				$lI = new ListaImagens;
				$lI->caminhoEscrita = Sistema::$caminhoDiretorio.Sistema::$caminhoDataTextos;
				$lI->caminhoLeitura = Sistema::$caminhoURL.Sistema::$caminhoDataTextos;
				$lI->condicoes('', $info[self::IMAGEM], ListaImagens::ID);
				
				if($lI->getTotal() > 0)
					$temp->setImagem($lI->listar());
					
			}
			
			return $temp;
		
		}
		
	}
	
	public function inserir(Vendedor &$t){
		
		parent::inserir($t);
		
		if($t->getImagem()){
			if($t->getImagem()->getImage()){
				if($t->getImagem()->getImage()->nome != ''){
				
					$lI 	= new ListaImagens;
					$lI->caminhoEscrita = Sistema::$caminhoDiretorio.Sistema::$caminhoDataTextos;
					$lI->caminhoLeitura = Sistema::$caminhoURL.Sistema::$caminhoDataTextos;
					$img 	= $t->getImagem();
					$lI->inserir($img);
					$t->setImagem($img);
					
				}
			}
		}
		
		$this->con->executar("INSERT INTO ".Sistema::$BDPrefixo.$this->tabela."(".self::NOME.", ".self::EMAIL.", ".self::MSN.", ".self::SKYPE.", ".self::VOIP.", ".self::TELEFONE.", ".self::RAMAL.", ".self::IMAGEM.", ".self::ORDEM.") VALUES('".$t->nome."','".$t->email."','".$t->msn."','".$t->skype."','".$t->voip."','".$t->telefone."','".$t->ramal."','".$t->getImagem()->getId()."', '".$t->ordem."')");
		
		
		$id = $this->con->getId();
		
		$class 	= __CLASS__;
		$l		= new $class;
		$l->condicoes('', $id, self::ID);
		
		$t = $l->listar();
		
		parent::alterar($t);
		
		if($t->getImagem()){
			if($t->getImagem()->getImage()){
				if($t->getImagem()->getImage()->nome != ''){
					$lI 	= new ListaImagens;
					$lI->caminhoEscrita = Sistema::$caminhoDiretorio.Sistema::$caminhoDataTextos;
					$lI->caminhoLeitura = Sistema::$caminhoURL.Sistema::$caminhoDataTextos;
					$t->getImagem()->setSessao($this->getTabela(), $t->getId());
					
					$img = $t->getImagem();
					$lI->alterar($img);
					$t->setImagem($img);
				}
			}
		}
		
	}
	
	public function alterar(Vendedor $t){
		
		parent::alterar($t);
		
		$where = "WHERE ".self::ID." = '".$t->getId()."'";
		
		if($t->getImagem()->getImage()->nome != ''){
			
			$lI 	= new ListaImagens;
			$lI->caminhoEscrita = Sistema::$caminhoDiretorio.Sistema::$caminhoDataTextos;
			$lI->caminhoLeitura = Sistema::$caminhoURL.Sistema::$caminhoDataTextos;
			$img 	= $t->getImagem();
			
			if($img->getId() != '')
				$lI->alterar($img);
			else{
				$t->getImagem()->setSessao($this->getTabela(), $t->getId());
				$lI->inserir($img);
			}
			
			$t->setImagem($img);
			
		}
		
		$this->con->alterar(Sistema::$BDPrefixo.$this->tabela, self::IMAGEM,	$t->getImagem()->getId(), $where);
		$this->con->alterar(Sistema::$BDPrefixo.$this->tabela, self::NOME, 		$t->nome, $where);
		$this->con->alterar(Sistema::$BDPrefixo.$this->tabela, self::EMAIL,		$t->email, $where);
		$this->con->alterar(Sistema::$BDPrefixo.$this->tabela, self::MSN,	 	$t->msn, $where);
		$this->con->alterar(Sistema::$BDPrefixo.$this->tabela, self::SKYPE, 	$t->skype, $where);
		$this->con->alterar(Sistema::$BDPrefixo.$this->tabela, self::VOIP,	 	$t->voip, $where);	
		$this->con->alterar(Sistema::$BDPrefixo.$this->tabela, self::TELEFONE, 	$t->telefone, $where);	
		$this->con->alterar(Sistema::$BDPrefixo.$this->tabela, self::RAMAL, 	$t->ramal, $where);	
		$this->con->alterar(Sistema::$BDPrefixo.$this->tabela, self::ORDEM, 	$t->ordem, $where);	
		
	}
	
	public function deletar(Vendedor $t){
		
		parent::deletar($t);
		
		$where = "WHERE ".self::ID." = '".$t->getId()."'";
		
		$lI = new ListaImagens;
		
		$lI->deletar($t->getImagem());
		
		$this->con->deletar(Sistema::$BDPrefixo.$this->tabela, $where);
		
	}
	
}	

?>