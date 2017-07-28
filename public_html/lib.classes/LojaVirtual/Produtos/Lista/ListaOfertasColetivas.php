<?php

importar("Geral.Lista");
importar("LojaVirtual.Produtos.OfertaColetiva");
importar("LojaVirtual.Produtos.Lista.ListaEmpresasOfertaColetiva");

class ListaOfertasColetivas extends Lista {
	
	const ID						= 'id';
	const EMPRESA					= 'empresa';
	const TITULO					= 'titulo';
	const SUBTITULO					= 'subtitulo';
	const VALORORIGINAL				= 'valororiginal';
	const DESCONTO					= 'desconto';
	const ECONOMIA					= 'economia';
	const VALOR						= 'valor';
	const QUANTIDADE				= 'quantidade';
	const COMPRASMINIMA				= 'comprasminima';
	const COMPRASMAXIMA				= 'comprasmaxima';
	const COMPRASEFETUADAS			= 'comprasefetuadas';
	const DESTAQUES					= 'destaques';
	const REGULAMENTO				= 'regulamento';
	const DATAINICIO				= 'datainicio';
	const DATAFIM					= 'datafim';
	
	public function __construct(){
		
		parent::__construct('ofertascoletivas');
		
		$this->ID = self::ID;
		
	}
	
	public function listar($ordem = "ASC", $campo = self::ID){
		
		$info = parent::listar($ordem, $campo);
		
		if(!empty($info)){ 
		
			$temp = new OfertaColetiva($info[self::ID]);
			
			parent::resgatarObjetos($info);
			
			$temp->titulo 			= $info[self::TITULO];
			$temp->subTitulo		= $info[self::SUBTITULO];
			$temp->valorOriginal	= $info[self::VALORORIGINAL];
			$temp->desconto			= $info[self::DESCONTO];
			$temp->economia			= $info[self::ECONOMIA];
			$temp->valor			= $info[self::VALOR];
			$temp->setURL($info[parent::URL]);
			$temp->destaques 		= $info[self::DESTAQUES];
			$temp->regulamento		= $info[self::REGULAMENTO];
			$temp->quantidade		= $info[self::QUANTIDADE];
			$temp->comprasMinima	= $info[self::COMPRASMINIMA];
			$temp->comprasMaxima	= $info[self::COMPRASMAXIMA];
			$temp->comprasEfetuadas	= $info[self::COMPRASEFETUADAS];
			
			$temp->setDataInicio(new DataHora($info[self::DATAINICIO]));
			$temp->setDataFim(new DataHora($info[self::DATAFIM]));
			
			$lEOC = new ListaEmpresasOfertaColetiva;
			$lEOC->condicoes('', $info[self::EMPRESA], ListaEmpresasOfertaColetiva::ID);
			if($lEOC->getTotal() > 0)	
				$temp->setEmpresa($lEOC->listar());
			
			return $temp;
		
		}
		
	}
	
	public function inserir(OfertaColetiva &$p){
		
		parent::inserir($p);
		
		$this->con->executar("INSERT INTO ".Sistema::$BDPrefixo.$this->tabela."(".parent::URL.", ".self::EMPRESA.", ".self::TITULO.", ".self::SUBTITULO.", ".self::DESTAQUES.", ".self::REGULAMENTO.", ".self::VALORORIGINAL.", ".self::DESCONTO.", ".self::ECONOMIA.", ".self::VALOR.", ".self::DATAINICIO.", ".self::DATAFIM.", ".self::QUANTIDADE.", ".self::COMPRASMINIMA.", ".self::COMPRASMAXIMA.") VALUES('".$p->getURL()->getId()."','".$p->getEmpresa()->getId()."','".$p->titulo."','".$p->subTitulo."','".$p->destaques."','".$p->regulamento."','".$p->valorOriginal->formatar()."','".$p->desconto->formatar()."','".$p->economia->formatar()."','".$p->valor->formatar()."','".$p->getDataInicio()->mostrar("YmdHi")."','".$p->getDataFim()->mostrar("YmdHi")."','".$p->quantidade."','".$p->comprasMinima."','".$p->comprasMaxima."')");
		
		$id = $this->con->getId();
		
		$class 	= __CLASS__;
		$l 		= new $class;
		$l->condicoes('', $id, self::ID);
		
		$p = $l->listar();
		
		parent::alterar($p);
		
	}
	
	public function alterar(OfertaColetiva $p){
		
		parent::alterar($p);
		
		$where = "WHERE ".self::ID." = '".$p->getId()."'";
		
		$this->con->alterar(Sistema::$BDPrefixo.$this->tabela, self::EMPRESA, 			$p->getEmpresa()->getId(), $where);
		$this->con->alterar(Sistema::$BDPrefixo.$this->tabela, self::TITULO, 			$p->titulo, $where);
		$this->con->alterar(Sistema::$BDPrefixo.$this->tabela, self::SUBTITULO, 		$p->subTitulo, $where);
		$this->con->alterar(Sistema::$BDPrefixo.$this->tabela, self::DESTAQUES, 		$p->destaques, $where);
		$this->con->alterar(Sistema::$BDPrefixo.$this->tabela, self::REGULAMENTO, 		$p->regulamento, $where);
		$this->con->alterar(Sistema::$BDPrefixo.$this->tabela, self::VALORORIGINAL,		$p->valorOriginal->formatar(), $where);
		$this->con->alterar(Sistema::$BDPrefixo.$this->tabela, self::DESCONTO,			$p->desconto->formatar(), $where);
		$this->con->alterar(Sistema::$BDPrefixo.$this->tabela, self::ECONOMIA, 			$p->economia->formatar(), $where);
		$this->con->alterar(Sistema::$BDPrefixo.$this->tabela, self::VALOR, 			$p->valor->formatar(), $where);
		$this->con->alterar(Sistema::$BDPrefixo.$this->tabela, self::QUANTIDADE,		$p->quantidade, $where);
		$this->con->alterar(Sistema::$BDPrefixo.$this->tabela, self::COMPRASMINIMA,		$p->comprasMinima, $where);
		$this->con->alterar(Sistema::$BDPrefixo.$this->tabela, self::COMPRASMAXIMA,		$p->comprasMaxima, $where);
		$this->con->alterar(Sistema::$BDPrefixo.$this->tabela, self::COMPRASEFETUADAS,	$p->comprasEfetuadas, $where);
		$this->con->alterar(Sistema::$BDPrefixo.$this->tabela, self::DATAINICIO,		$p->getDataInicio()->mostrar("YmdHi"), $where);
		$this->con->alterar(Sistema::$BDPrefixo.$this->tabela, self::DATAFIM,			$p->getDataFim()->mostrar("YmdHi"), $where);
		
	}
	
	public function deletar(OfertaColetiva $p){
		
		parent::deletar($p);
		
		$where = "WHERE ".self::ID." = '".$p->getId()."'";
		
		while($img = $p->getImagens()->listar("ASC")){
			$p->getImagens()->deletar($img);
			$p->getImagens()->setParametros(0);
		}
		
		$this->con->deletar(Sistema::$BDPrefixo."relacionamento_ofertascoletivas_categorias", "WHERE ofertacoletiva = '".$p->getId()."'");			
		$this->con->deletar(Sistema::$BDPrefixo.$this->tabela, $where);
		
	}
	
}

?>