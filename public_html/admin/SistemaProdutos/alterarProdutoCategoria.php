<?php

importar("LojaVirtual.Categorias.Lista.ListaProdutoCategorias");

$tituloPagina = 'Produtos > Categorias > Alterar';

$iAPC = new IFAdmin(new Arquivos(Sistema::$adminLayoutCaminhoDiretorio."/SistemaProdutos/produtoCategoria.html"));

$lI = new ListaIdiomas;

if(!empty($_POST)){
	
	$erro = '';	
	
    if(empty($_POST['nome']))
	    $erro = "<b>Nome</b> não preenchido!<br><br>";

	if(empty($erro)){
		
		$lCP = new ListaProdutoCategorias;
		$lCP->condicoes('', $_GET['categoria'], ListaProdutoCategorias::ID);
		$pC = $lCP->listar();
		
		$pC->nome 			= addslashes(str_replace("\"", "'", $_POST['nome']));
		$pC->ordem			= $_POST['ordem'];
		$pC->subreferencia	= $_POST['subreferencia'];
		$pC->cor			= $_POST['cor'];
		$pC->disponivel		= $_POST['disponivel'] 	== ListaProdutoCategorias::VALOR_DISPONIVEL_TRUE ? true : false;
		$pC->visaoUnica		= $_POST['visaoUnica'] 	== ListaProdutoCategorias::VALOR_VISAOUNICA_TRUE ? true : false;
		$pC->home			= $_POST['home'] 		== ListaProdutoCategorias::VALOR_HOME_TRUE ? true : false;
		
		if(!empty($_POST['url']))
			$pC->getURL()->setURL($_POST['url']);
		else
			$pC->getURL()->setURL(($cP->getId() > 0 ? URL::cleanURL($cP->getNavegador(new Templates(Arquivos::__Create("{nome}"))))."-" : '').URL::cleanURL($_POST['nome']));
		
		$pC->descricaoPequena	= eregi_replace('\.\./', Sistema::$caminhoURL, $_POST['descricaoPequena']);
		$pC->descricao	= eregi_replace('\.\./', Sistema::$caminhoURL, $_POST['descricao']);
		
		if(!empty($_FILES['imagem']['name']))
			$pC->setImagem(new Image(Arquivos::__OpenArquivoByTEMP($_FILES['imagem'])));
		
		$lCP->alterar($pC);
		
		while($i = $lI->listar()){
			
			$t = $i->getTraducaoById(ListaProdutoCategorias::NOME, $lCP->getTabela(), $pC->getId());
			
			if($t->getId()){
				
				$t->conteudo = $pC->nome;
				$t->traducao = $_POST['inome'][$i->getId()];
				$i->getTraducoes()->alterar($t);
			
			}else{
				
				$t = new Traducao;
				
				$t->conteudo = $pC->nome;
				$t->traducao = $_POST['inome'][$i->getId()];
				$t->setIdConteudo($pC->getId());
				$t->setCampoConteudo(ListaProdutoCategorias::NOME);
				$t->setTabelaConteudo($lCP->getTabela());
				$i->addTraducao($t);
				
			}
			
			$t = $i->getTraducaoById(ListaProdutoCategorias::DESCRICAOPEQUENA, $lCP->getTabela(), $pC->getId());
			
			if($t->getId()){
				
				$t->conteudo = $pC->descricaoPequena;
				$t->traducao = $_POST['idescricaoPequena'][$i->getId()];
				$i->getTraducoes()->alterar($t);
			
			}else{
				
				$t = new Traducao;
				
				$t->conteudo = $pC->descricaoPequena;
				$t->traducao = $_POST['idescricaoPequena'][$i->getId()];
				$t->setIdConteudo($pC->getId());
				$t->setCampoConteudo(ListaProdutoCategorias::DESCRICAOPEQUENA);
				$t->setTabelaConteudo($lCP->getTabela());
				$i->addTraducao($t);
				
			}
			
			$t = $i->getTraducaoById(ListaProdutoCategorias::DESCRICAO, $lCP->getTabela(), $pC->getId());
			
			if($t->getId()){
				
				$t->conteudo = $pC->descricao;
				$t->traducao = $_POST['idescricao'][$i->getId()];
				$i->getTraducoes()->alterar($t);
			
			}else{
				
				$t = new Traducao;
				
				$t->conteudo = $pC->descricao;
				$t->traducao = $_POST['idescricao'][$i->getId()];
				$t->setIdConteudo($pC->getId());
				$t->setCampoConteudo(ListaProdutoCategorias::DESCRICAO);
				$t->setTabelaConteudo($lCP->getTabela());
				$i->addTraducao($t);
				
			}
			
		}
		
		$lI->setParametros(0);
		
	    $javaScript .= Aviso::criar("Categoria salva com sucesso!");
	
	}else{
		
		$javaScript .= Aviso::criar($erro);
		
	}
	
}

$lPC = new ListaProdutoCategorias;
$pC = $lPC->condicoes('', $_GET['categoria'], ListaProdutoCategorias::ID)->listar();

$iAPC->trocar("nome", 				$pC->nome);
$iAPC->trocar("url", 				$pC->getURL()->getURL());
$iAPC->trocar("ordem", 				$pC->ordem);
$iAPC->trocar("subreferencia", 		$pC->subreferencia);
$iAPC->trocar("cor", 				$pC->cor);
$iAPC->trocar("disponivel", 		$pC->disponivel ? ListaProdutoCategorias::VALOR_DISPONIVEL_TRUE : ListaProdutoCategorias::VALOR_DISPONIVEL_FALSE);
$iAPC->trocar("visaoUnica", 		$pC->visaoUnica ? ListaProdutoCategorias::VALOR_VISAOUNICA_TRUE : ListaProdutoCategorias::VALOR_VISAOUNICA_FALSE);
$iAPC->trocar("home", 				$pC->home 		? ListaProdutoCategorias::VALOR_HOME_TRUE : ListaProdutoCategorias::VALOR_HOME_FALSE);
$iAPC->trocar("descricaoPequena", 	$pC->descricaoPequena);
$iAPC->trocar("descricao", 			$pC->descricao);

$sub 	= "repetir->nome.ProdutoCategorias.Idiomas";
$sub2 	= "repetir->descricao.ProdutoCategorias.Idiomas";
$sub3 	= "repetir->descricaoPequena.ProdutoCategorias.Idiomas";
$iAPC->createRepeticao($sub);
$iAPC->createRepeticao($sub2);
$iAPC->createRepeticao($sub3);
while($i = $lI->listar()){
	
	$iAPC->repetir($sub);
	$iAPC->repetir($sub2);
	$iAPC->repetir($sub3);
	
	$iAPC->enterRepeticao($sub)->trocar("nome.Idioma", $i->nome);
	$iAPC->enterRepeticao($sub)->trocar("id.Idioma", $i->getId());
	$iAPC->enterRepeticao($sub)->trocar("nome.ProdutoCategoria.Idioma", $i->getTraducaoById(ListaProdutoCategorias::NOME, $lPC->getTabela(), $pC->getId())->traducao);
	
	$iAPC->enterRepeticao($sub2)->trocar("nome.Idioma", $i->nome);
	$iAPC->enterRepeticao($sub2)->trocar("id.Idioma", $i->getId());
	$iAPC->enterRepeticao($sub2)->trocar("descricao.ProdutoCategoria.Idioma", $i->getTraducaoById(ListaProdutoCategorias::DESCRICAO, $lPC->getTabela(), $pC->getId())->traducao);
	
	$iAPC->enterRepeticao($sub3)->trocar("nome.Idioma", $i->nome);
	$iAPC->enterRepeticao($sub3)->trocar("id.Idioma", $i->getId());
	$iAPC->enterRepeticao($sub3)->trocar("descricaoPequena.ProdutoCategoria.Idioma", $i->getTraducaoById(ListaProdutoCategorias::DESCRICAOPEQUENA, $lPC->getTabela(), $pC->getId())->traducao);
	
}

$iAPC->trocar("linkVoltar", "?p=".$_GET['p']."&a=listarProdutoCategorias&categoria=".$pC->getIdCategoriaPai());

$iAPC->trocar("idContaPai", $pC->getIdCategoriaPai());
												   
if($pC->getImagem()->nome != '')
	$iAPC->trocar("imagem", $pC->getImagem()->showHTML(200, 200));

$iAPC->createJavaScript();
$javaScript .= $iAPC->javaScript->concluir();

$includePagina = $iAPC->concluir();

?>