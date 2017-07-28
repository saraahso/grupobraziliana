<?php

importar("Utilidades.Publicidades.Banners.Lista.ListaBannerCategorias");

$tituloPagina = 'Utilidades > Publicidades > Banners > Categorias > Alterar';

$iTPC = new IFAdmin(new Arquivos(Sistema::$adminLayoutCaminhoDiretorio."/SistemaUtilidades/bannerCategoria.html"));

$lI = new ListaIdiomas;

if(!empty($_POST)){
	
	$erro = '';	
	
    if(empty($_POST['titulo']))
	    $erro = "<b>Titulo</b> não preenchido!<br><br>";

	if(empty($erro)){
		
		$lBC = new ListaBannerCategorias;
		$lBC->condicoes('', $_GET['categoria'], ListaBannerCategorias::ID);
		$bC = $lBC->listar();
		
		$bC->titulo 	= $_POST['titulo'];
		$bC->setLargura($_POST['largura']);
		$bC->setAltura($_POST['altura']);
		
		$lBC->alterar($bC);
		
		while($i = $lI->listar()){
			
			$t = $i->getTraducaoById(ListaBannerCategorias::TITULO, $lBC->getTabela(), $bC->getId());
			
			if($t->getId()){
				
				$t->conteudo = $bC->titulo;
				$t->traducao = $_POST['ititulo'][$i->getId()];
				$i->getTraducoes()->alterar($t);
			
			}else{
				
				$t = new Traducao;
				
				$t->conteudo = $bC->titulo;
				$t->traducao = $_POST['ititulo'][$i->getId()];
				$t->setIdConteudo($bC->getId());
				$t->setCampoConteudo(ListaBannerCategorias::TITULO);
				$t->setTabelaConteudo($lBC->getTabela());
				$i->addTraducao($t);
				
			}
			
		}
		
		$lI->setParametros(0);
		
	    $javaScript .= Aviso::criar("Categoria salva com sucesso!");
	
	}else{
		
		$javaScript .= Aviso::criar($erro);
		
	}
	
}

$lBC = new ListaBannerCategorias;
$bC = $lBC->condicoes('', $_GET['categoria'], ListaBannerCategorias::ID)->listar();

$iTPC->trocar("titulo",		$bC->titulo);
$iTPC->trocar("largura", 	$bC->getLargura()->moeda());
$iTPC->trocar("altura", 	$bC->getAltura()->moeda());

$sub 	= "repetir->titulo.BannerCategorias.Idiomas";

$iTPC->createRepeticao($sub);

while($i = $lI->listar()){
	
	$iTPC->repetir($sub);
	
	$iTPC->enterRepeticao($sub)->trocar("nome.Idioma", $i->nome);
	$iTPC->enterRepeticao($sub)->trocar("id.Idioma", $i->getId());
	$iTPC->enterRepeticao($sub)->trocar("titulo.BannerCategoria.Idioma", $i->getTraducaoById(ListaBannerCategorias::TITULO, $lBC->getTabela(), $bC->getId())->traducao);
	
}

$iTPC->trocar("linkVoltar", "?p=".$_GET['p']."&a=listarBannerCategorias");

$iTPC->createJavaScript();
$javaScript .= $iTPC->javaScript->concluir();

$includePagina = $iTPC->concluir();

?>