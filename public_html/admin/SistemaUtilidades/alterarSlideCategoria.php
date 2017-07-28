<?php

importar("Utilidades.Publicidades.Slides.Lista.ListaSlideCategorias");

$sCituloPagina = 'Utilidades > Publicidades > Slides > Categorias > Alterar';

$iTSC = new IFAdmin(new Arquivos(Sistema::$adminLayoutCaminhoDiretorio."/SistemaUtilidades/slideCategoria.html"));

$lI = new ListaIdiomas;

if(!empty($_POST)){
	
	$erro = '';	
	
    if(empty($_POST['titulo']))
	    $erro = "<b>Titulo</b> não preenchido!<br><br>";

	if(empty($erro)){
		
		$lSC = new ListaSlideCategorias;
		$lSC->condicoes('', $_GET['categoria'], ListaSlideCategorias::ID);
		$sC = $lSC->listar();
		
		$sC->titulo 	= $_POST['titulo'];
		
		$lSC->alterar($sC);
		
		while($i = $lI->listar()){
			
			$t = $i->getTraducaoById(ListaSlideCategorias::TITULO, $lSC->getTabela(), $sC->getId());
			
			if($t->getId()){
				
				$t->conteudo = $sC->titulo;
				$t->traducao = $_POST['ititulo'][$i->getId()];
				$i->getTraducoes()->alterar($t);
			
			}else{
				
				$t = new Traducao;
				
				$t->conteudo = $sC->titulo;
				$t->traducao = $_POST['ititulo'][$i->getId()];
				$t->setIdConteudo($sC->getId());
				$t->setCampoConteudo(ListaSlideCategorias::TITULO);
				$t->setTabelaConteudo($lSC->getTabela());
				$i->addTraducao($t);
				
			}
			
		}
		
		$lI->setParametros(0);
		
	    $javaScript .= Aviso::criar("Categoria salva com sucesso!");
	
	}else{
		
		$javaScript .= Aviso::criar($erro);
		
	}
	
}

$lSC = new ListaSlideCategorias;
$sC = $lSC->condicoes('', $_GET['categoria'], ListaSlideCategorias::ID)->listar();

$iTSC->trocar("titulo",		$sC->titulo);

$sub 	= "repetir->titulo.SlideCategorias.Idiomas";

$iTSC->createRepeticao($sub);

while($i = $lI->listar()){
	
	$iTSC->repetir($sub);
	
	$iTSC->enterRepeticao($sub)->trocar("nome.Idioma", $i->nome);
	$iTSC->enterRepeticao($sub)->trocar("id.Idioma", $i->getId());
	$iTSC->enterRepeticao($sub)->trocar("titulo.SlideCategoria.Idioma", $i->getTraducaoById(ListaSlideCategorias::TITULO, $lSC->getTabela(), $sC->getId())->traducao);
	
}

$iTSC->trocar("linkVoltar", "?p=".$_GET['p']."&a=listarSlideCategorias");

$iTSC->createJavaScript();
$javaScript .= $iTSC->javaScript->concluir();

$includePagina = $iTSC->concluir();

?>