<?php

importar("Geral.Lista");
importar("Utilidades.Tickets.TicketPost");
importar("Utilidades.Tickets.Lista.ListaTickets");

class ListaTicketPosts extends Lista {
	
	const ID			= 'id';
	const TICKET		= 'ticket';
	const TEXTO			= 'texto';
	const NOME			= 'nome';
	const ARQUIVO		= 'arquivo';
	const DATAHORA		= 'datahora';
	
	public function __construct(){
		
		parent::__construct('tickets_postagens');
		
	}
	
	public function listar($ordem = "ASC", $campo = self::ID){
		
		$info = parent::listar($ordem, $campo);
		
		if(!empty($info)){ 
		
			$temp = new TicketPost($info[self::ID]);
			
			parent::resgatarObjetos($info);
			
			if(!empty($info[self::ARQUIVO]))
				$temp->setArquivo(new Arquivos(Sistema::$caminhoDiretorio.Sistema::$caminhoDataTickets.$info[self::ARQUIVO]));
			
			$temp->setDataHora(new DataHora($info[self::DATAHORA]));
			
			$temp->texto = $info[self::TEXTO];
			
			$temp->nome = $info[self::NOME];
			
			return $temp;
		
		}
		
	}
	
	public function inserir(TicketPost &$t, Ticket $ti){
		
		parent::inserir($t);
		
		if($t->getArquivo()->nome != '')
			$arquivo = $t->getArquivo()->saveArquivo(Sistema::$caminhoDiretorio.Sistema::$caminhoDataTickets);
		
		$this->con->executar("INSERT INTO ".Sistema::$BDPrefixo.$this->tabela."(".self::TICKET.", ".self::TEXTO.", ".self::ARQUIVO.", ".self::DATAHORA.", ".self::NOME.") VALUES('".$ti->getId()."','".$t->texto."','".$arquivo."','".$t->getDataHora()->mostrar("YmdHi")."','".$t->nome."')");
		
		$id = $this->con->getId();
		
		$class 	= __CLASS__;
		$l 		= new $class;
		$l->condicoes('', $id, self::ID);
		
		$t = $l->listar();
		
		$lT = new ListaTickets;
		$lT->alterar($ti);
		
		parent::alterar($t);
		
	}
	
	public function alterar(TicketPost $t){
		
		parent::alterar($t);
		
		$where = "WHERE ".self::ID." = '".$t->getId()."'";
		
		if($t->getArquivo()->nome != '')
			$arquivo = $t->getArquivo()->saveArquivo(Sistema::$caminhoDiretorio.Sistema::$caminhoDataTickets);
		
		$this->con->alterar(Sistema::$BDPrefixo.$this->tabela, self::TEXTO,			$t->texto, $where);
		$this->con->alterar(Sistema::$BDPrefixo.$this->tabela, self::NOME,			$t->nome, $where);
		$this->con->alterar(Sistema::$BDPrefixo.$this->tabela, self::ARQUIVO, 		$arquivo, $where);
		
	}
	
	public function deletar(TicketPost $t){
		
		parent::deletar($t);
		
		$where = "WHERE ".self::ID." = '".$t->getId()."'";
		
		$t->getArquivo()->deleteArquivo();
		
		$this->con->deletar(Sistema::$BDPrefixo.$this->tabela, $where);
		
	}
	
}

?>