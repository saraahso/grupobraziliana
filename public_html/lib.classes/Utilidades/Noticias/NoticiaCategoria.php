<?php

importar("Geral.Objeto");
importar("Geral.URL");
importar("Geral.Texto");
importar("Utils.Dados.Numero");
importar("Utilidades.Noticias.Lista.ListaNoticias");

class NoticiaCategoria extends Objeto {
	
	private	$noticias;
	private $texto;
	private	$url;
	
	public	$ordem;
	
	public function __construct($id = ''){
		
		parent::__construct($id);
		
		$this->ordem 		= 0;
		$this->url			= new URL;
		$this->texto 		= new Texto;
		
		$this->noticias	= new ListaNoticias;
		$this->noticias->condicoes('', '', '', '', "SELECT * FROM ".Sistema::$BDPrefixo."relacionamento_noticias_categorias rnc INNER JOIN ".Sistema::$BDPrefixo."noticias n ON n.id = rnc.noticia WHERE rnc.categoria = '".$this->id."'");
		
	}
	
	public function addNoticia(Noticia $n){
		
		if($n->getId() != '' && $this->getId() != ''){
		
			$con = BDConexao::__Abrir();
			$con->executar("INSERT INTO ".Sistema::$BDPrefixo."relacionamento_noticias_categorias(categoria, noticia) VALUES('".$this->getId()."','".$n->getId()."')");		
		
		}
		
	}
	
	public function getNoticias(){
		
		return $this->noticias;
		
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
	
}

?>