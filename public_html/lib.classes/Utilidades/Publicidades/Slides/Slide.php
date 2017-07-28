<?php

importar("Geral.Objeto");
importar("Utils.Imagens.Image");
importar("Utilidades.Publicidades.Slides.Lista.ListaSlideCategorias");

class Slide extends Objeto {
	
	private	$categorias;
	private $imagem;
	private $flash;
	
	public	$titulo;
	public	$legenda;
	public	$enderecoURL;
	public	$ativo;
	public	$tipo;
	public	$segundos;
	public	$corfundo;
	public 	$ordem;
	
	public function __construct($id = ''){
		
		parent::__construct($id);
		
		$this->titulo		= '';
		$this->legenda		= '';
		$this->enderecoURL	= '';
		$this->ativo		= false;
		$this->tipo			= '';
		$this->segundos		= 0;
		$this->corfundo		= 'FFFFFF';
		$this->ordem 		= 0;
		
		
		$this->categorias	= new ListaSlideCategorias;
		$this->categorias->condicoes('', '', '', '', "SELECT * FROM ".Sistema::$BDPrefixo."relacionamento_slides_categorias rsc INNER JOIN ".Sistema::$BDPrefixo."slides_categorias c ON c.id = rsc.categoria WHERE rsc.slide = '".$this->id."'");
		
	}
	
	public function addCategoria(SlideCategoria $sC){
		
		if($sC->getId() != '' && $this->getId() != ''){
		
			$con = BDConexao::__Abrir();
			$con->executar("INSERT INTO ".Sistema::$BDPrefixo."relacionamento_slides_categorias(slide, categoria) VALUES('".$this->getId()."','".$sC->getId()."')");		
		
		}
		
	}
	
	public function getCategorias(){
		
		return $this->categorias;
		
	}
	
	public function setImagem(Image $img){
		$this->imagem = $img;	
	}
	
	public function getImagem(){
		return $this->imagem;	
	}
	
	public function setFlash($f){
		$this->flash = $f;	
	}
	
	public function getFlash(){
		return $this->flash;	
	}
	
}

?>