<?php 

importar("Geral.Objeto");
importar("Utilidades.UploadsDownloads.Lista.ListaUploadDownloadArquivos");

class UploadDownloadCategoria extends Objeto {
	
	private	$arquivos;
	
	public	$titulo;
	public	$ordem;
	
	public function __construct($id = ''){
		
		parent::__construct($id);
		
		$this->titulo 	= '';
		$this->ordem	= 0;
		
		$this->arquivos	= new ListaUploadDownloadArquivos;
		$this->arquivos->condicoes('', '', '', '', "SELECT * FROM ".Sistema::$BDPrefixo."relacionamento_arquivos_categorias rac INNER JOIN ".Sistema::$BDPrefixo."arquivos a ON a.id = rac.arquivo WHERE rac.categoria = '".$this->id."'");
		
	}
	
	public function addArquivo(UploadDownloadArquivo $uDA){
		
		if($uDA->getId() != '' && $this->getId() != ''){
		
			$con = BDConexao::__Abrir();
			$con->executar("INSERT INTO ".Sistema::$BDPrefixo."relacionamento_arquivos_categorias(categoria, arquivo) VALUES('".$this->getId()."','".$uDA->getId()."')");		
		
		}
		
	}
	
	public function getArquivos(){
		
		return $this->arquivos;
		
	}
	
}

?>