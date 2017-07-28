<?php

importar('Geral.Objeto');
importar("Geral.URL");
importar('Utils.Dados.Numero');
importar('Utils.Dados.DataHora');
importar('Geral.Lista.ListaImagens');
importar('LojaVirtual.Categorias.Lista.ListaProdutoCategorias');
importar('LojaVirtual.Produtos.EmpresaOfertaColetiva');

class OfertaColetiva extends Objeto {
	
	protected	$valorOriginal;
	protected	$desconto;
	protected	$economia;
	protected	$valor;
	protected	$dataInicio;
	protected	$dataFim;
	protected	$categorias;
	protected	$url;
	protected	$imagens;
	protected	$empresa;
	
	public		$titulo;
	public		$subTitulo;
	public 		$destaques;
	public 		$regulamento;
	public		$quantidade;
	public		$comprasMinima;
	public		$comprasMaxima;
	public		$comprasEfetuadas;
	
	public function __construct($id = ''){
		
		parent::__construct($id);
		

		$this->url				= new URL;
		$this->titulo 			= '';
		$this->subTitulo 		= '';
		$this->destaques 		= '';
		$this->regulamento 		= '';
		$this->valorOriginal	= new Numero;
		$this->desconto 		= new Numero;
		$this->economia 		= new Numero;
		$this->valor 			= new Numero;
		$this->quantidade		= 0;
		$this->comprasMinima	= 0;
		$this->comprasMaxima	= 0;
		$this->comprasEfetuadas	= 0;
		$this->empresa			= new EmpresaOfertaColetiva;
		
		$this->dataInicio 		= new DataHora;
		$this->dataFim 			= new DataHora;
		
		$this->imagens		= new ListaImagens;
		$this->imagens->caminhoEscrita = Sistema::$caminhoDiretorio.Sistema::$caminhoDataOfertasColetivas;
		$this->imagens->caminhoLeitura = Sistema::$caminhoURL.Sistema::$caminhoDataOfertasColetivas;
		
		$a[1] = array('campo' => ListaImagens::IDSESSAO, 	'valor' => $this->id);
		$a[2] = array('campo' => ListaImagens::SESSAO, 		'valor' => 'ofertascoletivas');
		
		$this->imagens->condicoes($a);
		
		$this->categorias	= new ListaProdutoCategorias;
		$this->categorias->condicoes('', '', '', '', "SELECT * FROM ".Sistema::$BDPrefixo."relacionamento_ofertascoletivas_categorias rpc INNER JOIN ".Sistema::$BDPrefixo."produtos_categorias c ON c.id = rpc.categoria WHERE rpc.ofertacoletiva = '".$this->id."'");
		
	}
	
	public function __get($propriedade){
		
		if($propriedade == 'valorOriginal')
			return $this->valorOriginal;
		elseif($propriedade == 'desconto')
			return $this->desconto;
		elseif($propriedade == 'economia')
			return $this->economia;
		elseif($propriedade == 'valor')
			return $this->valor;
		
	}
	
	public function __set($propriedade, $v){
		
		if($propriedade == 'valorOriginal')
			$this->valorOriginal->num = $v;
		elseif($propriedade == 'desconto')
			$this->desconto->num = $v;
		elseif($propriedade == 'economia')
			$this->economia->num = $v;
		elseif($propriedade == 'valor')
			$this->valor->num = $v;
		
	}
	
	public function setDataInicio(DataHora $vDataInicio){
		$this->dataInicio = $vDataInicio;	
	}
	
	public function getDataInicio(){
		return $this->dataInicio;
	}
	
	public function setDataFim(DataHora $vDataFim){
		$this->dataFim = $vDataFim;	
	}
	
	public function getDataFim(){
		return $this->dataFim;
	}
	
	public function getImagens(){
		return $this->imagens;
	}
	
	public function addCategoria(ProdutoCategoria $pC){
		
		if($pC->getId() != '' && $this->getId() != ''){
		
			$con = BDConexao::__Abrir();
			$con->executar("INSERT INTO ".Sistema::$BDPrefixo."relacionamento_ofertascoletivas_categorias(ofertacoletiva, categoria) VALUES('".$this->getId()."','".$pC->getId()."')");		
		
		}
		
	}
	
	public function getCategorias(){
		
		return $this->categorias;
		
	}
	
	public function setURL(URL $url){
		$this->url = $url;	
	}
	
	public function getURL(){
		return $this->url;	
	}
	
	public function setEmpresa(EmpresaOfertaColetiva $em){
		$this->empresa = $em;	
	}
	
	public function getEmpresa(){
		return $this->empresa;	
	}
	
}

?>