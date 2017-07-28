<?php 

importar("Geral.Objeto");
importar("Utils.Dados.DataHora");
importar("Utils.Imagens.Image");
importar("Utilidades.Discografia.Lista.ListaMusicas");

class MusicaCategoria extends Objeto {
	
	private	$musicas;
	private	$dataLancamento;
	private	$capa;
	
	public	$titulo;
	public	$subTitulo;
	public	$gravadora;
	public	$ordem;
	
	public function __construct($id = ''){
		
		parent::__construct($id);
		
		$this->titulo 			= '';
		$this->subTitulo		= '';
		$this->gravadora		= '';
		$this->ordem			= 0;
		$this->dataLancamento 	= new DataHora;
		$this->capa				= new Image;
		
		$this->musicas			= new ListaMusicas;
		$this->musicas->condicoes('', '', '', '', "SELECT * FROM ".Sistema::$BDPrefixo."relacionamento_musicas_categorias rmc INNER JOIN ".Sistema::$BDPrefixo."musicas m ON m.id = rmc.musica WHERE rmc.categoria = '".$this->id."'");
		
	}
	
	public function addMusica(Musica $uDA){
		
		if($uDA->getId() != '' && $this->getId() != ''){
		
			$con = BDConexao::__Abrir();
			$con->executar("INSERT INTO ".Sistema::$BDPrefixo."relacionamento_musicas_categorias(categoria, musica) VALUES('".$this->getId()."','".$uDA->getId()."')");		
		
		}
		
	}
	
	public function setDataLancamento(DataHora $d){
		$this->dataLancamento = $d;
	}
	
	public function getDataLancamento(){
		return $this->dataLancamento;
	}
	
	public function setCapa(Image $c){
		$this->capa = $c;
	}
	
	public function getCapa(){
		return $this->capa;	
	}
	
	public function getMusicas(){
		
		return $this->musicas;
		
	}
	
}

?>