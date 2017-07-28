<?php

importar("Geral.Lista.ListaTextos");

$tituloPagina = 'Utilidades > Textos > Cadastrar';

$iCT = new IFAdmin(new Arquivos(Sistema::$adminLayoutCaminhoDiretorio."/SistemaUtilidades/texto.html"));

$lI 	= new ListaIdiomas;

if(!empty($_POST)){
	
	$erro = '';

	
    if(empty($_POST['titulo']))
	    $erro = "<b>Titulo</b> não preenchido!<br><br>";
	if(empty($_POST['url']))
	    $erro = "<b>URL</b> não preenchido!<br><br>";

	if(empty($erro)){
	
	    $tx 			= new Texto;
		
		$tx->titulo 		= $_POST['titulo'];	
		$tx->subTitulo 		= $_POST['subTitulo'];	
		$tx->ordem			= $_POST['ordem'];
		$tx->getURL()->setURL($_POST['url']);
		$tx->textoPequeno	= $_POST['textoPequeno'];
		$tx->texto			= $_POST['texto'];
		
		if(!empty($_FILES['imagem']['name']))
			$tx->getImagem()->setImage(new Image(Arquivos::__OpenArquivoByTEMP($_FILES['imagem'])));
		
		
		$lT				= new ListaTextos;
		$lT->inserir($tx);
		
		while($i = $lI->listar()){
			
			$t = new Traducao;
			
			$t->setIdConteudo($tx->getId());
			$t->setCampoConteudo(ListaTextos::TITULO);
			$t->setTabelaConteudo($lT->getTabela());
			$t->conteudo = $tx->titulo;
			$t->traducao = $_POST['ititulo'][$i->getId()];
			$i->addTraducao($t);
			
			$t->setCampoConteudo(ListaTextos::SUBTITULO);
			$t->conteudo = $tx->subTitulo;
			$t->traducao = $_POST['isubTitulo'][$i->getId()];
			$i->addTraducao($t);
			
			$t->setCampoConteudo(ListaTextos::TEXTOPEQUENO);
			$t->conteudo = $tx->textoPequeno;
			$t->traducao = $_POST['itextoPequeno'][$i->getId()];
			$i->addTraducao($t);
			
			$t->setCampoConteudo(ListaTextos::TEXTO);
			$t->conteudo = $tx->texto;
			$t->traducao = $_POST['itexto'][$i->getId()];
			$i->addTraducao($t);
			
		}
		
		$lI->resetCondicoes();
		$lI->setParametros(0);
	    
		$_POST = '';
		
	    $javaScript .= Aviso::criar("Texto salvo com sucesso!");
	
	}else{
		
		$javaScript .= Aviso::criar($erro);
		
	}
	
}


$iCT->trocar("linkVoltar", "?p=".$_GET['p']."&a=listarTextos");

$iCT->trocar("titulo", 		$_POST['titulo']);
$iCT->trocar("subTitulo", 	$_POST['subTitulo']);
$iCT->trocar("url", 		$_POST['url']);
$iCT->trocar("ordem", 		$_POST['ordem']);
$iCT->trocar("textoPequeno", 		$_POST['textoPequeno']);
$iCT->trocar("texto", 		$_POST['texto']);


$sub 	= "repetir->titulo.Textos.Idiomas";
$sub2 	= "repetir->texto.Textos.Idiomas";
$sub3 	= "repetir->subTitulo.Textos.Idiomas";
$sub4 	= "repetir->textoPequeno.Textos.Idiomas";
$iCT->createRepeticao($sub);
$iCT->createRepeticao($sub2);
$iCT->createRepeticao($sub3);
$iCT->createRepeticao($sub4);
while($i = $lI->listar()){
	
	$iCT->repetir($sub);
	$iCT->repetir($sub2);
	$iCT->repetir($sub3);
	$iCT->repetir($sub4);
	
	$iCT->enterRepeticao($sub)->trocar("nome.Idioma", $i->nome);
	$iCT->enterRepeticao($sub)->trocar("id.Idioma", $i->getId());
	if(!empty($_POST)) 
		$iCT->enterRepeticao($sub)->trocar("titulo.Texto.Idioma", $_POST['ititulo'][$i->getId()]);
	
	$iCT->enterRepeticao($sub2)->trocar("nome.Idioma", $i->nome);
	$iCT->enterRepeticao($sub2)->trocar("id.Idioma", $i->getId());
	if(!empty($_POST)) 
		$iCT->enterRepeticao($sub2)->trocar("texto.Texto.Idioma", $_POST['itexto'][$i->getId()]);
		
	$iCT->enterRepeticao($sub3)->trocar("nome.Idioma", $i->nome);
	$iCT->enterRepeticao($sub3)->trocar("id.Idioma", $i->getId());
	if(!empty($_POST)) 
		$iCT->enterRepeticao($sub3)->trocar("subTitulo.Texto.Idioma", $_POST['isubTitulo'][$i->getId()]);
		
	$iCT->enterRepeticao($sub4)->trocar("nome.Idioma", $i->nome);
	$iCT->enterRepeticao($sub4)->trocar("id.Idioma", $i->getId());
	if(!empty($_POST)) 
		$iCT->enterRepeticao($sub4)->trocar("textoPequeno.Texto.Idioma", $_POST['itextoPequeno'][$i->getId()]);
	
}

$iCT->createJavaScript();
$javaScript .= $iCT->javaScript->concluir();

$includePagina = $iCT->concluir();

?>