<?php

importar("Utilidades.Publicidades.Slides.Lista.ListaSlideCategorias");

$tituloPagina = 'Utilidades > Publicidades > Slides > Categorias > Cadastrar';

$iTSC = new IFAdmin(new Arquivos(Sistema::$adminLayoutCaminhoDiretorio."/SistemaUtilidades/slideCategoria.html"));

$lI 	= new ListaIdiomas;

if(!empty($_POST)){
	
	$erro = '';

	
    if(empty($_POST['titulo']))
	    $erro = "<b>Titulo</b> não preenchido!<br><br>";

	if(empty($erro)){
	
	    $sC 			= new SlideCategoria;
		
		$sC->titulo 	= $_POST['titulo'];
		
		$lS				= new ListaSlideCategorias;
		$lS->inserir($sC);
		
		while($i = $lI->listar()){
			
			$t = new Traducao;
			
			$t->setIdConteudo($sC->getId());
			$t->setCampoConteudo(ListaSlideCategorias::TITULO);
			$t->setTabelaConteudo($lS->getTabela());
			$t->conteudo = $sC->titulo;
			$t->traducao = $_POST['ititulo'][$i->getId()];
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


$iTSC->trocar("linkVoltar", "?p=".$_GET['p']."&a=listarSlideCategorias");

$iTSC->trocar("titulo", $_POST['titulo']);

$sub 	= "repetir->titulo.SlideCategorias.Idiomas";

$iTSC->createRepeticao($sub);

while($i = $lI->listar()){
	
	$iTSC->repetir($sub);
;
	
	$iTSC->enterRepeticao($sub)->trocar("nome.Idioma", $i->nome);
	$iTSC->enterRepeticao($sub)->trocar("id.Idioma", $i->getId());
	if(!empty($_POST)) 
		$iTSC->enterRepeticao($sub)->trocar("titulo.SlideCategoria.Idioma", $_POST['ititulo'][$i->getId()]);
	
	
}

$iTSC->createJavaScript();
$javaScript .= $iTSC->javaScript->concluir();

$includePagina = $iTSC->concluir();

?>