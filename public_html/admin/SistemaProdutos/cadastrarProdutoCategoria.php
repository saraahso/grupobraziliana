<?php

importar("LojaVirtual.Categorias.Lista.ListaProdutoCategorias");

$tituloPagina = 'Produtos > Categorias > Cadastrar';

$iCPC = new IFAdmin(new Arquivos(Sistema::$adminLayoutCaminhoDiretorio."/SistemaProdutos/produtoCategoria.html"));

$lI 	= new ListaIdiomas;

if(!empty($_POST)){
	
	$erro = '';
	
	$lCP = new ListaProdutoCategorias;
	if(!empty($_GET['categoria'])){
		
		$lCP->condicoes('', $_GET['categoria'], ListaProdutoCategorias::ID);
		$cP = $lCP->listar();
		
	}else
		$cP = new ProdutoCategoria;
	
	
    if(empty($_POST['nome']))
	    $erro = "<b>Nome</b> não preenchido!<br><br>";

	if(empty($erro)){
	
	    $pC 					= new ProdutoCategoria;
		
		$pC->nome 				= $_POST['nome'];		
		$pC->ordem				= $_POST['ordem'];
		$pC->subreferencia		= $_POST['subreferencia'];
		$pC->cor				= $_POST['cor'];
		$pC->disponivel			= $_POST['disponivel'] 	== ListaProdutoCategorias::VALOR_DISPONIVEL_TRUE ? true : false;
		$pC->visaoUnica			= $_POST['visaoUnica'] 	== ListaProdutoCategorias::VALOR_VISAOUNICA_TRUE ? true : false;
		$pC->home				= $_POST['home'] 		== ListaProdutoCategorias::VALOR_HOME_TRUE ? true : false;
		
		if(!empty($_POST['url']))
			$pC->getURL()->setURL($_POST['url']);
		else
			$pC->getURL()->setURL(($cP->getId() > 0 ? URL::cleanURL($cP->getNavegador(new Templates(Arquivos::__Create("{nome}"))))."-" : '').URL::cleanURL($_POST['nome']));
			
		$pC->descricaoPequena	= $_POST['descricaoPequena'];
		$pC->descricao			= $_POST['descricao'];
		
		$pC->setIdCategoriaPai($cP->getId());
		
		if(!empty($_FILES['imagem']['name']))
			$pC->setImagem(new Image(Arquivos::__OpenArquivoByTEMP($_FILES['imagem'])));
		
		$lCP->inserir($pC);
		
		while($i = $lI->listar()){
			
			$t = new Traducao;
			
			$t->setIdConteudo($pC->getId());
			$t->setCampoConteudo(ListaProdutoCategorias::NOME);
			$t->setTabelaConteudo($lCP->getTabela());
			$t->conteudo = $pC->nome;
			$t->traducao = $_POST['inome'][$i->getId()];
			$i->addTraducao($t);
			
			$t->setCampoConteudo(ListaProdutoCategorias::DESCRICAOPEQUENA);
			$t->conteudo = $pC->descricaoPequena;
			$t->traducao = $_POST['idescricaoPequena'][$i->getId()];
			$i->addTraducao($t);
			
			$t->setCampoConteudo(ListaProdutoCategorias::DESCRICAO);
			$t->conteudo = $pC->descricao;
			$t->traducao = $_POST['idescricao'][$i->getId()];
			$i->addTraducao($t);
			
		}
		
		$lI->resetCondicoes();
		$lI->setParametros(0);
	    
		$_POST = '';
		
	    $javaScript .= Aviso::criar("Categoria salva com sucesso!");
	
	}else{
		
		$javaScript .= Aviso::criar($erro);
		
	}
	
}

if(!empty($_GET['categoria'])){
	
	$lCP = new ListaProdutoCategorias;
	$lCP->condicoes('', $_GET['categoria'], ListaProdutoCategorias::ID);
	$cP = $lCP->listar();
	
}else
	$cP = new ProdutoCategoria;

$iCPC->trocar("linkVoltar", "?p=".$_GET['p']."&a=listarProdutoCategorias&categoria=".$cP->getId());

$iCPC->trocar("nome", $_POST['nome']);
$iCPC->trocar("url", $_POST['url']);
$iCPC->trocar("ordem", $_POST['ordem']);
$iCPC->trocar("subreferencia", $_POST['subreferencia']);
$iCPC->trocar("disponivel", $_POST['disponivel']);
$iCPC->trocar("visaoUnica", $_POST['visaoUnica']);
$iCPC->trocar("home", $_POST['home']);
$iCPC->trocar("cor", $_POST['cor']);
$iCPC->trocar("descricaoPequena", $_POST['descricaoPequena']);
$iCPC->trocar("descricao", $_POST['descricao']);

$iCPC->trocar("id.ProdutoCategoria", $cP->getId());


$sub 	= "repetir->nome.ProdutoCategorias.Idiomas";
$sub2 	= "repetir->descricao.ProdutoCategorias.Idiomas";
$sub3 	= "repetir->descricaoPequena.ProdutoCategorias.Idiomas";
$iCPC->createRepeticao($sub);
$iCPC->createRepeticao($sub2);
$iCPC->createRepeticao($sub3);
while($i = $lI->listar()){
	
	$iCPC->repetir($sub);
	$iCPC->repetir($sub2);
	$iCPC->repetir($sub3);
	
	$iCPC->enterRepeticao($sub)->trocar("nome.Idioma", $i->nome);
	$iCPC->enterRepeticao($sub)->trocar("id.Idioma", $i->getId());
	if(!empty($_POST)) 
		$iCPC->enterRepeticao($sub)->trocar("nome.ProdutoCategoria.Idioma", $_POST['inome'][$i->getId()]);
	
	$iCPC->enterRepeticao($sub2)->trocar("nome.Idioma", $i->nome);
	$iCPC->enterRepeticao($sub2)->trocar("id.Idioma", $i->getId());
	if(!empty($_POST)) 
		$iCPC->enterRepeticao($sub2)->trocar("descricao.ProdutoCategoria.Idioma", $_POST['idescricao'][$i->getId()]);
		
	$iCPC->enterRepeticao($sub3)->trocar("nome.Idioma", $i->nome);
	$iCPC->enterRepeticao($sub3)->trocar("id.Idioma", $i->getId());
	if(!empty($_POST)) 
		$iCPC->enterRepeticao($sub3)->trocar("descricaoPequena.ProdutoCategoria.Idioma", $_POST['idescricaoPequena'][$i->getId()]);
	
}

$iCPC->createJavaScript();
$javaScript .= $iCPC->javaScript->concluir();

$includePagina = $iCPC->concluir();

?>