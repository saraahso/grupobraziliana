<?php

importar("LojaVirtual.Produtos.Lista.ListaProdutos");
importar("LojaVirtual.Produtos.Lista.ListaProdutoCores");
importar("LojaVirtual.Produtos.Lista.ListaProdutoTamanhos");
importar("LojaVirtual.Produtos.Lista.ListaProdutoPedras");
importar("LojaVirtual.Pedidos.Lista.ListaPedidos");
importar("LojaVirtual.Pedidos.PedidoItem");

class ListaPedidoItens extends Lista {
	
	const ID 						= 'id';
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
	const QUANTIDADEU				= 'quantidadeu';
	const DATACADASTRO				= 'datacadastro';
	const VIDEO						= 'urlvideo';
	const MARCA						= 'marca';
	const CODIGO					= 'codigo';
	const FRETE						= 'frete';
	const TIPOPEDIDO				= 'tipopedido';
	const QUANTIDADE 				= 'quantidade';
	const VALORFRETE				= 'fretevalor';
	const IDSESSAO					= 'idpedido';
	const OBSERVACAO				= 'observacao';
	
	const VALOR_DISPONIVEL_TRUE 	= 1;
	const VALOR_DISPONIVEL_FALSE 	= 0;
	
	public function __construct(){
		
		parent::__construct('pedido_itens');
		$this->enableClearCache = false;
		
	}
	
	public function listar($ordem = "ASC", $campo = self::ID){
		
		$info = parent::listar($ordem, $campo);
		
		if(!empty($info)){ 
			
			$lP = new ListaProdutos;
			$lP->condicoes('', $info[self::ID], ListaProdutos::ID);
			
			//if($lP->getTotal() > 0){
				
				$prod = $lP->listar();
				if($lP->getTotal() > 0)
					$temp = PedidoItem::__ProdutoToPedidoItem($prod);
				else
					$temp = new PedidoItem($info[self::ID]);
					
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
				
				if(trim(strip_tags($info[self::DESCRICAOPEQUENA])) != '')
					$temp->descricaoPequena	= $info[self::DESCRICAOPEQUENA];
				if(trim(strip_tags($info[self::DESCRICAO])) != '')
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
					
				if($info[self::TIPOUNIDADE] > 0)	
					$temp->tipoUnidade		= $info[self::TIPOUNIDADE];
				
				if($info[self::QUANTIDADEU] > 0)
					$temp->quantidadeu		= $info[self::QUANTIDADEU];
				if($info[self::ESTOQUE] > 0)
					$temp->estoque			= $info[self::ESTOQUE];
				
				if(!empty($info[self::CODIGO]))
					$temp->codigo			= $info[self::CODIGO];
				
				if(!empty($info[self::VIDEO]))
					$temp->setVideo($info[self::VIDEO]);
				
				$temp->setDataCadastro(new DataHora($info[self::DATACADASTRO]));
				
				$lPE = new ListaPedidos;
				$lPE->condicoes('', $info[self::IDSESSAO], ListaPedidos::ID);
				if($lPE->getTotal() > 0){
					$temp->setPedido($lPE->listar());
				}				
				$lPM = new ListaProdutoMarcas;
				$lPM->condicoes('', $info[self::MARCA], ListaProdutoMarcas::ID);
				if($lPM->getTotal() > 0)
					$temp->setMarca($lPM->listar());
				
				$temp->quantidade	= $info[self::QUANTIDADE];
				$temp->observacao	= $info[self::OBSERVACAO];
				
				$temp->setValorFrete($info[self::VALORFRETE]);
				
				return $temp;
			
			//}
		
		}
		
	}
	
	public function inserir(PedidoItem &$p, Pedido $pe){
		
		//$this->con->executar("INSERT INTO ".Sistema::$BDPrefixo.$this->tabela."(".self::ID.", ".self::IDSESSAO.", ".self::NOME.", ".self::URL.", ".self::PESO.", ".self::LARGURA.", ".self::ALTURA.", ".self::COMPRIMENTO.", ".self::VALORCUSTO.", ".self::VALORREAL.", ".self::VALORVENDA.", ".self::ESTOQUE.", ".self::DESCRICAOPEQUENA.", ".self::DESCRICAO.", ".self::DISPONIVEL.", ".self::PROMOCAO.", ".self::LANCAMENTO.", ".self::DESTAQUE.", ".self::COR.", ".self::PEDRA.", ".self::TAMANHO.", ".self::ORDEM.", ".self::TIPOUNIDADE.", ".self::QUANTIDADEU.", ".self::DATACADASTRO.", ".self::VIDEO.", ".self::MARCA.", ".self::CODIGO.", ".self::FRETE.", ".self::TIPOPEDIDO.", ".self::QUANTIDADE.", ".self::VALORFRETE.", ".self::OBSERVACAO.") VALUES('".$p->getId()."','".$pe->getId()."','".$p->nome."','".$p->getURL()->getId()."','".$p->peso->formatar(".", "", 3)."','".$p->largura->formatar()."','".$p->altura->formatar()."','".$p->comprimento->formatar()."','".$p->valorCusto->formatar()."','".$p->valorReal->formatar()."','".$p->valorVenda->formatar()."','".$p->estoque."','".addslashes($p->descricaoPequena)."','".addslashes($p->descricao)."','".$disponivel."','".$promocao."','".$lancamento."','".$destaque."','".$p->getCor()->getId()."','".$p->getPedra()->getId()."','".$p->getTamanho()->getId()."','".$p->ordem."','".$p->tipoUnidade."','".$p->quantidadeu."','".$p->getDataCadastro()->mostrar("Ymd")."','".$p->getVideo()."','".$p->getMarca()->getId()."','".$p->codigo."','".$p->frete."','".$p->tipoPedido."','".$p->quantidade."','".$p->getValorFrete()->formatar()."','".$p->observacao."')");
		
		$this->con->executar("INSERT INTO ".Sistema::$BDPrefixo.$this->tabela."(".self::ID.", ".self::IDSESSAO.", ".self::QUANTIDADE.", ".self::VALORFRETE.", ".self::OBSERVACAO.") VALUES('".$p->getId()."','".$pe->getId()."','".$p->quantidade."','".$p->getValorFrete()->formatar()."','".$p->observacao."')");
		
		$this->alterar($p, $pe);
		
	}
	
	public function alterar(PedidoItem $p, Pedido $pe){
		
		//parent::alterar($p);
		
		$where = "WHERE ".self::ID." = '".$p->getId()."' AND ".self::IDSESSAO." = '".$pe->getId()."'";
		
		$disponivel 	= $p->disponivel 	? self::VALOR_DISPONIVEL_TRUE : self::VALOR_DISPONIVEL_FALSE;
		$promocao 		= $p->promocao 		? self::VALOR_DISPONIVEL_TRUE : self::VALOR_DISPONIVEL_FALSE;
		$lancamento 	= $p->lancamento 	? self::VALOR_DISPONIVEL_TRUE : self::VALOR_DISPONIVEL_FALSE;
		$destaque 		= $p->destaque 		? self::VALOR_DISPONIVEL_TRUE : self::VALOR_DISPONIVEL_FALSE;
		$removido 		= $p->removido 		? self::VALOR_DISPONIVEL_TRUE : self::VALOR_DISPONIVEL_FALSE;

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
		
		$this->con->alterar(Sistema::$BDPrefixo.$this->tabela, self::QUANTIDADE, 		$p->quantidade, $where);
		$this->con->alterar(Sistema::$BDPrefixo.$this->tabela, self::VALORFRETE, 		$p->getValorFrete()->formatar(), $where);
		$this->con->alterar(Sistema::$BDPrefixo.$this->tabela, self::OBSERVACAO, 		$p->observacao, $where);
		
	}
	
	public function deletar(PedidoItem $p, Pedido $pe){
		
		$where = "WHERE ".self::ID." = '".$p->getId()."' AND ".self::IDSESSAO." = '".$pe->getId()."'";
		
		$this->con->deletar(Sistema::$BDPrefixo.$this->tabela, $where);
		
	}
	
}

?>