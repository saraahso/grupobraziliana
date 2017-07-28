<?php

importar("Geral.Objeto");
importar("Utils.Arquivos");
importar("Utils.Imagens.Image");
importar("Utilidades.UploadsDownloads.Lista.ListaUploadDownloadCategorias");

class UploadDownloadArquivo extends Objeto {
	
	private $arquivo;
	private	$categorias;
	
	public	$ordem;
	public	$produtos;
	
	public function __construct($id = ''){
		
		parent::__construct($id);
		
		$this->arquivo 	= Arquivos::__Create('');
		
		$this->ordem	= 0;
		
		$this->categorias	= new ListaUploadDownloadCategorias;
		$this->categorias->condicoes('', '', '', '', "SELECT * FROM ".Sistema::$BDPrefixo."relacionamento_arquivos_categorias rac INNER JOIN ".Sistema::$BDPrefixo."arquivos_categorias c ON c.id = rac.categoria WHERE rac.arquivo = '".$this->id."'");
		
	}
	
	public function setArquivo(Arquivos $arq){
		$this->arquivo = $arq;	
	}
	
	public function getArquivo(){
		return $this->arquivo;	
	}
	
	public function addCategoria(UploadDownloadCategoria $uDC){
		
		if($uDC->getId() != '' && $this->getId() != ''){
		
			$con = BDConexao::__Abrir();
			$con->executar("INSERT INTO ".Sistema::$BDPrefixo."relacionamento_arquivos_categorias(arquivo, categoria) VALUES('".$this->getId()."','".$uDC->getId()."')");		
		
		}
		
	}
	
	public function getCategorias(){
		
		return $this->categorias;
		
	}
	
	public function getImagem(){
		
		$ca = Sistema::$caminhoDiretorio."lib.img/icones/".$this->arquivo->extensao.".png";
		
		if(file_exists($ca))
			$arq = new Image(new Arquivos($ca));
		else
			$arq = new Image(new Arquivos(Sistema::$caminhoURL."lib.img/icones/generic.png"));
		
		return $arq;
		
	}
	
}

?>