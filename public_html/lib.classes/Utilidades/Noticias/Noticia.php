<?php

importar("Geral.Objeto");
importar("Geral.Texto");
importar("Utils.Dados.DataHora");
importar("Utilidades.Noticias.Lista.ListaNoticiaCategorias");

class Noticia extends Objeto {
	
	private $url;
	private $texto;
	private	$data;
	private	$categorias;
	
	public function __construct($id = ''){
		
		parent::__construct($id);
		
		$this->url 		= new URL;
		$this->texto 	= new Texto;
		$this->data 	= new DataHora;
		
		$this->categorias	= new ListaNoticiaCategorias;
		$this->categorias->condicoes('', '', '', '', "SELECT * FROM ".Sistema::$BDPrefixo."relacionamento_noticias_categorias rnc INNER JOIN ".Sistema::$BDPrefixo."noticias_categorias n ON n.id = rnc.categoria WHERE rnc.noticia = '".$this->id."'");
		
	}
	
	public function setData(DataHora $data){
		$this->data = $data;	
	}
	
	public function getData(){
		return $this->data;	
	}
	
	public function setURL(URL $url){
		$this->url = $url;	
	}
	
	public function getURL(){
		return $this->url;	
	}
	
	public function setTexto(Texto $texto){
		$this->texto = $texto;	
	}
	
	public function getTexto(){
		return $this->texto;	
	}
	
	public function addCategoria(NoticiaCategoria $nC){
		
		if($nC->getId() != '' && $this->getId() != ''){
		
			$con = BDConexao::__Abrir();
			$con->executar("INSERT INTO ".Sistema::$BDPrefixo."relacionamento_noticias_categorias(noticia, categoria) VALUES('".$this->getId()."','".$nC->getId()."')");		
		
		}
		
	}
	
	public function getCategorias(){
		return $this->categorias;
	}
	
}

?>