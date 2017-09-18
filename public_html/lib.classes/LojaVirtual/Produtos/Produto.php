<?php

importar('Geral.Objeto');
importar("Geral.URL");
importar('Utils.Dados.Numero');
importar('Utils.Dados.DataHora');
importar('Geral.Lista.ListaImagens');
importar('LojaVirtual.Categorias.Lista.ListaProdutoCategorias');
importar('LojaVirtual.Produtos.ProdutoMarca');
importar('LojaVirtual.Produtos.Lista.ListaProdutos');
importar('LojaVirtual.Produtos.Opcoes.Lista.ListaProdutoOpcaoGerados');

class Produto extends Objeto {
	
	const		FRETE_NORMAL				= 3;
	const		FRETE_GRATIS				= 1;
	const		SEM_FRETE					= 2;
	
	const		TIPO_PEDIDO_SOB_CONSULTA	= 1;
	
	protected	$peso;
	protected	$altura;
	protected	$largura;
	protected	$comprimento;
	protected	$valorCusto;
	protected	$valorReal;
	protected	$valorVenda;
	protected	$dataCadastro;
	protected	$imagens;
	protected	$categorias;
	protected	$encomendas;
	protected	$infos;
	protected	$video;
	protected	$url;
	protected	$marca;
	protected	$produtoPai;
	protected	$opcoes;
	
	private		$carregarImagensPai = true;
	
	public		$codigo;
	public		$nome;
	public		$estoque;
	public		$descricaoPequena;
	public		$descricao;
	public 		$disponivel;
	public 		$promocao;
	public		$lancamento;
	public		$destaque;
	public		$removido;
	public		$ordem;
	public		$tipoUnidade;
	public		$quantidadeu;
	public		$frete;
	public		$tipoPedido;
	public		$palavrasChaves;
	public		$manual;
	
	public function __construct($id = ''){
		
		parent::__construct($id);
		
		$this->produtoPai		= 0;
		$this->codigo			= '';
		$this->url				= new URL;
		$this->nome 			= '';
		$this->peso				= new Numero;
		$this->largura			= new Numero;
		$this->altura			= new Numero;
		$this->comprimento		= new Numero;
		$this->valorCusto 		= new Numero;
		$this->valorReal 		= new Numero;
		$this->valorVenda 		= new Numero;
		$this->estoque			= 0;
		$this->descricaoPequena	= '';
		$this->descricao		= '';
		$this->disponivel		= false;
		$this->promocao			= false;
		$this->lancamento		= false;
		$this->destaque			= false;
		$this->removido			= false;
		$this->dataCadastro 	= new DataHora;
		$this->ordem			= 0;
		$this->tipoUnidade		= '';
		$this->quantidadeu 		= 0;
		$this->video			= '';
		$this->marca			= new ProdutoMarca;
		$this->frete			= 0;
		$this->tipoPedido		= 0;
		$this->palavrasChaves = '';
		$this->manual			= '';
		
		$this->configCategorias(false);
		
		$this->encomendas	= new Lista("produtos_encomenda");
		$aR[1] = array('campo' => "idproduto", 	'valor' => $this->id);
		$this->encomendas->condicoes($aR);
		
		/*$this->infos			= new Lista("relacionamento_produtos_infos");
		$this->infos->condicoes('', '', '', '', "SELECT *, t.nome as nometamanho, t.imagem as imagemtamanho, c.nome as nomecor, c.imagem as imagemcor, p.nome as nomepedra, p.imagem as imagempedra FROM ".Sistema::$BDPrefixo."relacionamento_produtos_infos rpi LEFT OUTER JOIN ".Sistema::$BDPrefixo."produtos_pedras p ON p.id = rpi.pedra LEFT OUTER JOIN ".Sistema::$BDPrefixo."produtos_tamanhos t ON t.id = rpi.tamanho LEFT OUTER JOIN ".Sistema::$BDPrefixo."produtos_cores c ON c.id = rpi.cor WHERE rpi.produto = '".$this->id."'");*/
		$this->infos			= new ListaProdutos;
		$aRP[1] = array('campo' => ListaProdutos::PRODUTOPAI, 'valor' => $this->getId());
		$aRP[2] = array('campo' => ListaProdutos::REMOVIDO, 'valor' => ListaProdutos::VALOR_DISPONIVEL_FALSE);
		$this->infos->condicoes($aRP);
		
		$this->opcoes 			= new ListaProdutoOpcaoGerados;
		$aPO[1] = array('campo' => ListaProdutoOpcaoGerados::PRODUTO, 'valor' => $this->getId());
		$this->opcoes->condicoes($aPO);
		
	}
	
	public function __get($propriedade){
		
		if($propriedade == 'peso')
			return $this->peso;
		elseif($propriedade == 'largura')
			return $this->largura;
		elseif($propriedade == 'altura')
			return $this->altura;
		elseif($propriedade == 'comprimento')
			return $this->comprimento;
		elseif($propriedade == 'valorCusto')
			return $this->valorCusto;
		elseif($propriedade == 'valorReal')
			return $this->valorReal;
		elseif($propriedade == 'valorVenda')
			return $this->valorVenda;
		elseif($propriedade == 'valor')
			return $this->valorVenda->num < $this->valorReal->num && $this->valorVenda->num > 0 ? $this->valorVenda : $this->valorReal;
		
	}
	
	public function __set($propriedade, $v){
		
		if($propriedade == 'peso')
			$this->peso->num = $v;
		elseif($propriedade == 'largura')
			$this->largura->num = $v;
		elseif($propriedade == 'altura')
			$this->altura->num = $v;
		elseif($propriedade == 'comprimento')
			$this->comprimento->num = $v;
		elseif($propriedade == 'valorCusto')
			$this->valorCusto->num = $v;
		elseif($propriedade == 'valorReal')
			$this->valorReal->num = $v;
		elseif($propriedade == 'valorVenda')
			$this->valorVenda->num = $v;
		
	}
	
	public function setDataCadastro(DataHora $vDataCadastro){
		$this->dataCadastro = $vDataCadastro;	
	}
	
	public function getDataCadastro(){
		return $this->dataCadastro;
	}
	
	public function getImagens(){
		
		if(empty($this->imagens)){
			
			$this->imagens			= new ListaImagens;
			$this->imagens->caminhoEscrita = Sistema::$caminhoDiretorio.Sistema::$caminhoDataProdutos;
			$this->imagens->caminhoLeitura = Sistema::$caminhoURL.Sistema::$caminhoDataProdutos;
			
			$a[1] = array('campo' => ListaImagens::IDSESSAO, 	'valor' => $this->id);
			$a[2] = array('campo' => ListaImagens::SESSAO, 		'valor' => 'produtos');
			
			$this->imagens->condicoes($a);
			
			if($this->imagens->getTotal() == 0 && $this->carregarImagensPai){
				
				$a[1] = array('campo' => ListaImagens::IDSESSAO, 	'valor' => $this->getProdutoPai());
				$a[2] = array('campo' => ListaImagens::SESSAO, 		'valor' => 'produtos');
				
				$this->imagens->resetCondicoes();
				$this->imagens->condicoes($a);
				
			}
			
		}
		
		return $this->imagens;
	}
	
	protected function configCategorias($pai = false){
		
		$this->categorias		= new ListaProdutoCategorias;
		$this->categorias->condicoes('', '', '', '', "SELECT * FROM ".Sistema::$BDPrefixo."relacionamento_produtos_categorias rpc INNER JOIN ".Sistema::$BDPrefixo."produtos_categorias c ON c.id = rpc.categoria WHERE rpc.produto = '".$this->id."'");
		if($this->categorias->getTotal() == 0){
			$this->categorias->condicoes('', '', '', '', "SELECT * FROM ".Sistema::$BDPrefixo."relacionamento_produtos_categorias rpc INNER JOIN ".Sistema::$BDPrefixo."produtos_categorias c ON c.id = rpc.categoria WHERE rpc.produto = '".$this->produtoPai."'");
		}
		
	}
	
	public function addCategoria(ProdutoCategoria $pC){
		
		if($pC->getId() != '' && $this->getId() != ''){
		
			$con = BDConexao::__Abrir();
			$con->executar("INSERT INTO ".Sistema::$BDPrefixo."relacionamento_produtos_categorias(produto, categoria) VALUES('".$this->getId()."','".$pC->getId()."')");		
		
		}
		
	}
	
	public function getCategorias(){
		
		return $this->categorias;
		
	}
	
	public function addEncomenda($email){
		
		if($email != '' && $this->getId() != ''){
		
			$con = BDConexao::__Abrir();
			$con->executar("SELECT * FROM ".Sistema::$BDPrefixo."produtos_encomenda WHERE idproduto = '".$this->getId()."' AND email = '".$email."'");
			if($con->getTotal() == 0)
				$con->executar("INSERT INTO ".Sistema::$BDPrefixo."produtos_encomenda(idproduto, email) VALUES('".$this->getId()."','".$email."')");		
		
		}
		
	}
	
	public function getEncomendas(){
		
		return $this->encomendas;
		
	}
	
	public function getInfos(){
		return $this->infos;
	}
	
	public function setVideo($video){
		$this->video = $video;
	}	
	
	public function getVideo(){
		
		if(!empty($this->video)){
			
			$p = explode("&", $this->video);
			if(!eregi('http://www.youtube.com/v/', $p[0]))
				return "http://www.youtube.com/v/".str_replace('http://www.youtube.com/watch?v=', '', $p[0])."&hl=en_US&fs=1&";	
			else
				return $p[0];
			
		}
	}
	
	public function setURL(URL $url){
		$this->url = $url;	
	}
	
	public function getURL(){
		return $this->url;	
	}
	
	public function setMarca(ProdutoMarca $marca){
		$this->marca = $marca;	
	}
	
	public function getMarca(){
		return $this->marca;	
	}
	
	public function setProdutoPai(Produto $p, $all = true){
		
		$this->produtoPai 			= $p->getId();
		
		$this->carregarImagensPai	= $all;
		$this->configCategorias($all);
		
		if($all){
			$this->nome 			= $p->nome;
			$this->peso				= $p->peso;
			$this->largura			= $p->largura;
			$this->altura			= $p->altura;
			$this->comprimento		= $p->comprimento;
			$this->tipoPedido		= $p->tipoPedido;
			$this->valorCusto		= $p->valorCusto;
			$this->valorReal		= $p->valorReal;
			$this->valorVenda		= $p->valorVenda;
			$this->descricaoPequena	= $p->descricaoPequena;
			$this->descricao 		= $p->descricao;
			$this->ordem			= $p->ordem;
			$this->disponivel		= $p->disponivel;
			$this->promocao			= $p->promocao;
			$this->lancamento		= $p->lancamento;
			$this->destaque			= $p->destaque;
			$this->tipoUnidade		= $p->tipoUnidade;
			$this->quantidadeu		= $p->quantidadeu;
			$this->palavrasChaves = $p->palavrasChaves;
			//$this->estoque			= $p->estoque;
			$this->codigo			= $p->codigo;
			$this->frete			= $p->frete;
			
			$this->setVideo($p->getVideo());
			$this->setMarca($p->getMarca());
		
		}
		
	}
	
	public function getProdutoPai(){
		return $this->produtoPai;
	}
	
	public function addOpcao(ProdutoOpcao $obj, ProdutoOpcaoValor $obj2){
		
		if($this->getId() != ''){
			
			$pOG = new ProdutoOpcaoGerado;
			$pOG->setOpcao($obj);
			$pOG->setValor($obj2);
			
			$this->opcoes->inserir($pOG, $this);
			
		}
		
	}
	
	public function getOpcoes(){
		return $this->opcoes;
	}
	
	public function __toString(){
		return $this->nome;
	}
	
}

?>