<?php

importar("Geral.Lista");
importar("Utilidades.Galerias.GaleriaCategoria");

class ListaGaleriaCategorias extends Lista {
	
	const ID					= 'id';
	const TITULO				= 'titulo';
	const PROTEGIDO				= 'protegido';
	const ALTURA				= 'altura';
	const LARGURA				= 'largura';
	const ALTURAMEDIA			= 'alturam';
	const LARGURAMEDIA			= 'larguram';
	const ALTURAPEQUENA			= 'alturap';
	const LARGURAPEQUENA		= 'largurap';
	
	const VALOR_PROTEGIDO_TRUE	= 1;
	const VALOR_PROTEGIDO_FALSE	= 0;
	
	public function __construct(){
		
		parent::__construct('galerias_categorias');
		
	}
	
	public function listar($ordem = "ASC", $campo = self::ID){
		
		$info = parent::listar($ordem, $campo);
		
		if(!empty($info)){ 
		
			$temp 			= new GaleriaCategoria($info[self::ID]);
			
			parent::resgatarObjetos($info);
			
			$temp->titulo	= $info[self::TITULO];
			$temp->protegido	= $info[self::PROTEGIDO] == self::VALOR_PROTEGIDO_TRUE ? true : false;
			
			$temp->setAltura($info[self::ALTURA]);
			$temp->setLargura($info[self::LARGURA]);
			
			$temp->setAlturaMedia($info[self::ALTURAMEDIA]);
			$temp->setLarguraMedia($info[self::LARGURAMEDIA]);
			
			$temp->setAlturaPequena($info[self::ALTURAPEQUENA]);
			$temp->setLarguraPequena($info[self::LARGURAPEQUENA]);
			
			$temp->setURL($info[parent::URL]);
			
			if(is_object($info[parent::TEXTO]))
				$temp->setTexto($info[parent::TEXTO]);
		
			return $temp;
		
		}
		
	}
	
	public function inserir(GaleriaCategoria &$t){
		
		parent::inserir($t);
		
		$this->con->executar("INSERT INTO ".Sistema::$BDPrefixo.$this->tabela."(".self::TITULO.", ".parent::URL.", ".parent::TEXTO.", ".self::PROTEGIDO.", ".self::ALTURA.", ".self::LARGURA.", ".self::ALTURAMEDIA.", ".self::LARGURAMEDIA.", ".self::ALTURAPEQUENA.", ".self::LARGURAPEQUENA.") VALUES('".$t->titulo."','".$t->getURL()->getId()."','".$t->getTexto()->getId()."','".($t->protegido ? self::VALOR_PROTEGIDO_TRUE : self::VALOR_PROTEGIDO_FALSE)."','".$t->getAltura()->formatar()."','".$t->getLargura()->formatar()."','".$t->getAlturaMedia()->formatar()."','".$t->getLarguraMedia()->formatar()."','".$t->getAlturaPequena()->formatar()."','".$t->getLarguraPequena()->formatar()."')");
		
		$id = $this->con->getId();
		
		if(!file_exists(Sistema::$caminhoDiretorio.Sistema::$caminhoDataGalerias.$t->getLargura()->formatar('', '', 0)))
			mkdir(Sistema::$caminhoDiretorio.Sistema::$caminhoDataGalerias.$t->getLargura()->formatar('', '', 0));
		if(!file_exists(Sistema::$caminhoDiretorio.Sistema::$caminhoDataGalerias.$t->getAlturaMedia()->formatar('', '', 0)))
			mkdir(Sistema::$caminhoDiretorio.Sistema::$caminhoDataGalerias.$t->getLarguraMedia()->formatar('', '', 0));
		if(!file_exists(Sistema::$caminhoDiretorio.Sistema::$caminhoDataGalerias.$t->getAlturaPequena()->formatar('', '', 0)))
			mkdir(Sistema::$caminhoDiretorio.Sistema::$caminhoDataGalerias.$t->getLarguraPequena()->formatar('', '', 0));
			
		$class 	= __CLASS__;
		$l 		= new $class;
		$l->condicoes('', $id, self::ID);
		
		$t = $l->listar();
		
		parent::alterar($t);
		
	}
	
	public function alterar(GaleriaCategoria $t){
		
		parent::alterar($t);
		
		$where = "WHERE ".self::ID." = '".$t->getId()."'";
		
		$this->con->alterar(Sistema::$BDPrefixo.$this->tabela, self::TITULO, 	$t->titulo, $where);
		$this->con->alterar(Sistema::$BDPrefixo.$this->tabela, self::PROTEGIDO,	($t->protegido ? self::VALOR_PROTEGIDO_TRUE : self::VALOR_PROTEGIDO_FALSE), $where);
		
		$this->con->alterar(Sistema::$BDPrefixo.$this->tabela, self::ALTURA, 			$t->getAltura()->formatar(), $where);
		$this->con->alterar(Sistema::$BDPrefixo.$this->tabela, self::LARGURA, 			$t->getLargura()->formatar(), $where);
		$this->con->alterar(Sistema::$BDPrefixo.$this->tabela, self::ALTURAMEDIA, 		$t->getAlturaMedia()->formatar(), $where);
		$this->con->alterar(Sistema::$BDPrefixo.$this->tabela, self::LARGURAMEDIA, 		$t->getLarguraMedia()->formatar(), $where);
		$this->con->alterar(Sistema::$BDPrefixo.$this->tabela, self::ALTURAPEQUENA, 	$t->getAlturaPequena()->formatar(), $where);
		$this->con->alterar(Sistema::$BDPrefixo.$this->tabela, self::LARGURAPEQUENA,	$t->getLarguraPequena()->formatar(), $where);
		$this->con->alterar(Sistema::$BDPrefixo.$this->tabela, parent::URL,				$t->getURL()->getId(), $where);
		$this->con->alterar(Sistema::$BDPrefixo.$this->tabela, parent::TEXTO,			$t->getTexto()->getId(), $where);
		
		if(!file_exists(Sistema::$caminhoDiretorio.Sistema::$caminhoDataGalerias.$t->getLargura()->formatar('', '', 0)))
			mkdir(Sistema::$caminhoDiretorio.Sistema::$caminhoDataGalerias.$t->getLargura()->formatar('', '', 0));
		if(!file_exists(Sistema::$caminhoDiretorio.Sistema::$caminhoDataGalerias.$t->getLarguraMedia()->formatar('', '', 0)))
			mkdir(Sistema::$caminhoDiretorio.Sistema::$caminhoDataGalerias.$t->getLarguraMedia()->formatar('', '', 0));
		if(!file_exists(Sistema::$caminhoDiretorio.Sistema::$caminhoDataGalerias.$t->getLarguraPequena()->formatar('', '', 0)))
			mkdir(Sistema::$caminhoDiretorio.Sistema::$caminhoDataGalerias.$t->getLarguraPequena()->formatar('', '', 0));
		
	}
	
	public function deletar(GaleriaCategoria $t){
		
		if($t->getGalerias()->getTotal() > 0)
			throw new Exception("Está categoria possui galerias cadastradas, não foi possível removê-la!");
		else{
		
			parent::deletar($t);
		
			$where = "WHERE ".self::ID." = '".$t->getId()."'";
			
			$this->con->deletar(Sistema::$BDPrefixo.$this->tabela, $where);

		}
		
	}
	
}

?>