<?php

importar("Utilidades.Galerias.Lista.ListaGaleriaCategorias");

$tituloPagina = 'Utilidades > Galerias > Categorias > Cadastrar';

$iTCGC = new IFAdmin(new Arquivos(Sistema::$adminLayoutCaminhoDiretorio."/SistemaUtilidades/galeriaCategoria.html"));

$lI 	= new ListaIdiomas;

if(!empty($_POST)){
	
	$erro = '';

	
    if(empty($_POST['titulo']))
	    $erro = "<b>Titulo</b> não preenchido!<br><br>";

	if(empty($erro)){
	
	    $gC 			= new GaleriaCategoria;
		
		$gC->titulo 	= $_POST['titulo'];	
		$gC->getURL()->setURL($_POST['url'] ? $_POST['url'] : URL::cleanURL($_POST['titulo']));
		$gC->protegido	= $_POST['protegido'] == ListaGaleriaCategorias::VALOR_PROTEGIDO_TRUE ? true : false;
		
		$gC->setLargura($_POST['largura']);
		$gC->setAltura($_POST['altura']);
		
		$gC->setLarguraMedia($_POST['larguram']);
		$gC->setAlturaMedia($_POST['alturam']);
		
		$gC->setLarguraPequena($_POST['largurap']);
		$gC->setAlturaPequena($_POST['alturap']);
		
		$gC->getTexto()->subTitulo 	  = $_POST['subTitulo'];
		$gC->getTexto()->textoPequeno = $_POST['textoPequeno'];
		$gC->getTexto()->texto = $_POST['descricao'];
		if(!empty($_FILES['imagem']['name']))
			$gC->getTexto()->getImagem()->setImage(new Image(Arquivos::__OpenArquivoByTEMP($_FILES['imagem'])));
		
		$lB				= new ListaGaleriaCategorias;
		$lB->inserir($gC);
		
		$lT = new ListaTextos;
		
		while($i = $lI->listar()){
			
			$t = new Traducao;
			
			$t->setIdConteudo($gC->getId());
			$t->setCampoConteudo(ListaGaleriaCategorias::TITULO);
			$t->setTabelaConteudo($lB->getTabela());
			$t->conteudo = $gC->titulo;
			$t->traducao = $_POST['ititulo'][$i->getId()];
			$i->addTraducao($t);
			
			$t = new Traducao;
			
			$t->setIdConteudo($gC->getTexto()->getId());
			$t->setCampoConteudo(ListaTextos::SUBTITULO);
			$t->setTabelaConteudo($lT->getTabela());
			$t->conteudo = $gC->getTexto()->subTitulo;
			$t->traducao = $_POST['iSubTitulo'][$i->getId()];
			$i->addTraducao($t);
			
			$t = new Traducao;
			
			$t->setIdConteudo($gC->getTexto()->getId());
			$t->setCampoConteudo(ListaTextos::TEXTOPEQUENO);
			$t->setTabelaConteudo($lT->getTabela());
			$t->conteudo = $gC->getTexto()->textoPequeno;
			$t->traducao = $_POST['itextoPequeno'][$i->getId()];
			$i->addTraducao($t);
			
			$t = new Traducao;
			
			$t->setIdConteudo($gC->getTexto()->getId());
			$t->setCampoConteudo(ListaTextos::TEXTO);
			$t->setTabelaConteudo($lT->getTabela());
			$t->conteudo = $gC->getTexto()->texto;
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


$iTCGC->trocar("linkVoltar", "?p=".$_GET['p']."&a=listarGaleriaCategorias");

$iTCGC->trocar("titulo", 		$_POST['titulo']);
$iTCGC->trocar("subTitulo",		$_POST['subTitulo']);
$iTCGC->trocar("url", 			$_POST['url']);

$iTCGC->trocar("largura", 		$_POST['largura']);
$iTCGC->trocar("altura", 		$_POST['altura']);

$iTCGC->trocar("larguram", 		$_POST['larguram']);
$iTCGC->trocar("alturam", 		$_POST['alturam']);

$iTCGC->trocar("largurap", 		$_POST['largurap']);
$iTCGC->trocar("alturap", 		$_POST['alturap']);

$iTCGC->trocar("textoPequeno",	$_POST['textpPequeno']);
$iTCGC->trocar("descricao",		$_POST['descricao']);

$sub 	= "repetir->titulo.GaleriaCategorias.Idiomas";
$sub4 	= "repetir->subTitulo.GaleriaCategorias.Idiomas";
$sub2 	= "repetir->descricao.GaleriaCategorias.Idiomas";
$sub3 	= "repetir->textoPequeno.GaleriaCategorias.Idiomas";
$iTCGC->createRepeticao($sub);
$iTCGC->createRepeticao($sub2);
$iTCGC->createRepeticao($sub3);
$iTCGC->createRepeticao($sub4);
while($i = $lI->listar()){
	
	$iTCGC->repetir($sub);
	
	$iTCGC->enterRepeticao($sub)->trocar("nome.Idioma", $i->nome);
	$iTCGC->enterRepeticao($sub)->trocar("id.Idioma", $i->getId());
	if(!empty($_POST)) 
		$iTCGC->enterRepeticao($sub)->trocar("titulo.GaleriaCategorias.Idioma", $_POST['ititulo'][$i->getId()]);
		
	$iTCGC->repetir($sub4);
	$iTCGC->enterRepeticao($sub4)->trocar("nome.Idioma", $i->nome);
	$iTCGC->enterRepeticao($sub4)->trocar("id.Idioma", $i->getId());
	if(!empty($_POST)) 
		$iTCGC->enterRepeticao($sub4)->trocar("subTitulo.GaleriaCategorias.Idioma", $_POST['iSubTitulo'][$i->getId()]);	
	
	$iTCGC->repetir($sub2);
	
	$iTCGC->enterRepeticao($sub2)->trocar("nome.Idioma", $i->nome);
	$iTCGC->enterRepeticao($sub2)->trocar("id.Idioma", $i->getId());
	if(!empty($_POST)) 
		$iTCGC->enterRepeticao($sub2)->trocar("descricao.GaleriaCategorias.Idioma", $_POST['idescricao'][$i->getId()]);
	
	$iTCGC->repetir($sub3);
	$iTCGC->enterRepeticao($sub3)->trocar("nome.Idioma", $i->nome);
	$iTCGC->enterRepeticao($sub3)->trocar("id.Idioma", $i->getId());
	if(!empty($_POST)) 
		$iTCGC->enterRepeticao($sub3)->trocar("textoPequeno.GaleriaCategorias.Idioma", $_POST['itextoPequeno'][$i->getId()]);
	
	
}

$iTCGC->createJavaScript();
$javaScript .= $iTCGC->javaScript->concluir();

$includePagina = $iTCGC->concluir();

?>