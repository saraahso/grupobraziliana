<?php

importar("Utilidades.Publicidades.Banners.Lista.ListaBannerCategorias");

$tituloPagina = 'Utilidades > Publicidades > Banners > Categorias > Cadastrar';

$iTPC = new IFAdmin(new Arquivos(Sistema::$adminLayoutCaminhoDiretorio."/SistemaUtilidades/bannerCategoria.html"));

$lI 	= new ListaIdiomas;

if(!empty($_POST)){
	
	$erro = '';

	
    if(empty($_POST['titulo']))
	    $erro = "<b>Titulo</b> não preenchido!<br><br>";
	if(empty($_POST['largura']))
	    $erro = "<b>Largura</b> não preenchido!<br><br>";
	if(empty($_POST['altura']))
	    $erro = "<b>Altura</b> não preenchido!<br><br>";

	if(empty($erro)){
	
	    $bC 			= new BannerCategoria;
		
		$bC->titulo 	= $_POST['titulo'];		
		$bC->setLargura($_POST['largura']);
		$bC->setAltura($_POST['altura']);
		
		$lB				= new ListaBannerCategorias;
		$lB->inserir($bC);
		
		while($i = $lI->listar()){
			
			$t = new Traducao;
			
			$t->setIdConteudo($bC->getId());
			$t->setCampoConteudo(ListaBannerCategorias::TITULO);
			$t->setTabelaConteudo($lB->getTabela());
			$t->conteudo = $bC->titulo;
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


$iTPC->trocar("linkVoltar", "?p=".$_GET['p']."&a=listarBannerCategorias");

$iTPC->trocar("titulo", 	$_POST['titulo']);
$iTPC->trocar("largura", 	$_POST['largura']);
$iTPC->trocar("altura", 	$_POST['altura']);


$sub 	= "repetir->titulo.BannerCategorias.Idiomas";

$iTPC->createRepeticao($sub);

while($i = $lI->listar()){
	
	$iTPC->repetir($sub);
;
	
	$iTPC->enterRepeticao($sub)->trocar("nome.Idioma", $i->nome);
	$iTPC->enterRepeticao($sub)->trocar("id.Idioma", $i->getId());
	if(!empty($_POST)) 
		$iTPC->enterRepeticao($sub)->trocar("titulo.BannerCategoria.Idioma", $_POST['ititulo'][$i->getId()]);
	
	
}

$iTPC->createJavaScript();
$javaScript .= $iTPC->javaScript->concluir();

$includePagina = $iTPC->concluir();

?>