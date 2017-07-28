<?php

importar("Geral.Objeto");
importar("Utils.Imagens.Image");
importar("Utils.Dados.DataHora");
importar("Utils.Dados.Numero");
importar("Utilidades.Publicidades.Banners.Lista.ListaBannerCategorias");

class Banner extends Objeto {
	
	private $categorias;
	private $imagem;
	private $flash;
	private $dataInicio;
	private $dataFim;
	private $largura;
	private $altura;
	
	public	$titulo;
	public	$clicks;
	public	$enderecoURL;
	public	$ativo;
	public	$tipo;
	
	public function __construct($id = ''){
		
		parent::__construct($id);
		
		$this->titulo		= '';
		$this->imagem 		= new Image;
		$this->flash 		= '';
		$this->dataInicio	= new DataHora;
		$this->dataFim		= new DataHora;
		$this->clicks		= 0;
		$this->enderecoURL	= '';
		$this->ativo		= false;
		$this->tipo			= '';
		$this->largura		= new Numero;
		$this->altura		= new Numero;
		
		$this->categorias	= new ListaBannerCategorias;
		$this->categorias->condicoes('', '', '', '', "SELECT * FROM ".Sistema::$BDPrefixo."relacionamento_banners_categorias rbc INNER JOIN ".Sistema::$BDPrefixo."banners_categorias c ON c.id = rbc.categoria WHERE rbc.banner = '".$this->id."'");
		
	}
	
	public function addCategoria(BannerCategoria $bC){
		
		if($bC->getId() != '' && $this->getId() != ''){
		
			$con = BDConexao::__Abrir();
			$con->executar("INSERT INTO ".Sistema::$BDPrefixo."relacionamento_banners_categorias(banner, categoria) VALUES('".$this->getId()."','".$bC->getId()."')");		
		
		}
		
	}
	
	public function getCategorias(){
		
		return $this->categorias;
		
	}
	
	public function setDataInicio(DataHora $d){
		$this->dataInicio = $d;	
	}
	
	public function getDataInicio(){
		return $this->dataInicio;
	}
	
	public function setDataFim(DataHora $d){
		$this->dataFim = $d;	
	}
	
	public function getDataFim(){
		return $this->dataFim;
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