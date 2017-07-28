<?php

importar("Geral.Objeto");
importar("Geral.URL");
importar("Geral.Texto");
importar("Utils.Dados.Numero");
importar("Utilidades.Galerias.Lista.ListaGalerias");

class GaleriaCategoria extends Objeto {
	
	private	$galerias;
	private $texto;
	private	$altura;
	private	$largura;
	private	$alturam;
	private	$larguram;
	private	$alturap;
	private	$largurap;
	private	$url;
	
	public	$titulo;
	public	$protegido;
	
	public function __construct($id = ''){
		
		parent::__construct($id);
		
		$this->titulo 		= '';
		$this->url			= new URL;
		$this->texto 		= new Texto;
		
		$this->altura		= new Numero;
		$this->largura		= new Numero;
		$this->alturam		= new Numero;
		$this->larguram		= new Numero;
		$this->alturap		= new Numero;
		$this->largurap		= new Numero;
		
		$this->galerias	= new ListaGalerias;
		$this->galerias->condicoes('', '', '', '', "SELECT * FROM ".Sistema::$BDPrefixo."relacionamento_galerias_categorias rgc INNER JOIN ".Sistema::$BDPrefixo."galerias g ON g.id = rgc.galeria WHERE rgc.categoria = '".$this->id."'");
		
	}
	
	public function addGaleria(Galeria $g){
		
		if($g->getId() != '' && $this->getId() != ''){
		
			$con = BDConexao::__Abrir();
			$con->executar("INSERT INTO ".Sistema::$BDPrefixo."relacionamento_galerias_categorias(categoria, galeria) VALUES('".$this->getId()."','".$g->getId()."')");		
		
		}
		
	}
	
	public function getGalerias(){
		
		return $this->galerias;
		
	}
	
	public function setAltura($v){
		
		$this->altura->num = $v;
		
	}
	
	public function getAltura(){
		
		return $this->altura;
		
	}
	
	public function setLargura($v){
		
		$this->largura->num = $v;
		
	}
	
	public function getLargura(){
		
		return $this->largura;
		
	}
	
	public function setAlturaMedia($v){
		
		$this->alturam->num = $v;
		
	}
	
	public function getAlturaMedia(){
		
		return $this->alturam;
		
	}
	
	public function setLarguraMedia($v){
		
		$this->larguram->num = $v;
		
	}
	
	public function getLarguraMedia(){
		
		return $this->larguram;
		
	}
	
	public function setAlturaPequena($v){
		
		$this->alturap->num = $v;
		
	}
	
	public function getAlturaPequena(){
		
		return $this->alturap;
		
	}
	
	public function setLarguraPequena($v){
		
		$this->largurap->num = $v;
		
	}
	
	public function getLarguraPequena(){
		
		return $this->largurap;
		
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