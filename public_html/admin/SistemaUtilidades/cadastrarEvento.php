<?php

importar("Utilidades.Lista.ListaEventos");

$tituloPagina = 'Utilidades > Eventos - Agenda > Cadastrar';

$iCT = new IFAdmin(new Arquivos(Sistema::$adminLayoutCaminhoDiretorio."/SistemaUtilidades/evento.html"));

$lI 	= new ListaIdiomas;

if(!empty($_POST)){
	
	$erro = '';

	
    if(empty($_POST['titulo']))
	    $erro = "<b>Titulo</b> não preenchido!<br><br>";
	if(empty($_POST['url']))
	    $erro = "<b>URL</b> não preenchido!<br><br>";

	if(empty($erro)){
	
	    $eve 			= new Evento;
		
		$eve->getTexto()->titulo 	= $_POST['titulo'];		
		$eve->local					= $_POST['local'];
		$eve->getURL()->setURL($_POST['url']);
		$eve->getTexto()->texto		= html_entity_decode($_POST['texto']);
		$eve->setData(new DataHora($_POST['data']));
		
		if(!empty($_FILES['imagem']['name']))
			$eve->getTexto()->getImagem()->setImage(new Image(Arquivos::__OpenArquivoByTEMP($_FILES['imagem'])));
		
		
		$lE				= new ListaEventos;
		$lE->inserir($eve);
		
		$lT				= new ListaTextos;
		
		while($i = $lI->listar()){
			
			$t = new Traducao;
			
			$t->setIdConteudo($eve->getTexto()->getId());
			$t->setCampoConteudo(ListaTextos::TITULO);
			$t->setTabelaConteudo($lT->getTabela());
			$t->conteudo = $eve->getTexto()->titulo;
			$t->traducao = $_POST['ititulo'][$i->getId()];
			$i->addTraducao($t);
			
			$t->setCampoConteudo(ListaTextos::TEXTO);
			$t->conteudo = $eve->getTexto()->texto;
			$t->traducao = $_POST['itexto'][$i->getId()];
			$i->addTraducao($t);
			
		}
		
		$lI->resetCondicoes();
		$lI->setParametros(0);
	    
		$_POST = '';
		
	    $javaScript .= Aviso::criar("Evento salvo com sucesso!");
	
	}else{
		
		$javaScript .= Aviso::criar($erro);
		
	}
	
}


$iCT->trocar("linkVoltar", "?p=".$_GET['p']."&a=listarEventos");

$iCT->trocar("titulo", $_POST['titulo']);
$iCT->trocar("local", $_POST['local']);
$iCT->trocar("url", $_POST['url']);
$iCT->trocar("data", $_POST['data']);
$iCT->trocar("texto", $_POST['texto']);


$sub 	= "repetir->titulo.Eventos.Idiomas";
$sub2 	= "repetir->texto.Eventos.Idiomas";
$iCT->createRepeticao($sub);
$iCT->createRepeticao($sub2);
while($i = $lI->listar()){
	
	$iCT->repetir($sub);
	$iCT->repetir($sub2);
	
	$iCT->enterRepeticao($sub)->trocar("nome.Idioma", $i->nome);
	$iCT->enterRepeticao($sub)->trocar("id.Idioma", $i->getId());
	if(!empty($_POST)) 
		$iCT->enterRepeticao($sub)->trocar("titulo.Evento.Idioma", $_POST['ititulo'][$i->getId()]);
	
	$iCT->enterRepeticao($sub2)->trocar("nome.Idioma", $i->nome);
	$iCT->enterRepeticao($sub2)->trocar("id.Idioma", $i->getId());
	if(!empty($_POST)) 
		$iCT->enterRepeticao($sub2)->trocar("texto.Evento.Idioma", $_POST['itexto'][$i->getId()]);
	
}

$iCT->createJavaScript();
$javaScript .= $iCT->javaScript->concluir();

$includePagina = $iCT->concluir();

?>