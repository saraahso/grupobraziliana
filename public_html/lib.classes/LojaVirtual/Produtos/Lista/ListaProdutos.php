<?php

importar("Geral.Lista");
importar("LojaVirtual.Produtos.Produto");
importar("LojaVirtual.Produtos.Lista.ListaProdutoMarcas");

class ListaProdutos extends Lista {
	
	const ID						= 'id';
	const PRODUTOPAI				= 'produtopai';
	const NOME						= 'nome';
	const PESO						= 'peso';
	const LARGURA					= 'largura';
	const ALTURA					= 'altura';
	const COMPRIMENTO				= 'comprimento';
	const VALORCUSTO				= 'valorcusto';
	const VALORREAL					= 'valorreal';
	const VALORVENDA				= 'valorvenda';
	const ESTOQUE					= 'estoque';
	const DESCRICAOPEQUENA			= 'descricaopequena';
	const DESCRICAO					= 'descricao';
	const DISPONIVEL				= 'disponivel';
	const PROMOCAO					= 'promocao';
	const LANCAMENTO				= 'lancamento';
	const DESTAQUE					= 'destaque';
	const REMOVIDO					= 'removido';
	const ORDEM						= 'ordem';
	const TIPOUNIDADE				= 'tipounidade';
	const QUANTIDADEU				= 'quantidade';
	const DATACADASTRO				= 'datacadastro';
	const VIDEO						= 'urlvideo';
	const MARCA						= 'marca';
	const CODIGO					= 'codigo';
	const FRETE						= 'frete';
	const TIPOPEDIDO				= 'tipopedido';
	const PALAVRASCHAVES			= 'palavraschaves';
	const MANUAL					= 'manual';
	
	const VALOR_DISPONIVEL_TRUE 	= 1;
	const VALOR_DISPONIVEL_FALSE 	= 0;
	
	protected	$carregarDadosPai	= true;
	
	public function __construct(){
		
		parent::__construct('produtos');
		
		$this->ID = self::ID;
		
	}
	
	public function listar($ordem = "ASC", $campo = self::ID){
		
		$info = parent::listar($ordem, $campo);
		
		if(!empty($info)){ 
		
			$temp = new Produto($info[self::ID]);
			
			parent::resgatarObjetos($info);
			
			//$l = new ListaProdutos;
			//$l->condicoes('', $info[self::PRODUTOPAI], self::ID);
			//if($l->getTotal() > 0)
				//$temp->setProdutoPai($l->listar(), $this->carregarDadosPai);
			
			if(!empty($info[self::NOME]))
				$temp->nome 			= $info[self::NOME];
			if($info[self::PESO] > 0)
				$temp->peso				= $info[self::PESO];
			if($info[self::LARGURA] > 0)
				$temp->largura			= $info[self::LARGURA];
			if($info[self::ALTURA] > 0)
				$temp->altura			= $info[self::ALTURA];
			if($info[self::COMPRIMENTO] > 0)
				$temp->comprimento		= $info[self::COMPRIMENTO];
			if($info[self::VALORCUSTO] > 0)
				$temp->valorCusto		= $info[self::VALORCUSTO];
			if($info[self::VALORREAL] > 0)
				$temp->valorReal		= $info[self::VALORREAL];
			if($info[self::VALORVENDA] > 0)
				$temp->valorVenda		= $info[self::VALORVENDA];
			
			if($info[self::FRETE] > 0)
				$temp->frete			= $info[self::FRETE];
			if($info[self::TIPOPEDIDO] > 0)
				$temp->tipoPedido		= $info[self::TIPOPEDIDO];
			
			$temp->setURL($info[parent::URL]);
			
			if(trim(strip_tags($info[self::DESCRICAOPEQUENA])) != '' || preg_match("!<img!", $info[self::DESCRICAOPEQUENA]))
				$temp->descricaoPequena	= $info[self::DESCRICAOPEQUENA];
			if(trim(strip_tags($info[self::DESCRICAO])) != '' || preg_match("!<img!", $info[self::DESCRICAO]))
				$temp->descricao 		= $info[self::DESCRICAO];
			
			if($info[self::ORDEM] > 0)
				$temp->ordem			= $info[self::ORDEM];
			if($info[self::DISPONIVEL] > 0)
				$temp->disponivel		= $info[self::DISPONIVEL] 	== self::VALOR_DISPONIVEL_TRUE ? true : false;
			if($info[self::PROMOCAO] > 0)
				$temp->promocao			= $info[self::PROMOCAO] 	== self::VALOR_DISPONIVEL_TRUE ? true : false;
			if($info[self::LANCAMENTO] > 0)
				$temp->lancamento		= $info[self::LANCAMENTO] 	== self::VALOR_DISPONIVEL_TRUE ? true : false;
			if($info[self::DESTAQUE] > 0)
				$temp->destaque			= $info[self::DESTAQUE] 	== self::VALOR_DISPONIVEL_TRUE ? true : false;
			if($info[self::REMOVIDO] > 0 && !$temp->removido)
				$temp->removido			= $info[self::REMOVIDO] 	== self::VALOR_DISPONIVEL_TRUE ? true : false;
			
			if(!empty($info[self::TIPOUNIDADE]))	
				$temp->tipoUnidade		= $info[self::TIPOUNIDADE];
			
			if($info[self::QUANTIDADEU] > 0)
				$temp->quantidadeu		= $info[self::QUANTIDADEU];
			if($info[self::ESTOQUE] > 0)
				$temp->estoque			= $info[self::ESTOQUE];
			
			if(!empty($info[self::CODIGO]))
				$temp->codigo			= $info[self::CODIGO];
			if(!empty($info[self::PALAVRASCHAVES]))
				$temp->palavrasChaves 			= $info[self::PALAVRASCHAVES];
			if(!empty($info[self::MANUAL]))
				$temp->manual 			= $info[self::MANUAL];
			
			if(!empty($info[self::VIDEO]))
				$temp->setVideo($info[self::VIDEO]);
			
			$temp->setDataCadastro(new DataHora($info[self::DATACADASTRO]));
			
			$lPM = new ListaProdutoMarcas;
			$lPM->condicoes('', $info[self::MARCA], ListaProdutoMarcas::ID);
			if($lPM->getTotal() > 0)
				$temp->setMarca($lPM->listar());
			
			if($temp->getMarca()->getId() == 182 || $temp->getMarca()->getId() == 160){
				$temp->valorReal = 0;
				$temp->valorVenda = 0;
			}
			
			return $temp;
		
		}
		
	}
	
	public function inserir(Produto &$p){
		
		parent::inserir($p);
		
		$disponivel 	= $p->disponivel 	? self::VALOR_DISPONIVEL_TRUE : self::VALOR_DISPONIVEL_FALSE;
		$promocao 		= $p->promocao 		? self::VALOR_DISPONIVEL_TRUE : self::VALOR_DISPONIVEL_FALSE;
		$lancamento 	= $p->lancamento 	? self::VALOR_DISPONIVEL_TRUE : self::VALOR_DISPONIVEL_FALSE;
		$destaque 		= $p->destaque 		? self::VALOR_DISPONIVEL_TRUE : self::VALOR_DISPONIVEL_FALSE;
		
		if($p->getId() != '')
			$this->con->executar("INSERT INTO ".Sistema::$BDPrefixo.$this->tabela."(".self::ID.", ".self::PRODUTOPAI.", ".self::NOME.", ".self::URL.", ".self::PESO.", ".self::LARGURA.", ".self::ALTURA.", ".self::COMPRIMENTO.", ".self::VALORCUSTO.", ".self::VALORREAL.", ".self::VALORVENDA.", ".self::ESTOQUE.", ".self::DESCRICAOPEQUENA.", ".self::DESCRICAO.", ".self::DISPONIVEL.", ".self::PROMOCAO.", ".self::LANCAMENTO.", ".self::DESTAQUE.", ".self::ORDEM.", ".self::TIPOUNIDADE.", ".self::QUANTIDADEU.", ".self::DATACADASTRO.", ".self::VIDEO.", ".self::MARCA.", ".self::CODIGO.", ".self::FRETE.", ".self::TIPOPEDIDO.", ".self::PALAVRASCHAVES.", ".self::MANUAL.") VALUES('".$p->getId()."','".$p->getProdutoPai()."','".addslashes(str_replace("\"", "'", $p->nome))."','".$p->getURL()->getId()."','".$p->peso->formatar(".", "", 3)."','".$p->largura->formatar()."','".$p->altura->formatar()."','".$p->comprimento->formatar()."','".$p->valorCusto->formatar()."','".$p->valorReal->formatar()."','".$p->valorVenda->formatar()."','".$p->estoque."','".addslashes($p->descricaoPequena)."','".addslashes($p->descricao)."','".$disponivel."','".$promocao."','".$lancamento."','".$destaque."','".$p->ordem."','".addslashes(str_replace("\"", "'", $p->tipoUnidade))."','".$p->quantidadeu."','".$p->getDataCadastro()->mostrar("Ymd")."','".$p->getVideo()."','".$p->getMarca()->getId()."','".$p->codigo."','".$p->frete."','".$p->tipoPedido."','".$p->palavrasChaves."','".$p->manual."')");
		else
			$this->con->executar("INSERT INTO ".Sistema::$BDPrefixo.$this->tabela."(".self::PRODUTOPAI.", ".self::NOME.", ".self::URL.", ".self::PESO.", ".self::LARGURA.", ".self::ALTURA.", ".self::COMPRIMENTO.", ".self::VALORCUSTO.", ".self::VALORREAL.", ".self::VALORVENDA.", ".self::ESTOQUE.", ".self::DESCRICAOPEQUENA.", ".self::DESCRICAO.", ".self::DISPONIVEL.", ".self::PROMOCAO.", ".self::LANCAMENTO.", ".self::DESTAQUE.", ".self::ORDEM.", ".self::TIPOUNIDADE.", ".self::QUANTIDADEU.", ".self::DATACADASTRO.", ".self::VIDEO.", ".self::MARCA.", ".self::CODIGO.", ".self::FRETE.", ".self::TIPOPEDIDO.", ".self::PALAVRASCHAVES.", ".self::MANUAL.") VALUES('".$p->getProdutoPai()."','".addslashes(str_replace("\"", "'", $p->nome))."','".$p->getURL()->getId()."','".$p->peso->formatar(".", "", 3)."','".$p->largura->formatar()."','".$p->altura->formatar()."','".$p->comprimento->formatar()."','".$p->valorCusto->formatar()."','".$p->valorReal->formatar()."','".$p->valorVenda->formatar()."','".$p->estoque."','".addslashes($p->descricaoPequena)."','".addslashes($p->descricao)."','".$disponivel."','".$promocao."','".$lancamento."','".$destaque."','".$p->ordem."','".addslashes(str_replace("\"", "'", $p->tipoUnidade))."','".$p->quantidadeu."','".$p->getDataCadastro()->mostrar("Ymd")."','".$p->getVideo()."','".$p->getMarca()->getId()."','".$p->codigo."','".$p->frete."','".$p->tipoPedido."','".$p->palavrasChaves."','".$p->manual."')");
		
		$id = $this->con->getId();
		
		$class 	= __CLASS__;
		$l 		= new $class;
		$l->condicoes('', $id, self::ID);
		
		$p = $l->listar();
		
		parent::alterar($p);
		
	}
	
	public function alterar(Produto $p){
		
		parent::alterar($p);
		
		$where = "WHERE ".self::ID." = '".$p->getId()."'";
		
		$disponivel 	= $p->disponivel 	? self::VALOR_DISPONIVEL_TRUE : self::VALOR_DISPONIVEL_FALSE;
		$promocao 		= $p->promocao 		? self::VALOR_DISPONIVEL_TRUE : self::VALOR_DISPONIVEL_FALSE;
		$lancamento 	= $p->lancamento 	? self::VALOR_DISPONIVEL_TRUE : self::VALOR_DISPONIVEL_FALSE;
		$destaque 		= $p->destaque 		? self::VALOR_DISPONIVEL_TRUE : self::VALOR_DISPONIVEL_FALSE;
		$removido 		= $p->removido 		? self::VALOR_DISPONIVEL_TRUE : self::VALOR_DISPONIVEL_FALSE;
		
		$this->con->alterar(Sistema::$BDPrefixo.$this->tabela, self::URL, 				$p->getURL()->getId(), $where);
		$this->con->alterar(Sistema::$BDPrefixo.$this->tabela, self::NOME, 				addslashes(str_replace("\"", "'", $p->nome)), $where);
		$this->con->alterar(Sistema::$BDPrefixo.$this->tabela, self::PESO, 				$p->peso->formatar(".", "", 3), $where);
		$this->con->alterar(Sistema::$BDPrefixo.$this->tabela, self::LARGURA, 			$p->largura->formatar(), $where);
		$this->con->alterar(Sistema::$BDPrefixo.$this->tabela, self::ALTURA, 			$p->altura->formatar(), $where);
		$this->con->alterar(Sistema::$BDPrefixo.$this->tabela, self::COMPRIMENTO, 		$p->comprimento->formatar(), $where);
		$this->con->alterar(Sistema::$BDPrefixo.$this->tabela, self::VALORCUSTO,		$p->valorCusto->formatar(), $where);
		$this->con->alterar(Sistema::$BDPrefixo.$this->tabela, self::VALORREAL, 		$p->valorReal->formatar(), $where);
		$this->con->alterar(Sistema::$BDPrefixo.$this->tabela, self::VALORVENDA, 		$p->valorVenda->formatar(), $where);
		$this->con->alterar(Sistema::$BDPrefixo.$this->tabela, self::ESTOQUE, 			$p->estoque, $where);
		$this->con->alterar(Sistema::$BDPrefixo.$this->tabela, self::DESCRICAOPEQUENA, 	addslashes($p->descricaoPequena), $where);
		$this->con->alterar(Sistema::$BDPrefixo.$this->tabela, self::DESCRICAO, 		addslashes($p->descricao), $where);
		$this->con->alterar(Sistema::$BDPrefixo.$this->tabela, self::PALAVRASCHAVES, 		$p->palavrasChaves, $where);
		$this->con->alterar(Sistema::$BDPrefixo.$this->tabela, self::MANUAL, 			$p->manual, $where);
		
		$this->con->alterar(Sistema::$BDPrefixo.$this->tabela, self::DISPONIVEL, 		$disponivel, $where);
		$this->con->alterar(Sistema::$BDPrefixo.$this->tabela, self::PROMOCAO,	 		$promocao, $where);
		$this->con->alterar(Sistema::$BDPrefixo.$this->tabela, self::LANCAMENTO, 		$lancamento, $where);
		$this->con->alterar(Sistema::$BDPrefixo.$this->tabela, self::DESTAQUE,	 		$destaque, $where);
		$this->con->alterar(Sistema::$BDPrefixo.$this->tabela, self::REMOVIDO,	 		$removido, $where);
		$this->con->alterar(Sistema::$BDPrefixo.$this->tabela, self::ORDEM, 			$p->ordem, $where);
		$this->con->alterar(Sistema::$BDPrefixo.$this->tabela, self::CODIGO, 			$p->codigo, $where);
		$this->con->alterar(Sistema::$BDPrefixo.$this->tabela, self::FRETE, 			$p->frete, $where);
		$this->con->alterar(Sistema::$BDPrefixo.$this->tabela, self::TIPOPEDIDO, 		$p->tipoPedido, $where);
		$this->con->alterar(Sistema::$BDPrefixo.$this->tabela, self::TIPOUNIDADE, 		addslashes(str_replace("\"", "'", $p->tipoUnidade)), $where);
		$this->con->alterar(Sistema::$BDPrefixo.$this->tabela, self::QUANTIDADEU, 		$p->quantidadeu, $where);
		$this->con->alterar(Sistema::$BDPrefixo.$this->tabela, self::VIDEO, 			$p->getVideo(), $where);
		$this->con->alterar(Sistema::$BDPrefixo.$this->tabela, self::MARCA, 			$p->getMarca()->getId(), $where);
		
	}
	
	public function deletar(Produto $p){
		
		parent::deletar($p);
		
		$where = "WHERE ".self::ID." = '".$p->getId()."'";
		
		while($img = $p->getImagens()->listar("ASC")){
			$p->getImagens()->deletar($img);
			$p->getImagens()->setParametros(0);
		}
		
		$this->con->deletar(Sistema::$BDPrefixo."relacionamento_produtos_categorias", "WHERE produto = '".$p->getId()."'");			
		$this->con->deletar(Sistema::$BDPrefixo.$this->tabela, $where);
		
	}
	
	public function enableDadosProdutoPai(){
		$this->carregarDadosPai = true;
		return $this;
	}
	
	public function disableDadosProdutoPai(){
		$this->carregarDadosPai = false;
		return $this;
	}
	
}

?>