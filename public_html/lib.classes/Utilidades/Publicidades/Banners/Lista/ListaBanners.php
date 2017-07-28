<?php

importar("Geral.Lista");
importar("Utilidades.Publicidades.Banners.Banner");

class ListaBanners extends Lista {
	
	const ID				= 'id';
	const TITULO			= 'titulo';
	const IMAGEM			= 'imagem';
	const FLASH				= 'flash';
	const DATAINICIO		= 'datainicio';
	const DATAFIM			= 'datafim';
	const CLICKS			= 'clicks';
	const ENDERECOURL		= 'enderecourl';
	const ATIVO				= 'ativo';
	const TIPO				= 'tipo';
	const LARGURA			= 'largura';
	const ALTURA			= 'altura';
	
	const VALOR_ATIVO_TRUE	= 1;
	const VALOR_ATIVO_FALSE	= 0;
	
	public function __construct(){
		
		parent::__construct('banners');
		
	}
	
	public function listar($ordem = "ASC", $campo = self::ID){
		
		$info = parent::listar($ordem, $campo);
		
		if(!empty($info)){ 
		
			$temp 			= new Banner($info[self::ID]);
			
			parent::resgatarObjetos($info);
			
			$temp->titulo		= $info[self::TITULO];
			$temp->clicks		= $info[self::CLICKS];
			$temp->enderecoURL	= $info[self::ENDERECOURL];
			$temp->ativo		= $info[self::ATIVO] == self::VALOR_ATIVO_TRUE ? true : false ;
			$temp->tipo			= $info[self::TIPO];
			
			if(!empty($info[self::IMAGEM]))
				$temp->setImagem(new Image(new Arquivos(Sistema::$caminhoURL.Sistema::$caminhoDataBanners.$info[self::IMAGEM])));
			
			$temp->setFlash($info[self::FLASH]);
			
			$temp->setDataInicio(new DataHora($info[self::DATAINICIO]));
			$temp->setDataFim(new DataHora($info[self::DATAFIM]));
			
			$temp->setLargura($info[self::LARGURA]);
			$temp->setAltura($info[self::ALTURA]);
		
			return $temp;
		
		}
		
	}
	
	public function inserir(Banner &$t){
		
		parent::inserir($t);
		
		if($t->getImagem()->nome != ''){
			$t->getImagem()->open();
			$imagem = $t->getImagem()->saveImage(Sistema::$caminhoDiretorio.Sistema::$caminhoDataBanners);
		}
		
		$ativo = $t->ativo ? self::VALOR_ATIVO_TRUE : self::VALOR_ATIVO_FALSE;
		
		$this->con->executar("INSERT INTO ".Sistema::$BDPrefixo.$this->tabela."(".self::TITULO.", ".self::IMAGEM.", ".self::FLASH.", ".self::DATAINICIO.", ".self::DATAFIM.", ".self::CLICKS.", ".self::ENDERECOURL.", ".self::ATIVO.", ".self::TIPO.", ".self::LARGURA.", ".self::ALTURA.") VALUES('".$t->titulo."','".$imagem."','".$t->getFlash()."','".$t->getDataInicio()->mostrar("YmdHis")."','".$t->getDataFim()->mostrar("YmdHis")."','".$t->clicks."','".$t->enderecoURL."','".$ativo."','".$t->tipo."','".$t->getLargura()->formatar()."','".$t->getAltura()->formatar()."')");
		
		$id = $this->con->getId();
		
		$class 	= __CLASS__;
		$l 		= new $class;
		$l->condicoes('', $id, self::ID);
		
		$t = $l->listar();
		
		parent::alterar($t);
		
	}
	
	public function alterar(Banner $t){
		
		parent::alterar($t);
		
		$where = "WHERE ".self::ID." = '".$t->getId()."'";
		
		if($t->getImagem()->nome != ''){
			$t->getImagem()->open();
			$imagem = $t->getImagem()->saveImage(Sistema::$caminhoDiretorio.Sistema::$caminhoDataBanners);
		}
		
		$ativo = $t->ativo ? self::VALOR_ATIVO_TRUE : self::VALOR_ATIVO_FALSE;
		
		$this->con->alterar(Sistema::$BDPrefixo.$this->tabela, self::TITULO, 		$t->titulo, $where);
		$this->con->alterar(Sistema::$BDPrefixo.$this->tabela, self::IMAGEM, 		$imagem, $where);
		$this->con->alterar(Sistema::$BDPrefixo.$this->tabela, self::FLASH, 		$t->getFlash(), $where);
		$this->con->alterar(Sistema::$BDPrefixo.$this->tabela, self::DATAINICIO, 	$t->getDataInicio()->mostrar("YmdHis"), $where);
		$this->con->alterar(Sistema::$BDPrefixo.$this->tabela, self::DATAFIM, 		$t->getDataFim()->mostrar("YmdHis"), $where);
		$this->con->alterar(Sistema::$BDPrefixo.$this->tabela, self::CLICKS, 		$t->clicks, $where);
		$this->con->alterar(Sistema::$BDPrefixo.$this->tabela, self::ENDERECOURL, 	$t->enderecoURL, $where);
		$this->con->alterar(Sistema::$BDPrefixo.$this->tabela, self::ATIVO, 		$ativo, $where);
		$this->con->alterar(Sistema::$BDPrefixo.$this->tabela, self::TIPO, 			$t->tipo, $where);
		$this->con->alterar(Sistema::$BDPrefixo.$this->tabela, self::LARGURA, 		$t->getLargura()->formatar(), $where);
		$this->con->alterar(Sistema::$BDPrefixo.$this->tabela, self::ALTURA, 		$t->getAltura()->formatar(), $where);
		
	}
	
	public function deletar(Banner $t){
		
		parent::deletar($t);
		
		$where = "WHERE ".self::ID." = '".$t->getId()."'";
		
		Arquivos::__DeleteArquivo(Sistema::$caminhoDiretorio.Sistema::$caminhoDataBanners.$t->getImagem()->nome.".".$t->getImagem()->extensao);
		Arquivos::__DeleteArquivo(Sistema::$caminhoDiretorio.Sistema::$caminhoDataBanners.$t->getFlash());
			
		$this->con->deletar(Sistema::$BDPrefixo.$this->tabela, $where);
		
	}
	
}

?>