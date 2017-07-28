<?php

importar("TemTudoAqui.Usuarios.Pessoa");

class PessoaJuridica extends Pessoa{
	
	public 		$razaoSocial;
	public 		$cnpj;
	
	public function __construct($id = ''){
		
		parent::__construct($id);
		
	}
	
}

?>