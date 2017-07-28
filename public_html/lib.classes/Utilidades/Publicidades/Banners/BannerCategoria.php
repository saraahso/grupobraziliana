<?php

importar("Geral.Objeto");
importar("Utilidades.Publicidades.Banners.Lista.ListaBanners");
importar("Utils.Dados.Numero");

class BannerCategoria extends Objeto {
	
	private	$banners;
	private	$largura;
	private	$altura;
	
	public	$titulo;
	
	public function __construct($id = ''){
		
		parent::__construct($id);
		
		$this->titulo 	= '';
		$this->largura 	= new Numero;
		$this->altura 	= new Numero;
		
		$this->banners 	= new ListaBanners;
		$this->banners->condicoes('', '', '', '', "SELECT * FROM ".Sistema::$BDPrefixo."relacionamento_banners_categorias rbc INNER JOIN ".Sistema::$BDPrefixo."banners b ON b.id = rbc.banner WHERE rbc.categoria = '".$this->id."'");
		
	}
	
	public function getBanners(){
		
		return $this->banners;
		
	}
	
	public function setLargura($n){
		$this->largura->num = $n;	
	}
	
	public function getLargura(){
		return $this->largura;	
	}
	
	public function setAltura($n){
		$this->altura->num = $n;	
	}
	
	public function getAltura(){
		return $this->altura;	
	}
	
}

?>