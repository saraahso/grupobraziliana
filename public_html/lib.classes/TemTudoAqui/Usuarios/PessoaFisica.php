<?php

importar("TemTudoAqui.Usuarios.Pessoa");
importar("Utils.Dados.DataHora");

class PessoaFisica extends Pessoa{
	
	private		$dataNasc;
	
	public		$sobreNome;
	public 		$rg;
	public 		$cpf;
	public		$sexo;
	
	public function __construct($id = ''){
		
		parent::__construct($id);
		
		$this->dataNasc = new DataHora;
		
		$this->sobreNome = '';
		
	}
	
	public function setDataNasc(DataHora $vDataNasc){
		$this->dataNasc = $vDataNasc;
	}
	
	public function getDataNasc(){
		return $this->dataNasc;
	}
	
	
}

?>