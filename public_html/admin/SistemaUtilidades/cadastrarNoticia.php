<?php

importar("Utilidades.Noticias.Lista.ListaNoticias");

$tituloPagina = 'Utilidades > Noticias > Cadastrar';

$iCT = new IFAdmin(new Arquivos(Sistema::$adminLayoutCaminhoDiretorio."/SistemaUtilidades/noticia.html"));

$lI 	= new ListaIdiomas;

if(!empty($_POST)){
	
	$erro = '';

	
    if(empty($_POST['titulo']))
	    $erro = "<b>Titulo</b> não preenchido!<br><br>";

	if(empty($erro)){
	
	    $not 			= new Noticia;
		
		$not->getTexto()->titulo 	= $_POST['titulo'];		
		$not->getTexto()->subTitulo = $_POST['subTitulo'];
		$not->getTexto()->texto		= addslashes($_POST['texto']);
		$not->setData(new DataHora($_POST['data']));
		
		if(!empty($_FILES['imagem']['name']))
			$not->getTexto()->getImagem()->setImage(new Image(Arquivos::__OpenArquivoByTEMP($_FILES['imagem'])));
		
		
		$lN				= new ListaNoticias;
		$lN->inserir($not);
		$not->getURL()->setURL($_POST['url'] ? $_POST['url'] : $not->getId()."-".URL::cleanURL($_POST['titulo']));
		$lN->alterar($not);
		
		$lT				= new ListaTextos;
		
		while($i = $lI->listar()){
			
			$t = new Traducao;
			
			$t->setIdConteudo($not->getTexto()->getId());
			$t->setCampoConteudo(ListaTextos::TITULO);
			$t->setTabelaConteudo($lT->getTabela());
			$t->conteudo = $not->getTexto()->titulo;
			$t->traducao = $_POST['ititulo'][$i->getId()];
			$i->addTraducao($t);
			
			$t->setCampoConteudo(ListaTextos::SUBTITULO);
			$t->conteudo = $not->getTexto()->subTitulo;
			$t->traducao = $_POST['isubTitulo'][$i->getId()];
			$i->addTraducao($t);
			
			$t->setCampoConteudo(ListaTextos::TEXTO);
			$t->conteudo = $not->getTexto()->texto;
			$t->traducao = $_POST['itexto'][$i->getId()];
			$i->addTraducao($t);
			
		}
		
		$lI->resetCondicoes();
		$lI->setParametros(0);
	    
		$_POST = '';
		
		header("Location: ".Sistema::$adminCaminhoURL."?p=".$_GET['p']."&a=alterarNoticia&noticia=".$not->getId());
		
	    $javaScript .= Aviso::criar("Noticia salva com sucesso!");
	
	}else{
		
		$javaScript .= Aviso::criar($erro);
		
	}
	
}

$iCT->condicao("condicao->alterar.Noticia", true);

$iCT->trocar("linkVoltar", "?p=".$_GET['p']."&a=listarNoticias");

$iCT->trocar("titulo", $_POST['titulo']);
$iCT->trocar("subTitulo", $_POST['subTitulo']);
$iCT->trocar("url", $_POST['url']);
$iCT->trocar("data", $_POST['data']);
$iCT->trocar("texto", $_POST['texto']);


$sub 	= "repetir->titulo.Noticias.Idiomas";
$sub2 	= "repetir->texto.Noticias.Idiomas";
$sub3 	= "repetir->subTitulo.Noticias.Idiomas";
$iCT->createRepeticao($sub);
$iCT->createRepeticao($sub2);
$iCT->createRepeticao($sub3);
while($i = $lI->listar()){
	
	$iCT->repetir($sub);
	$iCT->repetir($sub2);
	$iCT->repetir($sub3);
	
	$iCT->enterRepeticao($sub)->trocar("nome.Idioma", $i->nome);
	$iCT->enterRepeticao($sub)->trocar("id.Idioma", $i->getId());
	if(!empty($_POST)) 
		$iCT->enterRepeticao($sub)->trocar("titulo.Noticia.Idioma", $_POST['ititulo'][$i->getId()]);
	
	$iCT->enterRepeticao($sub2)->trocar("nome.Idioma", $i->nome);
	$iCT->enterRepeticao($sub2)->trocar("id.Idioma", $i->getId());
	if(!empty($_POST)) 
		$iCT->enterRepeticao($sub2)->trocar("texto.Noticia.Idioma", $_POST['itexto'][$i->getId()]);
		
	$iCT->enterRepeticao($sub3)->trocar("nome.Idioma", $i->nome);
	$iCT->enterRepeticao($sub3)->trocar("id.Idioma", $i->getId());
	if(!empty($_POST)) 
		$iCT->enterRepeticao($sub3)->trocar("subTitulo.Noticia.Idioma", $_POST['isubTitulo'][$i->getId()]);
	
}

$iCT->createJavaScript();
$javaScript .= $iCT->javaScript->concluir();

$includePagina = $iCT->concluir();

?>