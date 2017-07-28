<?php

importar("Geral.Objeto");
importar("Utils.Arquivos");
importar("Utilidades.Discografia.Lista.ListaMusicaCategorias");

class Musica extends Objeto {
	
	private	$musica;
	private	$categorias;
	
	public 	$titulo;
	public	$ordem;
	
	public function __construct($id = ''){
		
		parent::__construct($id);
		
		$this->musica 	= Arquivos::__Create('');
		
		$this->ordem	= 0;
		
		$this->categorias	= new ListaMusicaCategorias;
		$this->categorias->condicoes('', '', '', '', "SELECT * FROM ".Sistema::$BDPrefixo."relacionamento_musicas_categorias rmc INNER JOIN ".Sistema::$BDPrefixo."musicas_categorias c ON c.id = rmc.categoria WHERE rmc.musica = '".$this->id."'");
		
	}
	
	public function setMusica(Arquivos $arq){
		$this->musica = $arq;	
	}
	
	public function getMusica(){
		return $this->musica;	
	}
	
	public function addCategoria(MusicaCategoria $uDC){
		
		if($uDC->getId() != '' && $this->getId() != ''){
		
			$con = BDConexao::__Abrir();
			$con->executar("INSERT INTO ".Sistema::$BDPrefixo."relacionamento_musicas_categorias(musica, categoria) VALUES('".$this->getId()."','".$uDC->getId()."')");		
		
		}
		
	}
	
	public function getCategorias(){
		
		return $this->categorias;
		
	}
	
}

?>