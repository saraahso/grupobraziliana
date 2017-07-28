<?php

importar("TemTudoAqui.Usuarios.Lista.ListaEnderecos");
importar("TemTudoAqui.Usuarios.Lista.ListaTelefones");
importar("TemTudoAqui.Usuarios.Lista.ListaEmails");
importar("Utils.Dados.DataHora");

class Pessoa{
	
	protected 	$id;
	protected 	$dataCadastro;
	protected 	$endereco;
	protected 	$telefone;
	protected 	$email;
	protected	$foto;
	protected	$vendedor;
	
	public 		$nome;
	public 		$emailPrimario;
	public 		$usuario;
	public 		$senha;
	public		$emailSecundario;
	public 		$site;
	public		$atacadista;
	public		$origemCadastro;
	
	public function __construct($id = ''){
		
		$this->id = $id;
		$this->endereco = new ListaEnderecos;
		$this->endereco->condicoes('', $id, ListaEnderecos::IDSESSAO);
		$this->telefone = new ListaTelefones;
		$this->telefone->condicoes('', $id, ListaTelefones::IDSESSAO);
		$this->email 	= new ListaEmails;
		$this->email->condicoes('', $id, ListaEmails::PESSOA);
		
		$this->dataCadastro = new DataHora();
		$this->atacadista	= false;
		
		$this->foto		= new Image;
		
		$this->origemCadastro = '';
		
		$this->vendedor = 0;
		
	}
	
	public function getId(){
		
		return $this->id;
		
	}
	
	public function addEndereco(Endereco $vEndereco){
		if($this->getId() != '')
			$this->endereco->inserir($vEndereco, $this);
	}
	
	public function getEndereco(){
		return $this->endereco;
	}
	
	public function addTelefone(Telefone $vTelefone){
		if($this->getId() != '')
			$this->telefone->inserir($vTelefone, $this);
	}
	
	public function getTelefone(){
		return $this->telefone;
	}
	
	public function addEmail(Email $vEmail){
		if($this->getId() != '')
			$this->email->inserir($vEmail, $this);
	}
	
	public function getEmail(){
		return $this->email;
	}
	
	public function setDataCadastro(DataHora $vDC){
		
		$this->dataCadastro = vDC;
		
	}
	
	public function getDataCadastro(){
		
		return $this->dataCadastro;
		
	}
	
	public function setFoto(Image $vFoto){
		$this->foto = $vFoto;	
	}
	
	public function getFoto(){
		return $this->foto;
	}

	public function setVendedor($id){
		$this->vendedor = (int)$id;	
	}

	public function getVendedor(){
		return $this->vendedor;	
	}
	
	public function __call($m, $a){
		return false;	
	}	
	
}

?>