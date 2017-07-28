<?php

importar("Geral.Objeto");
importar("Geral.Idiomas.Lista.ListaTraducoes");
importar("Utils.Imagens.Image");

final class Idioma extends Objeto{
	
	private $imagem;
	private	$traducoes;
	
	public	$nome;
	public 	$sigla;
	
	public function __construct($id = ''){
		
		parent::__construct($id);
		
		$this->id 			= $id;
		$this->sigla 		= '';
		$this->nome 		= '';
		
		$this->imagem 		= new Image;
		
		$this->traducoes 	= new ListaTraducoes;
		$this->traducoes->condicoes(array(1 => array('campo' => ListaTraducoes::IDIOMA, 'valor' => $this->id)));
		
	}
	
	public function getId(){
		return $this->id;	
	}
	
	public function setImagem(Image $imagem){
		$this->imagem = $imagem;	
	}
	
	public function getImagem(){
		return $this->imagem;	
	}
	
	public function addTraducao(Traducao $t){
		
		if(!empty($this->id)){
			
			$t->setIdIdioma($this->id);
			$this->traducoes->inserir($t);
			
		}else
			return false;
		
	}
	
	public function getTraducoes(){
		return $this->traducoes;	
	}
	
	public function getTraducaoById($campo, $tabela, $id){
		
		$v[1] 	= array('campo' => ListaTraducoes::TABELACONTEUDO, 	'valor' => $tabela);
		$v[2] 	= array('campo' => ListaTraducoes::IDCONTEUDO, 		'valor' => $id);
		$v[3] 	= array('campo' => ListaTraducoes::CAMPOCONTEUDO, 	'valor' => $campo);
		$v[4]	= array('campo' => ListaTraducoes::IDIOMA, 			'valor' => $this->id);
		
		$lT		= new ListaTraducoes;
		$lT->condicoes($v);
		
		if($lT->getTotal() > 0)
			$t = $lT->listar();
		else{
			
			$t = new Traducao;
		
			$con = BDConexao::__Abrir();
			$con->executar("SELECT * FROM ".Sistema::$BDPrefixo.$tabela." WHERE id = '".$id."'");
			$rs = $con->getRegistro();
		
			$t->conteudo = $rs[$campo];
			$t->traducao = $rs[$campo];
			
		}
			
		return $t;
		
	}
	
	public function getTraducaoByConteudo($conteudo, $tabela = '', $returnOriginal = true){
		
		$v[1] = array('campo' => ListaTraducoes::CONTEUDO, 	'valor' => $conteudo);
		$v[2] = array('campo' => ListaTraducoes::IDIOMA, 	'valor' => $this->id);
		if(!empty($tabela))
			$v[3] = array('campo' => ListaTraducoes::TABELACONTEUDO, 'valor' => $tabela);
		
		$lT	  = new ListaTraducoes;
		$lT->condicoes($v);
		
		if($lT->getTotal() > 0){
			return $lT->listar();
		}else{
			$t = new Traducao;
			if($returnOriginal)
				$t->traducao = $conteudo;
			return $t;
		}
	}
	
	public static function __GetTraducaoById($idioma, $tabela, $id){
		
		$v[1] = array('campo' => 'idioma', 'valor' => $idioma);
		$v[2] = array('campo' => 'tabela', 'valor' => $tabela);
		$v[3] = array('campo' => 'idconteudo', 'valor' => $id);		
		
		$traducoes = new Lista('traducoes');
		$traducoes->condicoes($v);
		
		$this->traducoes->condicoes($v);
		
		return $this->traducoes->listar();
		
	}
	
	public static function __GetTraducaoByConteudo($idioma, $conteudo){
		
		$v[1] = array('campo' => 'idioma', 'valor' => $idioma);
		$v[2] = array('campo' => 'conteudo', 'valor' => $conteudo);	
		
		$traducoes = new Lista('traducoes');
		$traducoes->condicoes($v);
		
		$traducoes->condicoes($v);
		
		if($traducoes->getTotal() > 0){
			$rs = $traducoes->listar();
			return $rs['traducao'];
		}else
			return $conteudo;
		
	}
	
}

?>