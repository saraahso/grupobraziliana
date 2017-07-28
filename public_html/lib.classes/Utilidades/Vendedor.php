<?php

importar("Geral.Objeto");
importar("Geral.URL");
importar("Geral.Imagem");

class Vendedor extends Objeto {
	
	private	$imagem;
	
	public  $nome;
	public  $email;
	public  $msn;
	public  $skype;
	public  $voip;
	public  $telefone;
	public  $ramal;
	
	public function __construct($id = ''){
		
		parent::__construct($id);
		
		$this->nome			= '';
		$this->email		= '';
		$this->msn			= '';
		$this->skype		= '';
		$this->voip			= '';
		$this->telefone		= '';
		$this->ramal		= '';
		$this->ordem		= '';
		
		$this->imagem		= new Imagem;
		
	}
	
	public function setImagem(Imagem $img){
		$this->imagem = $img;	
	}
	
	public function getImagem(){
		return $this->imagem;	
	}
	
}

?>