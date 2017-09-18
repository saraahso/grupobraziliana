<?php

importar("LojaVirtual.Produtos.Lista.ListaProdutos");
importar("LojaVirtual.Produtos.Opcoes.Lista.ListaProdutoOpcoes");
importar("LojaVirtual.Produtos.Lista.ListaProdutoMarcas");
importar("Utils.Dados.Strings");

$tituloPagina = 'Produtos > Cadastrar';

$iCP = new IFAdmin(new Arquivos(Sistema::$adminLayoutCaminhoDiretorio."/SistemaProdutos/produto.html"));

$lI 	= new ListaIdiomas;
$lP		= new ListaProdutos;
$lP->condicoes("", $_GET['produtoPai'], ListaProdutos::ID);
if($lP->getTotal() > 0)
	$produtoPai = $lP->listar();
else
	$produtoPai = new Produto;

if(!empty($_POST)){
	
	$erro = '';
		
	if(empty($_POST['codigo']))
	    $erro = "<b>Código</b> não preenchido!<br><br>";

	if(empty($erro)){
		
		try{
		
	    $p 						= new Produto;
		
		$p->setProdutoPai($produtoPai, false);
		
		$p->nome 				= $_POST['nome'];		
		$p->ordem				= $_POST['ordem'];	
		$p->codigo				= $_POST['codigo'];
		$p->peso				= $_POST['peso'];
		$p->largura				= $_POST['largura'];
		$p->altura				= $_POST['altura'];
		$p->comprimento			= $_POST['comprimento'];
		$p->valorCusto			= str_replace('.', '', $_POST['valorCusto']);
		$p->valorReal			= str_replace('.', '', $_POST['valorReal']);
		$p->valorVenda			= str_replace('.', '', $_POST['valorVenda']);
		$p->tipoUnidade			= $_POST['tipoUnidade'];
		$p->quantidadeu			= $_POST['quantidadeu'];
		$p->estoque				= $_POST['estoque'];
		$p->tipoPedido			= $_POST['tipoPedido'];
		$p->palavrasChaves			= $_POST['palavrasChaves'];
		$p->manual				= $_POST['manual'];
		
		if(trim(strip_tags($_POST['descricaoPequena'])) != '')
			$p->descricaoPequena	= $_POST['descricaoPequena'];
		if(trim(strip_tags($_POST['descricao'])) != '')
			$p->descricao			= $_POST['descricao'];
		
		$p->frete				= $_POST['frete'];
		if(empty($_POST['frete']) && $p->getProdutoPai() == '')
			$p->frete			= Produto::FRETE_NORMAL;
				
		$lPM = new ListaProdutoMarcas;
		$lPM->condicoes('', $_POST['marca'], ListaProdutoMarcas::ID);
		if($lPM->getTotal() > 0)
			$p->setMarca($lPM->listar());
		
		$p->disponivel			= $_POST['disponivel'] 	== ListaProdutos::VALOR_DISPONIVEL_TRUE ? true : false;
		$p->lancamento			= $_POST['lancamento']	== ListaProdutos::VALOR_DISPONIVEL_TRUE ? true : false;
		$p->destaque			= $_POST['destaque'] 	== ListaProdutos::VALOR_DISPONIVEL_TRUE ? true : false;
		$p->promocao			= $_POST['promocao'] 	== ListaProdutos::VALOR_DISPONIVEL_TRUE ? true : false;
		
		$p->setVideo($_POST['video']);
		
		$lP->inserir($p);
		$p->getURL()->setURL($_POST['url'] ? $_POST['url'] : $p->getId().$p->codigo."-".URL::cleanURL($_POST['nome'] ? $_POST['nome'] : $produtoPai->nome));
		$lP->alterar($p);
		
		if(count($_POST['opcao']) > 0){
			
			$lPOV = new ListaProdutoOpcaoValores;
			foreach($_POST['opcao'] as $k => $v){
				
				if(!empty($v)){
				
					$aRPOV[1] = array('campo' => ListaProdutoOpcaoValores::OPCAO, 'valor' => $k);
					$aRPOV[2] = array('campo' => ListaProdutoOpcaoValores::ID, 'valor' => $v);
					$lPOV->resetCondicoes();
					$lPOV->condicoes($aRPOV);
					if($lPOV->getTotal() > 0)
						$pOV = $lPOV->listar();
					else{
						$pOV = new ProdutoOpcaoValor;
						$pOV->valor = $v;
					}
					
					$p->addOpcao(new ProdutoOpcao($k), $pOV);
				
				}
				
			}
			
		}
		
		while($i = $lI->listar()){
			
			$t = new Traducao;
			
			$t->setIdConteudo($p->getId());
			$t->setCampoConteudo(ListaProdutos::NOME);
			$t->setTabelaConteudo($lP->getTabela());
			$t->conteudo = $p->nome;
			$t->traducao = $_POST['inome'][$i->getId()];
			$i->addTraducao($t);
			
			$t->setCampoConteudo(ListaProdutos::DESCRICAOPEQUENA);
			if(trim(strip_tags($_POST['descricaoPequena'])) != '')
				$t->conteudo = $p->descricaoPequena;
			$t->traducao = $_POST['idescricaoPequena'][$i->getId()];
			$i->addTraducao($t);
			
			$t->setCampoConteudo(ListaProdutos::DESCRICAO);
			if(trim(strip_tags($_POST['descricao'])) != '')
				$t->conteudo = $p->descricao;
			$t->traducao = $_POST['idescricao'][$i->getId()];
			$i->addTraducao($t);
			
			$t->setCampoConteudo(ListaProdutos::TIPOUNIDADE);
			$t->conteudo = $p->tipoUnidade;
			$t->traducao = $_POST['itipoUnidade'][$i->getId()];
			$i->addTraducao($t);
			
		}
		
		$_POST = '';
		
		header("Location: ?p=SistemaProdutos&a=alterarProduto&produto=".$p->getId());
		
	    $javaScript .= Aviso::criar("Produto salvo com sucesso!");		
		
		}catch(Exception $e){
			
			$javaScript .= Aviso::criar($e->getMessage());
			
		}
	
	}else{
		
		$javaScript .= Aviso::criar($erro);
		
	}
	
}

$iCP->condicao('condicao->alterar.Produto', true);
$iCP->condicao("condicaoBotoes", false);
$botoes = $iCP->cutParte('botoes');

$iCP->trocar("linkVoltar", "?p=".$_GET['p']."&a=listarProdutos");

$iCP->condicao("condicao->ProdutoPai",	$produtoPai->getId() != 0);
$iCP->trocar("nome.ProdutoPai", $produtoPai->nome);

$iCP->trocar("nome", 			$_POST['nome']);
$iCP->trocar("codigo", 			$_POST['codigo'] ? $_POST['codigo'] : $produtoPai->codigo);

$lPM = new ListaProdutoMarcas;
$iCP->createRepeticao("repetir->ProdutoMarcas");
while($pM = $lPM->listar("ASC", ListaProdutoMarcas::NOME)){
	
	$iCP->repetir();
	$iCP->enterRepeticao()->trocar('id.ProdutoMarca', $pM->getId());
	$iCP->enterRepeticao()->trocar('nome.ProdutoMarca', $pM->nome);
	
}

$lPO = new ListaProdutoOpcoes;
$iCP->createRepeticao("repetir->ProdutoOpcoes");
while($pO = $lPO->listar("ASC", ListaProdutoOpcoes::NOME)){
	
	if($lPO->getParametros()%2 == 0)
		$bgcolor = '#E5E5E5';
	else
		$bgcolor = '#FFF';
		
	$iCP->repetir();
	
	$iCP->enterRepeticao()->condicao('condicao->Multi.ProdutoOpcao', $pO->multi);
	
	$iCP->enterRepeticao()->trocar('bgcolor.ProdutoOpcao', $bgcolor);
	$iCP->enterRepeticao()->trocar('id.ProdutoOpcao', $pO->getId());
	$iCP->enterRepeticao()->trocar('nome.ProdutoOpcao', $pO->nome);
	
	if($pO->multi){
		
		$iCP->enterRepeticao()->createRepeticao('repetir->ProdutoOpcaoValores.ProdutoOpcao');
		while($pOV = $pO->getValores()->listar('ASC', ListaProdutoOpcaoValores::VALOR)){
			
			$iCP->enterRepeticao()->repetir();
			$iCP->enterRepeticao()->enterRepeticao()->trocar('id.ProdutoOpcaoValor.ProdutoOpcao', $pOV->getId());
			$iCP->enterRepeticao()->enterRepeticao()->trocar('valor.ProdutoOpcaoValor.ProdutoOpcao', $pOV->valor);
		
		}
		
	}
	
	$iCP->enterRepeticao()->trocar('opcao'.$pO->getId().'.ProdutoOpcao', $_POST['opcao'.$pO->getId().'.ProdutoOpcao']);
	
}

$iCP->trocar("marca", 				$_POST['marca']);
$iCP->trocar("url", 				$_POST['url']);
$iCP->trocar("ordem", 				$_POST['ordem']);
$iCP->trocar("peso", 				$_POST['peso']);
$iCP->trocar("altura", 				$_POST['altura']);
$iCP->trocar("largura",				$_POST['largura']);
$iCP->trocar("comprimento",			$_POST['comprimento']);
$iCP->trocar("valorCusto", 			$_POST['valorCusto']);
$iCP->trocar("valorReal", 			$_POST['valorReal']);
$iCP->trocar("valorVenda", 			$_POST['valorVenda']);
$iCP->trocar("tipoUnidade",			$_POST['tipoUnidade']);
$iCP->trocar("palavrasChaves",			$_POST['palavrasChaves']);
$iCP->trocar("manual",				$_POST['manual']);
$iCP->trocar("quantidadeu", 		$_POST['quantidadeu']);
$iCP->trocar("descricaoPequena",	$_POST['descricaoPequena']);
$iCP->trocar("descricao", 			$_POST['descricao']);

$iCP->trocar("video", 				$_POST['video']);

$iCP->trocar("disponivel", 			$_POST['disponivel']);
$iCP->trocar("promocao", 			$_POST['promocao']);
$iCP->trocar("lancamento", 			$_POST['lancamento']);
$iCP->trocar("destaque", 			$_POST['destaque']);
$iCP->trocar("cor", 				$_POST['cor']);
$iCP->trocar("tamanho", 			$_POST['tamanho']);
$iCP->trocar("pedra", 				$_POST['pedra']);
$iCP->trocar("tipoPedido", 			$_POST['tipoPedido']);
$iCP->trocar("frete", 				$_POST['frete']);


$iCP->createRepeticao("repetir->ProdutoCategorias.Produto");
$iCP->createRepeticao("repetir->Imagens.Produto");

$sub 	= "repetir->nome.Produtos.Idiomas";
$sub4 	= "repetir->descricaoPequena.Produtos.Idiomas";
$sub2 	= "repetir->descricao.Produtos.Idiomas";
$sub3	= "repetir->tipoUnidade.Produtos.Idiomas";
$iCP->createRepeticao($sub);
$iCP->createRepeticao($sub4);
$iCP->createRepeticao($sub2);
$iCP->createRepeticao($sub3);
while($i = $lI->listar()){
	
	$iCP->repetir($sub);
	$iCP->repetir($sub4);
	$iCP->repetir($sub2);
	$iCP->repetir($sub3);
	
	$iCP->enterRepeticao($sub)->trocar("nome.Idioma", $i->nome);
	$iCP->enterRepeticao($sub)->trocar("id.Idioma", $i->getId());
	if(!empty($_POST)) 
		$iCP->enterRepeticao($sub)->trocar("nome.Produto.Idioma", $_POST['inome'][$i->getId()]);
	
	$iCP->enterRepeticao($sub4)->trocar("nome.Idioma", $i->nome);
	$iCP->enterRepeticao($sub4)->trocar("id.Idioma", $i->getId());
	if(!empty($_POST)) 
		$iCP->enterRepeticao($sub4)->trocar("descricaoPequena.Produto.Idioma", $_POST['idescricaoPequena'][$i->getId()]);
	
	$iCP->enterRepeticao($sub2)->trocar("nome.Idioma", $i->nome);
	$iCP->enterRepeticao($sub2)->trocar("id.Idioma", $i->getId());
	if(!empty($_POST)) 
		$iCP->enterRepeticao($sub2)->trocar("descricao.Produto.Idioma", $_POST['idescricao'][$i->getId()]);
	
	$iCP->enterRepeticao($sub3)->trocar("nome.Idioma", $i->nome);
	$iCP->enterRepeticao($sub3)->trocar("id.Idioma", $i->getId());
	if(!empty($_POST)) 
		$iCP->enterRepeticao($sub3)->trocar("tipoUnidade.Produto.Idioma", $_POST['itipoUnidade'][$i->getId()]);
	
}

$iCP->createJavaScript();
$javaScript .= $iCP->javaScript->concluir();

$includePagina = $iCP->concluir();

?>