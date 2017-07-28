<?php

importar("Utilidades.Galerias.Lista.ListaGaleriaCategorias");
importar("Geral.Objeto");
importar("Geral.URL");
importar("Geral.Lista.ListaImagens");
importar("Utils.Dados.DataHora");

class Galeria extends Objeto {
	
	private	$categorias;
	private	$data;
	private	$video;
	private	$url;
	private	$imagens;
	
	public	$titulo;
	public	$descricao;
	public	$local;
	public	$tipo;
	
	public function __construct($id = ''){
		
		parent::__construct($id);
		
		$this->data 		= new DataHora;
		$this->url			= new URL;
		$this->video 		= '';
		$this->titulo		= '';
		$this->descricao	= '';
		$this->local		= '';
		$this->tipo			= '';
		
		$this->imagens		= new ListaImagens;
		$this->imagens->caminhoEscrita = Sistema::$caminhoDiretorio.Sistema::$caminhoDataGalerias;
		$this->imagens->caminhoLeitura = Sistema::$caminhoURL.Sistema::$caminhoDataGalerias;
		
		$a[1] = array('campo' => ListaImagens::IDSESSAO, 	'valor' => $this->id);
		$a[2] = array('campo' => ListaImagens::SESSAO, 		'valor' => 'galerias');
		
		$this->imagens->condicoes($a);
		
		$this->categorias	= new ListaGaleriaCategorias;
		$this->categorias->condicoes('', '', '', '', "SELECT * FROM ".Sistema::$BDPrefixo."relacionamento_galerias_categorias rgc INNER JOIN ".Sistema::$BDPrefixo."galerias_categorias c ON c.id = rgc.categoria WHERE rgc.galeria = '".$this->id."'");
		
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
	
	public function setVideo($video){
		$this->video = $video;	
	}
	
	public function getVideo(){
		
		if(!empty($this->video)){
			
			$p = explode("&", $this->video);
			if(!eregi('http://www.youtube.com/v/', $p[0]))
				return "http://www.youtube.com/v/".str_replace('http://www.youtube.com/watch?v=', '', $p[0])."&hl=en_US&fs=1&";	
			else
				return $p[0];
			
		}
	}
	
	public function getImagens(){
		return $this->imagens;	
	}
	
	public function addCategoria(GaleriaCategoria $gC){
		
		if($gC->getId() != '' && $this->getId() != ''){
		
			$con = BDConexao::__Abrir();
			$con->executar("INSERT INTO ".Sistema::$BDPrefixo."relacionamento_galerias_categorias(galeria, categoria) VALUES('".$this->getId()."','".$gC->getId()."')");		
		
		}
		
	}
	
	public function getCategorias(){
		
		return $this->categorias;
		
	}
	
}

?>