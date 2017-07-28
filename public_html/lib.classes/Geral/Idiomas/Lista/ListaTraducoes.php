<?php

importar("Geral.Lista");
importar("Geral.Idiomas.Traducao");

class Listatraducoes extends Lista {
	
	const ID 				= 'id';
	const IDIOMA 			= 'idioma';
	const CONTEUDO			= 'conteudo';
	const TRADUCAO 			= 'traducao';
	const CAMPOCONTEUDO		= 'campoconteudo';
	const TABELACONTEUDO	= 'tabelaconteudo';
	const IDCONTEUDO		= 'idconteudo';
	
	public function __construct(){
		parent::__construct('idiomas_traducoes');	
	}
	
	public function listar($ordem = "ASC", $campo = self::ID){
		
		$info = parent::listar($ordem, $campo);
		
		if(!empty($info)){ 
		
			$temp = new Traducao($info[self::ID]);
			$temp->conteudo 	= $info[self::CONTEUDO];
			$temp->traducao		= $info[self::TRADUCAO];
			
			$temp->setIdIdioma($info[self::IDIOMA]);
			$temp->setIdConteudo($info[self::IDCONTEUDO]);
			$temp->setTabelaConteudo($info[self::TABELACONTEUDO]);
			
			return $temp;
		
		}
		
	}
	
	public function inserir(Traducao &$t){
		
		try{
		
			$this->con->executar("INSERT INTO ".Sistema::$BDPrefixo.$this->tabela."(".self::IDIOMA.", ".self::CONTEUDO.", ".self::TRADUCAO.", ".self::TABELACONTEUDO.", ".self::IDCONTEUDO.",".self::CAMPOCONTEUDO.") VALUES('".$t->getIdIdioma()."',\"".eregi_replace('\"', '\'', $t->conteudo)."\",\"".eregi_replace('\"', '\'', $t->traducao)."\",'".$t->getTabelaConteudo()."','".$t->getIdConteudo()."','".$t->getCampoConteudo()."')");
			
			$class = __CLASS__;
			$l = new $class;
			$l->condicoes('', $this->con->getId(), self::ID);
			$t = $l->listar();
			
		}catch(Exception $e){
			
			throw new Exception($e->getMessage());
			
		}
		
	}
	
	public function alterar(traducao $t){
		
		$where = "WHERE ".self::ID." = '".$t->getId()."'";
		
		$this->con->executar("UPDATE ".Sistema::$BDPrefixo.$this->tabela." SET ".self::CONTEUDO." = \"".eregi_replace("\"", "'", $t->conteudo)."\" ".$where);
		$this->con->executar("UPDATE ".Sistema::$BDPrefixo.$this->tabela." SET ".self::TRADUCAO." = \"".eregi_replace("\"", "'", $t->traducao)."\" ".$where);
				
	}
	
	public function deletar(Traducao $t){
		
		$where = "WHERE ".self::ID." = '".$t->getId()."'";

		$this->con->deletar(Sistema::$BDPrefixo.$this->tabela, $where);
		
	}
	
}	

?>