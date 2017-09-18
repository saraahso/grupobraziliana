<?php

importar("Geral.Lista.ListaImagens");
importar("LojaVirtual.Produtos.Lista.ListaProdutos");
importar("LojaVirtual.Produtos.Lista.ListaProdutoMarcas");
importar("LojaVirtual.Produtos.Lista.ListaProdutoCores");
importar("LojaVirtual.Produtos.Lista.ListaProdutoTamanhos");
importar("LojaVirtual.Produtos.Lista.ListaProdutoPedras");
importar("Utils.EnvioEmail");

$tituloPagina = 'Produtos > Alterar';

$iAP = new IFAdmin(new Arquivos(Sistema::$adminLayoutCaminhoDiretorio."/SistemaProdutos/produto.html"));

$lI = new ListaIdiomas;

$lP = new ListaProdutos;
$p = $lP->condicoes('', $_GET['produto'], ListaProdutos::ID)->disableDadosProdutoPai()->listar();
$lP->condicoes('', $p->getProdutoPai(), ListaProdutos::ID);
if($lP->getTotal() > 0)
	$produtoPai = $lP->listar();
else
	$produtoPai = new Produto;
	
if(!empty($_POST)){
	
	$erro = '';	
	
	if(empty($_POST['codigo']))
	    $erro = "<b>Código</b> não preenchido!<br><br>";

	if(empty($erro)){
		
		$estoque = 0;
		
		$lP = new ListaProdutos;
		$lP->condicoes('', $_GET['produto'], ListaProdutos::ID);
		$p = $lP->listar();
		
		$p->nome 				= $_POST['nome'];		
		$p->ordem				= $_POST['ordem'];	
		$p->codigo				= $_POST['codigo'];
		$p->peso				= $_POST['peso'];
		$p->largura				= $_POST['largura'];
		$p->altura				= $_POST['altura'];
		$p->comprimento			= $_POST['comprimento'];
		$p->valorCusto			= $_POST['valorCusto'];
		$p->valorReal			= $_POST['valorReal'];
		$p->valorVenda			= $_POST['valorVenda'];
		$p->tipoUnidade			= $_POST['tipoUnidade'];
		$p->quantidadeu			= $_POST['quantidadeu'];
		$p->estoque				= $_POST['estoque'];
		$estoque				= $p->estoque;
		$p->tipoPedido			= $_POST['tipoPedido'];
		$p->palavrasChaves			= $_POST['palavrasChaves'];
		$p->manual				= $_POST['manual'];
		
		$p->descricaoPequena	= $_POST['descricaoPequena'];
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
		
		$p->getURL()->setURL($_POST['url'] ? $_POST['url'] : $p->getId().$p->codigo."-".URL::cleanURL($_POST['nome'] ? $_POST['nome'] : $produtoPai->nome));
		
		$lP->alterar($p);
		
		if(count($_POST['opcao']) > 0){
			
			$con = BDConexao::__Abrir();
			$con->deletar(Sistema::$BDPrefixo."produtos_opcoes_gerados", "WHERE produto = '".$p->getId()."'");
			
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
			
			$t = $i->getTraducaoById(ListaProdutos::NOME, $lP->getTabela(), $p->getId());
			
			if($t->getId()){
				
				$t->conteudo = $p->nome;
				$t->traducao = $_POST['inome'][$i->getId()];
				$i->getTraducoes()->alterar($t);
			
			}else{
				
				$t = new Traducao;
				
				$t->conteudo = $p->nome;
				$t->traducao = $_POST['inome'][$i->getId()];
				$t->setIdConteudo($p->getId());
				$t->setCampoConteudo(ListaProdutos::NOME);
				$t->setTabelaConteudo($lP->getTabela());
				$i->addTraducao($t);
				
			}
			
			$t = $i->getTraducaoById(ListaProdutos::DESCRICAOPEQUENA, $lP->getTabela(), $p->getId());
			
			if($t->getId()){
				
				$t->conteudo = $p->descricaoPequena;
				$t->traducao = $_POST['idescricaoPequena'][$i->getId()];
				$i->getTraducoes()->alterar($t);
			
			}else{
				
				$t = new Traducao;
				
				$t->conteudo = $p->descricaoPequena;
				$t->traducao = $_POST['idescricaoPequena'][$i->getId()];
				$t->setIdConteudo($p->getId());
				$t->setCampoConteudo(ListaProdutos::DESCRICAOPEQUENA);
				$t->setTabelaConteudo($lP->getTabela());
				$i->addTraducao($t);
				
			}
			
			$t = $i->getTraducaoById(ListaProdutos::DESCRICAO, $lP->getTabela(), $p->getId());
			
			if($t->getId()){
				
				$t->conteudo = $p->descricao;
				$t->traducao = $_POST['idescricao'][$i->getId()];
				$i->getTraducoes()->alterar($t);
			
			}else{
				
				$t = new Traducao;
				
				$t->conteudo = $p->descricao;
				$t->traducao = $_POST['idescricao'][$i->getId()];
				$t->setIdConteudo($p->getId());
				$t->setCampoConteudo(ListaProdutos::DESCRICAO);
				$t->setTabelaConteudo($lP->getTabela());
				$i->addTraducao($t);
				
			}
			
			$t = $i->getTraducaoById(ListaProdutos::TIPOUNIDADE, $lP->getTabela(), $p->getId());
			
			if($t->getId()){
				
				$t->conteudo = $p->tipoUnidade;
				$t->traducao = $_POST['itipoUnidade'][$i->getId()];
				$i->getTraducoes()->alterar($t);
			
			}else{
				
				$t = new Traducao;
				
				$t->conteudo = $p->tipoUnidade;
				$t->traducao = $_POST['itipoUnidade'][$i->getId()];
				$t->setIdConteudo($p->getId());
				$t->setCampoConteudo(ListaProdutos::TIPOUNIDADE);
				$t->setTabelaConteudo($lP->getTabela());
				$i->addTraducao($t);
				
			}
			
		}
		
		$con = BDConexao::__Abrir();
		$con->deletar(Sistema::$BDPrefixo."relacionamento_produtos_categorias", "WHERE produto = '".$p->getId()."'");
		
		$lPC = new ListaProdutoCategorias;
		
		if(!empty($_POST['categoriasSelecionadas'])){
		
			foreach($_POST['categoriasSelecionadas'] as $valor){
				
				$lPC->condicoes('', $valor, ListaProdutoCategorias::ID);
				
				if($lPC->getTotal() > 0){
					
					$pC = $lPC->listar();
					
					$p->addCategoria($pC);
					
				}
				
			}
		
		}
		
		
		if(!empty($_POST['remover'])){
				
			$lI 				= new ListaImagens;
			$lI->caminhoEscrita	= Sistema::$caminhoDiretorio.Sistema::$caminhoDataProdutos;
			$lI->caminhoLeitura	= $lI->caminhoEscrita;
			
			foreach($_POST['remover'] as $id){
				
				$lI->condicoes('', $id, ListaImagens::ID);
				
				if($lI->getTotal() > 0){
					
					$img = $lI->listar();
					$lI->deletar($img);
					
				}
				
			}
			
		}
		
		if(!empty($_POST['legenda'])){
				
			$lI 				= new ListaImagens;
			$lI->caminhoEscrita	= Sistema::$caminhoDiretorio.Sistema::$caminhoDataProdutos;
			$lI->caminhoLeitura	= $lI->caminhoEscrita;
			
			foreach($_POST['legenda'] as $k => $v){
				
				$lI->condicoes('', $k, ListaImagens::ID);
				
				if($lI->getTotal() > 0){
					
					$img = $lI->listar();
					$img->legenda = $v;
					$lI->alterar($img);
					
				}
				
			}
			
		}
		
		if(count($_POST['estoqueInfo']) > 0){
			$con = BDConexao::__Abrir();
			$lPOV = new ListaProdutoOpcaoValores;
			for($i = 1; $i <= count($_POST['estoqueInfo']); $i++){
				
				$definido = false;
				foreach($_POST['opcaoInfo'] as $k => $v)
					if(!empty($v))
						$definido = true;
				
				if($definido || !empty($_POST['idInfo'][$i])){
					
					$lP->condicoes('', $_POST['idInfo'][$i], ListaProdutos::ID)->disableDadosProdutoPai();
					if($lP->getTotal() > 0)
						$pI = $lP->listar();
					else{
						$pI = new Produto;
						$pI->disponivel = true;
					}
					$pI->setProdutoPai($p, false);
					
					$pI->valorReal = $_POST['valorInfo'][$i];					
					$pI->estoque = $_POST['estoqueInfo'][$i];
					
					if($pI->getId() != '')
						$lP->alterar($pI);
					else{
						$pI->codigo = $p->codigo;
						$lP->inserir($pI);
						$pI->getURL()->setURL(addslashes($_POST['url'] ? $_POST['url'] : $pI->getId()."-".$pI->codigo.($p->nome ? "-".Strings::__RemoveAcentos(str_replace(" ", "-", $p->nome)) : "-".Strings::__RemoveAcentos(str_replace(" ", "-", $produtoPai->nome)))));
					}
					
					$con->deletar(Sistema::$BDPrefixo."produtos_opcoes_gerados", "WHERE produto = '".$pI->getId()."'");						
					foreach($_POST['opcaoInfo'][$i] as $k => $v){
						
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
							
							$pI->addOpcao(new ProdutoOpcao($k), $pOV);
						
						}
						
					}
					
				}
				
			}
			
		}
		
		while($pI = $p->getInfos()->listar()){
			$estoque += (int)$pI->estoque;
		}
		
		if($estoque > 0 && $p->getEncomendas()->getTotal() > 0){
		
			$temE = new InterFaces(new Arquivos(Sistema::$adminLayoutCaminhoDiretorio."/email-padrao.html"));
			$temEE = new InterFaces(new Arquivos(Sistema::$adminLayoutCaminhoDiretorio."/SistemaProdutos/email-encomenda.html"));
			
			if($p->getImagens()->getTotal() > 0){
				$img = $p->getImagens()->listar();
				if($img->getImage()->nome != '')
					$temEE->trocar('imagem', $img->getImage()->showHTML(170, 170));
				$p->getImagens()->setParametros(0);
			}
			$temEE->trocar('nome', $p->nome);
			$temEE->trocar('codigo', $p->codigo);
			if($p->getMarca())
				$temEE->trocar('nome.Marca', $p->getMarca()->nome);
			
			if($p->getCategorias()->getTotal() > 0)
				$temEE->trocar('linkVisualizar', Sistema::$caminhoURL."br/produtos/".$p->getCategorias()->listar()->getURL()->url."/".$p->getURL()->url);
			else
				$temEE->trocar('linkVisualizar', Sistema::$caminhoURL."br/produtos/".$p->getMarca()->getURL()->url."/".$p->getURL()->url);
			
			$temE->trocar('texto', $temEE->concluir());
			$msg = $temE->concluir();
			
			EnvioEmail::$de = Sistema::$nomeEmpresa."<no-reply@".Sistema::$dominioEmpresa.">";
			EnvioEmail::$assunto = 'Pedido de orçamento';
			EnvioEmail::$html = true;
			EnvioEmail::$msg = $msg;
			
			while($rs = $p->getEncomendas()->listar()){
				EnvioEmail::$para = $rs['email'];
				EnvioEmail::enviar();
			}
			
			$con = BDConexao::__Abrir();
			$con->executar("DELETE FROM ".Sistema::$BDPrefixo."produtos_encomenda WHERE idproduto = '".$p->getId()."'");	
		
		}
		
	    $javaScript .= Aviso::criar("Produto salvo com sucesso!");
	
	}else{
		
		$javaScript .= Aviso::criar($erro);
		
	}
	
}

$p = $lP->condicoes('', $_GET['produto'], ListaProdutos::ID)->disableDadosProdutoPai()->listar();

if(isset($_GET['uploadFlash'])){
	
	$con = BDConexao::__Abrir();
	$con->executar("SELECT * FROM ".Sistema::$BDPrefixo."marcadagua");
	$rs = $con->getRegistro();
	
	$img = new Imagem;
	$img->setSessao($lP->getTabela(), $p->getId());
	$arq = Arquivos::__OpenArquivoByTEMP($_FILES['Filedata']);
	//header("Content-type: image/jpeg");
	$img->setImage(new Image($arq));
	$img->getImage()->open();
	//echo $img->getImage()->getImage();exit;
	
	if($rs['produtos']){
	
		if($rs['tipo'] == 1){
			
			if(!empty($rs['texto'])){
				
				$ma = new NewImage(strlen($rs['texto'])*9, 20);
				$ma->writeText($rs['texto']);	
			}
			
		}elseif($rs['tipo'] == 2){
			if(!empty($rs['imagem'])){
				
				$ma = new Image(new Arquivos(Sistema::$caminhoURL.Sistema::$caminhoDataIdiomas.$rs['imagem']));		
				$ma->open();
				//echo $ma->getImage();exit;
			}
		}
		
		if($ma){
			
			if($rs['posicaohorizontal'] == 1){
				$x = 0;
			}elseif($rs['posicaohorizontal'] == 2){
				$x = ($img->getImage()->largura/2)-($ma->largura/2);
			}elseif($rs['posicaohorizontal'] == 3){
				$x = $img->getImage()->largura-$ma->largura;
			}
			
			if($rs['posicaovertical'] == 1){
				$y = 0;
			}elseif($rs['posicaovertical'] == 2){
				$y = ($img->getImage()->altura/2)-($ma->altura/2);
			}elseif($rs['posicaovertical'] == 3){
				$y = $img->getImage()->altura-$ma->altura;
			}
			
			$img->getImage()->combineImage($ma, 0, 0, $x, $y, 50);
			
		}
	
	}
	
	$lIM = new ListaImagens;
	$lIM->caminhoEscrita = Sistema::$caminhoDiretorio.Sistema::$caminhoDataProdutos;
	$lIM->caminhoLeitura = Sistema::$caminhoURL.Sistema::$caminhoDataProdutos;
	$img->getImage()->redimensionar(1500, 1500);
	$lIM->inserir($img);	
	exit;
	
}


$iAP->condicao("condicaoBotoes", true);
$iAP->trocar("linkCadastrarVariacao", "?p=".$_GET['p']."&a=cadastrarProduto&produtoPai=".$p->getId());
$botoes = $iAP->cutParte('botoes');

$iAP->condicao('condicao->alterar.Produto', false);

$iAP->trocar("linkVoltar", "?p=".$_GET['p']."&a=listarProdutos");

$iAP->condicao("condicao->ProdutoPai",	$produtoPai->getId() != 0);
$iAP->trocar("nome.ProdutoPai", $produtoPai->nome);
$iAP->trocar("linkAlterar.ProdutoPai", "?p=".$_GET['p']."&a=".$_GET['a']."&produto=".$produtoPai->getId());
$iAP->trocar("nome", 			$p->nome);

$lPM = new ListaProdutoMarcas;
$iAP->createRepeticao("repetir->ProdutoMarcas");
while($pM = $lPM->listar("ASC", ListaProdutoMarcas::NOME)){
	
	$iAP->repetir();
	$iAP->enterRepeticao()->trocar('id.ProdutoMarca', $pM->getId());
	$iAP->enterRepeticao()->trocar('nome.ProdutoMarca', $pM->nome);
	
}

$lPO = new ListaProdutoOpcoes;
$iAP->createRepeticao("repetir->ProdutoOpcoes");
$iAP->createRepeticao("repetir->ProdutoOpcoes.JavaScript");
while($pO = $lPO->listar("ASC", ListaProdutoOpcoes::NOME)){
	
	if($lPO->getParametros()%2 == 0)
		$bgcolor = '#E5E5E5';
	else
		$bgcolor = '#FFF';
		
	$iAP->repetir("repetir->ProdutoOpcoes");
	$iAP->repetir("repetir->ProdutoOpcoes.JavaScript");
	
	$iAP->enterRepeticao("repetir->ProdutoOpcoes")->condicao('condicao->Multi.ProdutoOpcao', $pO->multi);
	$iAP->enterRepeticao("repetir->ProdutoOpcoes.JavaScript")->condicao('condicao->Multi.ProdutoOpcao', $pO->multi);
	
	$iAP->enterRepeticao("repetir->ProdutoOpcoes")->trocar('bgcolor.ProdutoOpcao', $bgcolor);
	$iAP->enterRepeticao("repetir->ProdutoOpcoes")->trocar('id.ProdutoOpcao', $pO->getId());
	$iAP->enterRepeticao("repetir->ProdutoOpcoes")->trocar('nome.ProdutoOpcao', $pO->nome);
	
	$iAP->enterRepeticao("repetir->ProdutoOpcoes.JavaScript")->trocar('id.ProdutoOpcao', $pO->getId());
	$iAP->enterRepeticao("repetir->ProdutoOpcoes.JavaScript")->trocar('nome.ProdutoOpcao', addslashes($pO->nome));
	
	if($pO->multi){
		
		$iAP->enterRepeticao("repetir->ProdutoOpcoes")->createRepeticao('repetir->ProdutoOpcaoValores.ProdutoOpcao');
		$iAP->enterRepeticao("repetir->ProdutoOpcoes.JavaScript")->createRepeticao('repetir->ProdutoOpcaoValores.ProdutoOpcao');
		while($pOV = $pO->getValores()->listar('ASC', ListaProdutoOpcaoValores::VALOR)){
			
			$iAP->enterRepeticao("repetir->ProdutoOpcoes")->repetir();
			$iAP->enterRepeticao("repetir->ProdutoOpcoes")->enterRepeticao()->trocar('id.ProdutoOpcaoValor.ProdutoOpcao', $pOV->getId());
			$iAP->enterRepeticao("repetir->ProdutoOpcoes")->enterRepeticao()->trocar('valor.ProdutoOpcaoValor.ProdutoOpcao', $pOV->valor);
			
			$iAP->enterRepeticao("repetir->ProdutoOpcoes.JavaScript")->repetir();
			$iAP->enterRepeticao("repetir->ProdutoOpcoes.JavaScript")->enterRepeticao()->trocar('id.ProdutoOpcaoValor.ProdutoOpcao', $pOV->getId());
			$iAP->enterRepeticao("repetir->ProdutoOpcoes.JavaScript")->enterRepeticao()->trocar('valor.ProdutoOpcaoValor.ProdutoOpcao', addslashes($pOV->valor));
		
		}
		
	}
	
	$opcao = '';
	$p->getOpcoes()->setParametros(0);
	while($pOG = $p->getOpcoes()->listar()){
		if($pOG->getOpcao()->getId() == $pO->getId())
			$opcao = $pOG->getValor()->getId() != '' ? $pOG->getValor()->getId() : $pOG->getValor()->valor;
	}
	
	$iAP->enterRepeticao("repetir->ProdutoOpcoes")->trocar('opcao'.$pO->getId().'.ProdutoOpcao', $opcao);
	
}

$iAP->trocar("marca", 				$p->getMarca()->getId());
$iAP->trocar("url", 				$p->getURL()->getURL());
$iAP->trocar("ordem", 				$p->ordem);
$iAP->trocar("codigo", 				$p->codigo);
$iAP->trocar("peso", 				$p->peso->formatar(",", "", 3));
$iAP->trocar("altura", 				$p->altura->moeda());
$iAP->trocar("largura", 			$p->largura->moeda());
$iAP->trocar("comprimento",			$p->comprimento->moeda());
$iAP->trocar("valorCusto", 			$p->valorCusto->moeda());
$iAP->trocar("valorReal", 			$p->valorReal->moeda());
$iAP->trocar("valorVenda", 			$p->valorVenda->moeda());
$iAP->trocar("tipoUnidade",			$p->tipoUnidade);
$iAP->trocar("palavrasChaves",			$p->palavrasChaves);
$iAP->trocar("manual",				$p->manual);
$iAP->trocar("quantidadeu", 		$p->quantidadeu);
$iAP->trocar("estoque", 			$p->estoque);
$iAP->trocar("descricaoPequena", 	$p->descricaoPequena);
$iAP->trocar("descricao", 			$p->descricao);

$iAP->trocar("video", 				$p->getVideo());
	
$iAP->trocar("disponivel", 			$p->disponivel 	? 1 : 0);
$iAP->trocar("lancamento", 			$p->lancamento 	? 1 : 0);
$iAP->trocar("destaque", 			$p->destaque 	? 1 : 0);
$iAP->trocar("promocao", 			$p->promocao 	? 1 : 0);

$iAP->trocar("tipoPedido", 			$p->tipoPedido);
$iAP->trocar("frete", 				$p->frete);

$iAP->trocar('uploadCaminhoURL', Sistema::$adminCaminhoURL."?p=".$_GET['p']."&a=".$_GET['a']."&produto=".$p->getId()."&uploadFlash");

$iAP->createRepeticao("repetir->ProdutoCategorias.Produto");
while($pC = $p->getCategorias()->listar()){
	
	$iAP->repetir();
	$iAP->enterRepeticao()->trocar('id.ProdutoCategoria.Produto', $pC->getId());
	$iAP->enterRepeticao()->trocar('navegador.ProdutoCategoria.Produto', $pC->getNavegador());
	
}

$lI 	= new ListaIdiomas;
$sub 	= "repetir->nome.Produtos.Idiomas";
$sub4 	= "repetir->descricaoPequena.Produtos.Idiomas";
$sub2 	= "repetir->descricao.Produtos.Idiomas";
$sub3 	= "repetir->tipoUnidade.Produtos.Idiomas";
$iAP->createRepeticao($sub);
$iAP->createRepeticao($sub4);
$iAP->createRepeticao($sub2);
$iAP->createRepeticao($sub3);
while($i = $lI->listar()){
	
	$iAP->repetir($sub);
	$iAP->repetir($sub4);
	$iAP->repetir($sub2);
	$iAP->repetir($sub3);
	
	$iAP->enterRepeticao($sub)->trocar("nome.Idioma", $i->nome);
	$iAP->enterRepeticao($sub)->trocar("id.Idioma", $i->getId());
	$iAP->enterRepeticao($sub)->trocar("nome.Produto.Idioma", $i->getTraducaoById(ListaProdutos::NOME, $lP->getTabela(), $p->getId())->traducao);
	
	$iAP->enterRepeticao($sub4)->trocar("nome.Idioma", $i->nome);
	$iAP->enterRepeticao($sub4)->trocar("id.Idioma", $i->getId());
	$iAP->enterRepeticao($sub4)->trocar("descricaoPequena.Produto.Idioma", $i->getTraducaoById(ListaProdutos::DESCRICAOPEQUENA, $lP->getTabela(), $p->getId())->traducao);
	
	$iAP->enterRepeticao($sub2)->trocar("nome.Idioma", $i->nome);
	$iAP->enterRepeticao($sub2)->trocar("id.Idioma", $i->getId());
	$iAP->enterRepeticao($sub2)->trocar("descricao.Produto.Idioma", $i->getTraducaoById(ListaProdutos::DESCRICAO, $lP->getTabela(), $p->getId())->traducao);
	
	$iAP->enterRepeticao($sub3)->trocar("nome.Idioma", $i->nome);
	$iAP->enterRepeticao($sub3)->trocar("id.Idioma", $i->getId());
	$iAP->enterRepeticao($sub3)->trocar("tipoUnidade.Produto.Idioma", $i->getTraducaoById(ListaProdutos::TIPOUNIDADE, $lP->getTabela(), $p->getId())->traducao);
	
}

$iAP->trocar("linkDeletar.Imagem.Produto", "?p=".$_GET['p']."&a=".$_GET['a']."&produto=".$p->getId()."&deletar&");
$iAP->trocar("linkPrincipal.Imagem.Produto", "?p=".$_GET['p']."&a=".$_GET['a']."&produto=".$p->getId()."&principal&imagem={id.Imagem.Produto}");

if(isset($_GET['deletar']) && !empty($_GET['imagem'])){
	
	$lI 				= new ListaImagens;
	$lI->caminhoEscrita	= Sistema::$caminhoDiretorio.Sistema::$caminhoDataProdutos;
	$lI->caminhoLeitura	= $lI->caminhoEscrita;
	$lI->condicoes('', $_GET['imagem'], ListaImagens::ID);
	
	if($lI->getTotal() > 0){
		
		$lI->deletar($lI->listar());
		
		$javaScript .= Aviso::criar("Imagem removida com sucesso!");
		
	}
	
}elseif(isset($_GET['principal']) && !empty($_GET['imagem'])){
	
	$lI 				= new ListaImagens;
	$lI->caminhoEscrita	= Sistema::$caminhoDataProdutos;
	$lI->caminhoLeitura	= $lI->caminhoEscrita;
	$lI->condicoes('', $_GET['imagem'], ListaImagens::ID);
	
	while($img = $p->getImagens()->listar()){
		
		$img->destaque = false;
		$lI->alterar($img);
		
	}
	
	$p->getImagens()->setParametros(0);
	
	if($lI->getTotal() > 0){
		
		$img 			= $lI->listar();
		$img->destaque 	= true;
		
		$lI->alterar($img);
		
		$javaScript .= Aviso::criar("Imagem salva com sucesso!");
		
	}
		
}

$iAP->createRepeticao("repetir->Imagens.Produto");
$p->getImagens()->setParametros(9999999, 'limite');
while($img = $p->getImagens()->listar("DESC", ListaImagens::DESTAQUE)){
	
	$iAP->repetir();
	if($img->getImage()->nome != "")
	$iAP->enterRepeticao()->trocar("imagem.Imagem.Produto", $img->getImage()->showHTML(150, 150));
	$iAP->enterRepeticao()->trocar("id.Imagem.Produto", $img->getId());
	$iAP->enterRepeticao()->trocar("legenda.Imagem.Produto", $img->legenda);
	
	$iAP->enterRepeticao()->condicao('condicao->principal.Imagem.Produto', !$img->destaque);
	
}

$iAP->createRepeticao("repetir->ProdutoInfos");
while($pI = $p->getInfos()->disableDadosProdutoPai()->listar()){
	
	$iAP->repetir();
	$iAP->enterRepeticao()->trocar("id.ProdutoInfo", $pI->getId());
	$iAP->enterRepeticao()->trocar("posicao.ProdutoInfo", $p->getInfos()->getParametros());
	$iAP->enterRepeticao()->trocar("estoque.ProdutoInfo", $pI->estoque);
	$iAP->enterRepeticao()->trocar("valor.ProdutoInfo", $pI->valorReal > 0 ? $pI->valorReal->moeda() : '');
	
	$iAP->enterRepeticao()->trocar("linkAlterar.ProdutoInfo", "?p=".$_GET['p']."&a=".$_GET['a']."&produto=".$pI->getId());
	
	
	$iAP->enterRepeticao()->createRepeticao("repetir->ProdutoOpcoes.ProdutoInfo");
	while($pOG = $pI->getOpcoes()->listar()){
		
		$iAP->enterRepeticao()->repetir();
		
		$iAP->enterRepeticao()->enterRepeticao()->trocar('id.ProdutoOpcao.ProdutoInfo', $pOG->getOpcao()->getId());
		$iAP->enterRepeticao()->enterRepeticao()->trocar('nome.ProdutoOpcao.ProdutoInfo', $pOG->getOpcao()->nome);
		$iAP->enterRepeticao()->enterRepeticao()->trocar('id.ProdutoOpcaoValor.ProdutoInfo', $pOG->getValor()->getId() != '' ? $pOG->getValor()->getId() : $pOG->getValor()->valor);
		$iAP->enterRepeticao()->enterRepeticao()->trocar('valor.ProdutoOpcaoValor.ProdutoInfo', $pOG->getValor()->valor);
		
	}
	
}

$iAP->createJavaScript();
$javaScript .= $iAP->javaScript->concluir();

$includePagina = $iAP->concluir();

?>