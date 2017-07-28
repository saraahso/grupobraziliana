<?php

importar("Geral.Objeto");
importar("TemTudoAqui.Usuarios.Pessoa");
importar("Utilidades.Tickets.Lista.ListaTicketPosts");
importar("Utils.Dados.DataHora");
importar("Utils.EnvioEmail");

class Ticket extends Objeto {
	
	private $cliente;
	private	$nivel;
	private	$status;
	private	$satisfacao;
	private	$posts;
	private	$dataAlteracao;
	
	public	$titulo;
	
	const	NIVEL_URGENTE			= 3;
	const	NIVEL_MEDIO				= 2;
	const	NIVEL_NORMAL			= 1;
	const	STATUS_ABERTO			= 1;
	const	STATUS_FECHADO			= 0;
	const	SATISFACAO_EXCELENTE	= 1;
	const	SATISFACAO_BOM			= 0;
	const	SATISFACAO_RUIM			= 2;
	
	public function __construct($id = ''){
		
		parent::__construct($id);
		
		$this->cliente 			= new Pessoa;
		$this->titulo			= '';
		$this->nivel			= self::NIVEL_NORMAL;
		$this->status			= self::STATUS_ABERTO;
		$this->satisfacao		= self::SATISFACAO_BOM;
		$this->dataAlteracao	= new DataHora;
		
		$this->posts			= new ListaTicketPosts;
		$this->posts->condicoes(array(1 => array('valor' => $this->getId(), 'campo' =>ListaTicketPosts::TICKET)));
		
	}
	
	public function setCliente(Pessoa $cl){
		$this->cliente = $cl;
	}
	
	public function getCliente(){
		return $this->cliente;
	}
	
	public function setNivel($n){
		$this->nivel = $n;
	}
	
	public function getNivel(){
		return $this->nivel;
	}
	
	public function setStatus($s){
		$this->status = $s;
	}
	
	public function getStatus(){
		return $this->status;
	}
	
	public function setSatisfacao($s){
		$this->satisfacao = $s;
	}
	
	public function getSatisfacao(){
		return $this->satisfacao;
	}
	
	public function addPostagem(TicketPost $tP){
		
		if($this->getId() != ""){
			$this->posts->inserir($tP, $this);
			
			if($this->posts->getTotal() > 0)
				EnvioEmail::$assunto = 'Ticket Respondido';
			else
				EnvioEmail::$assunto = 'Um chamado foi criado!';
				
			EnvioEmail::$de = Sistema::$nomeEmpresa."<".Sistema::$emailEmpresa.">";
			EnvioEmail::$html = true;
			
			$iT = new IFAdmin(new Arquivos(Sistema::$adminLayoutCaminhoDiretorio."SistemaUtilidades/ticketemail.html"));
			$iT->trocar("mensagem", $tP->texto);
			$iT->trocar("id", $this->getId());
			$iT->trocar("data", $tP->getDataHora()->mostrar());
			$iT->trocar("hora", $tP->getDataHora()->mostrar("H:i"));
			$iT->trocar("titulo", $this->titulo);
			$iT->trocar("status", self::__GetStatus($this->status));
			$iT->trocar("linkVisualizar.Ticket", Sistema::$caminhoURL."br/tickets/".$this->getId());
			
			EnvioEmail::$msg = $iT->concluir();
			
			while($email = $this->getCliente()->getEmail()->listar()){
				EnvioEmail::$para = $email->email;
				EnvioEmail::enviar();
			}
			
			EnvioEmail::$para = Sistema::$emailEmpresa;
			EnvioEmail::enviar();
			
		}
		
	}
	
	public function getPostagens(){
		return $this->posts;
	}
	
	public static function __GetNivel($n){
		
		switch ($n) {
			
			case 1: return "Normal";
			case 2: return "Medio";
			case 3: return "Urgente";
			
		}
		
	}
	
	public static function __GetStatus($s){
		
		switch ($s) {
			
			case 1: return "Aberto";
			case 0: return "Fechado";
			
		}
		
	}
	
	public static function __GetSatisfacao($s){
		
		switch ($n) {
			
			case 2: return "Ruim";
			case 1: return "Excelente";
			case 0: return "Bom";
			
		}
		
	}
	
	public function setDataAlteracao(DataHora $dT){
		$this->dataAlteracao = $dT;
	}
	
	public function getDataAlteracao(){
		return $this->dataAlteracao;
	}
	
}

?>