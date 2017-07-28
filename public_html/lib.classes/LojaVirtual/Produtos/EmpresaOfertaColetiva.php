<?php

importar("TemTudoAqui.Usuarios.PessoaJuridica");
importar("Geral.URL");
importar("Geral.Texto");

class EmpresaOfertaColetiva extends PessoaJuridica {
	
	private $url;
	private $texto;
	
	public function __construct($id = ''){
		
		$this->id = $id;
		$this->endereco = new ListaEnderecos('ofertascoletivas_empresas_enderecos');
		$this->endereco->condicoes('', $id, ListaEnderecos::IDSESSAO);
		$this->telefone = new ListaTelefones('ofertascoletivas_empresas_telefones');
		$this->telefone->condicoes('', $id, ListaTelefones::IDSESSAO);
		$this->email 	= new ListaEmails('ofertascoletivas_empresas_emails');
		$this->email->condicoes('', $id, ListaEmails::PESSOA);
		
		$this->dataCadastro = new DataHora();
		
		$this->foto		= new Image;
		
		$this->url 		= new URL;
		$this->texto 	= new Texto;
		
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