<?php

importar("Geral.Objeto");
importar("Geral.URL");
importar("Utils.Imagens.Image");
importar("LojaVirtual.Categorias.Lista.ListaProdutoCategorias");
importar("LojaVirtual.Produtos.Lista.ListaProdutos");
importar("Geral.Idiomas.Lista.ListaIdiomas");

class ProdutoCategoria extends Objeto {
		
	private		$idCategoriaPai;
	private		$imagem;
	private		$subC;
	private 	$produtos;
	private		$url;
	
	public		$nome;
	public		$descricaoPequena;
	public		$descricao;
	public		$ordem;
	public		$disponivel;
	public		$visaoUnica;
	public		$home;
	public		$cor;
	public		$subreferencia;
	
	public function __construct($id = ''){
		
		parent::__construct($id);
		
		$this->idCategoriaPai	= '';
		$this->nome 			= '';
		$this->descricaoPequena	= '';
		$this->descricao 		= '';
		$this->imagem 			= new Image;
		$this->url				= new URL;
		$this->disponivel		= true;
		$this->visaoUnica		= false;
		$this->home				= false;
		$this->cor				= '';
		
		$this->subC 			= new ListaProdutoCategorias;
		$aPC[1] 				= array('campo' => ListaProdutoCategorias::CATEGORIAPAI, 'valor' => $this->getId());
		$this->subC->condicoes($aPC);
		
		$this->produtos 		= new ListaProdutos;
		$this->produtos->condicoes('', '', '', '', "SELECT * FROM ".Sistema::$BDPrefixo."relacionamento_produtos_categorias rpc INNER JOIN ".Sistema::$BDPrefixo."produtos p ON p.id = rpc.produto WHERE rpc.categoria = '".$this->id."'");
		
		$this->subreferencia	= '';
		
	}
	
	public function setImagem(Image $vImagem){
		$this->imagem = $vImagem;
	}
	
	public function getImagem(){
		return $this->imagem;
	}
	
	public function addSubCategoria(ProdutoCategoria $vSubC){
		
		if(!empty($this->id)){
			$vSub->setIdCategoriaPai($this->getId());
			$this->subC->inserir($vSub);
		}
		
	}
	
	public function getSubCategorias(){
		
		return $this->subC;
		
	}
	
	public function setIdCategoriaPai($id){
		
		$this->idCategoriaPai = $id;
		
	}
	
	public function getIdCategoriaPai(){

		return $this->idCategoriaPai;
		
	}
	
	public function getProdutos(){
		
		return $this->produtos;
		
	}
	
	public function getNavegador(Templates $tem = null, $separador = " > "){
		
		$lI  		= new ListaIdiomas;
		if(isset($this->session['lang'])){
			if($lI->condicoes('', $this->session['lang'], ListaIdiomas::SIGLA)->getTotal() > 0)
				$idioma = $lI->listar();
			else
				$idioma = new Idioma;
		}else
			$idioma = new Idioma;
			
		
		return self::createNavegador($this, $idioma, $tem, $separador);
		
	}
	
	public static function createNavegador(ProdutoCategoria $pC, Idioma $idioma = null, Templates $tem = null, $separador = " > "){
		
		$lPC = new ListaProdutoCategorias;
		$lPC->condicoes('', $pC->getIdCategoriaPai(), ListaProdutoCategorias::ID);
		
		if($lPC->getTotal() > 0){
			
			$cPC = $lPC->listar();
			
			if($tem){
				
				$tem2 = new Templates(Arquivos::__Create($tem->getArquivo()->arquivo));
				
				if($idioma)
					$tem->trocar("nome", $idioma->getTraducaoByConteudo($pC->nome)->traducao);
				else
					$tem->trocar("nome", $pC->nome);
				
				$tem->trocar("url", $pC->getURL()->url);
				$tem->trocar("id", $pC->getId());
				$tem->trocar("ordem", $pC->ordem);
				
				if($pC->getId() != '') return self::createNavegador($cPC, $idioma, $tem2, $separador).$separador.$tem->concluir();
				
			}else{
				
				if($idioma)
					return self::createNavegador($cPC, $idioma, null, $separador).$separador.$idioma->getTraducaoByConteudo($pC->nome)->traducao;
				else
					return self::createNavegador($cPC, null, null, $separador).$separador.$pC->nome;
				
			}
			
		}else{
			
			if($tem){
				
				if($idioma)
					$tem->trocar("nome", $idioma->getTraducaoByConteudo($pC->nome)->traducao);
				else
					$tem->trocar("nome", $pC->nome);
				
				$tem->trocar("url", $pC->getURL()->url);
				$tem->trocar("id", $pC->getId());
				$tem->trocar("ordem", $pC->ordem);
				
				if($pC->getId() != '') return $tem->concluir();
				
			}else
				if($idioma)
					return $idioma->getTraducaoByConteudo($pC->nome)->traducao;
				else	
					return $pC->nome;

			
		}
		
	}
	
	public function setURL(URL $url){
		$this->url = $url;	
	}
	
	public function getURL(){
		return $this->url;	
	}
	
	/*public static function getIdAllSubCategoria($id = 0){
		
		$a = array();
		if($id > 0)
			$a[] = $id;
		$con = BDConexao::__Abrir();
		$con->executar("SELECT id FROM ".Sistema::$BDPrefixo."produtos_categorias WHERE categoriapai = '".$id."' AND disponivel = 1");
		//$subC 			= new ListaProdutoCategorias;
		//$aPC[1] 		= array('campo' => ListaProdutoCategorias::CATEGORIAPAI, 'valor' => $this->getId());
		//$subC->condicoes($aPC);
		//while($sub = $subC->listar()){
		while($sub = $con->getRegistro()){
			
			$a[] = $sub['id'];
			
			$b = self::getIdAllSubCategoria($sub['id']);
			if(count($b) > 0)
				$a = array_merge($a, $b);
		}
		
		return $a;
		
	}*/
	public function getIdAllSubCategoria(){
		
		$a = array();
		$a[' '.$this->getId()] = true;
		$subC 			= new ListaProdutoCategorias;
		$aPC[1] 		= array('campo' => ListaProdutoCategorias::CATEGORIAPAI, 'valor' => $this->getId());
		$subC->condicoes($aPC);
		while($sub = $subC->listar()){
			
			$a[' '.$sub->getId()] = true;
			
			$b = $sub->getIdAllSubCategoria();
			if(count($b) > 0)
				$a = array_merge($a, $b);
		}
		 
		return $a;
		
	}
		
}

?>