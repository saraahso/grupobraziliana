<?php

importar("Geral.Objeto");
importar("Geral.Texto");
importar("Geral.Lista");
importar("Utils.Dados.DataHora");
importar("Utils.EnvioEmail");

class PacoteMailing extends Objeto {
	
	public 	$titulo;
	private	$con;
	
	public function __construct($id = ''){
		
		parent::__construct($id);
		
		$this->titulo	= '';
		
		$this->emails 	= new Lista('mailing_pacotes_emails');
		$this->emails->condicoes(array(1 => (array('campo' => 'pacote', 'valor' => $this->id))));
		
		$this->con = BDConexao::__Abrir();
		
	}
	
	
	public function getEmails(){
		return $this->emails;
	}
	
	public function addEmail($email, $nome = '', $cidade = '', $estado = '', $area = '', $dataNasc = ''){
		
		if(empty($dataNasc)) $dataNasc = new DataHora;
		
		$this->con->executar("SELECT * FROM ".Sistema::$BDPrefixo."mailing_pacotes_emails WHERE pacote = '".$this->getId()."' AND email = '".$email."'");
		
		if($this->con->getTotal() == 0)
			$this->con->executar("INSERT INTO ".Sistema::$BDPrefixo."mailing_pacotes_emails(pacote, email, nome, cidade, estado, area, datanasc) VALUES('".$this->getId()."','".$email."','".$nome."','".$cidade."','".$estado."','".$area."','".($dataNasc ? $dataNasc->mostrar("Y-m-d") : '')."')");
		else
			throw new Exception("E-mail já cadastrado");
		
	}
	
}

?>