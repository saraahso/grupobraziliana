<?php

importar("Geral.Objeto");
importar("Geral.Texto");
importar("Utilidades.Publicidades.Mailing.PacoteMailing");
importar("Utilidades.Publicidades.Mailing.Lista.ListaMailings");
importar("Utils.Dados.DataHora");
importar("Utils.EnvioEmail");

class Mailing extends Objeto {
	
	const	PARADO = 1;
	const	ENVIANDO = 2;
	
	private	$status;
	private $pacote;
	private $texto;
	private	$data;
	
	public function __construct($id = ''){
		
		parent::__construct($id);
		
		$this->status	= self::PARADO;
		$this->texto	= new Texto;
		$this->pacote	= new PacoteMailing;
		$this->data		= new DataHora;
		
	}
	
	public function setStatus($s){
		$this->status = $s;
	}
	
	public function getStatus(){
		return $this->status;
	}
	
	public function setTexto(Texto $t){
		$this->texto = $t;
	}
	
	public function getTexto(){
		return $this->texto;
	}
	
	public function setPacote(PacoteMailing $pM){
		$this->pacote = $pM;
	}
	
	public function getPacote(){
		return $this->pacote;
	}
	
	public function setData(DataHora $d){
		$this->data = $d;
	}
	
	public function getData(){
		return $this->data;
	}
	
	public function enviarPacote($de = '', $html = false){
		
		Email::$msg = $this->getTexto()->texto;
		if($html) Email::$html = $html;
		if($de) Email::$de = $de;
		Email::$assunto = $this->getTexto()->titulo;
		
		if($this->getStatus() == self::PARADO){
			
			self::criarListaParaEnvio($this);
			
		}
		
		$l = new Lista('mailing_pacotes_envio');
		$l->condicoes('', $this->getId(), 'mailing');
		$total = $l->getTotal();
		
		if($total > Sistema::$emailsPorHora){
			$l->setParametros(Sistema::$emailsPorHora, 'limite');
			$this->setStatus(self::ENVIANDO);
		}else
			$this->setStatus(self::PARADO);
		
		$lM = new ListaMailings;
		$lM->alterar($this);
		
		$con = BDConexao::__Abrir();
		while($rs = $l->listar()){
			
			Email::$para = $rs['email'];
			Email::enviar();
			
			$con->executar("DELETE FROM ".Sistema::$BDPrefixo."mailing_pacotes_envio WHERE mailing = '".$this->getId()."' AND email = '".$rs['email']."'");
			$l->setParametros(0);
			$l->setParametros($l->getParametros('limite')-1, 'limite');
			
		}
		
		
	}
	
	private static function criarListaParaEnvio(Mailing $m){
		
		if($m->getId() != '' && $m->getPacote()->getId() != ''){
			
			$m->getPacote()->getEmails()->setParametros(0);
			
			$con = BDConexao::__Abrir();
			$con->executar("DELETE FROM ".Sistema::$BDPrefixo."mailing_pacotes_envio WHERE mailing = '".$m->getId()."'");
			while($rs = $m->getPacote()->getEmails()->listar()){
				
				$con->executar("INSERT INTO ".Sistema::$BDPrefixo."mailing_pacotes_envio(mailing, email) VALUES('".$m->getId()."','".$rs['email']."')");
				
			}
			
			$m->getPacote()->getEmails()->setParametros(0);
		
		}
		
	}
	
}

?>