<?php

importar("Utilidades.Discografia.Lista.ListaMusicaCategorias");

$mCituloPagina = 'Utilidades > Discografia > Albúns > Alterar';

$iTAMC = new IFAdmin(new Arquivos(Sistema::$adminLayoutCaminhoDiretorio."/SistemaUtilidades/musicaCategoria.html"));

if(!empty($_POST)){
	
	$erro = '';	
	
    if(empty($_POST['titulo']))
	    $erro = "<b>Titulo</b> não preenchido!<br><br>";
	if(empty($_POST['gravadora']))
	    $erro = "<b>Gravadora</b> não preenchido!<br><br>";
	if(empty($_POST['dataLancamento']))
	    $erro = "<b>Ano de lançamento</b> não preenchido!<br><br>";

	if(empty($erro)){
		
		$lMC = new ListaMusicaCategorias;
		$lMC->condicoes('', $_GET['categoria'], ListaMusicaCategorias::ID);
		$mC = $lMC->listar();
		
		$mC->titulo 	= $_POST['titulo'];
		$mC->subTitulo 	= $_POST['subTitulo'];
		$mC->gravadora 	= $_POST['gravadora'];
		$mC->ordem		= $_POST['ordem'];
		
		$mC->setDataLancamento(new DataHora($_POST['dataLancamento']."0101"));
		
		if(!empty($_FILES['capa']['name']))
			$mC->setCapa(new Image(Arquivos::__OpenArquivoByTEMP($_FILES['capa'])));
		
		$lMC->alterar($mC);
		
	    $javaScript .= Aviso::criar("Albúm salvo com sucesso!");
	
	}else{
		
		$javaScript .= Aviso::criar($erro);
		
	}
	
}

$lMC = new ListaMusicaCategorias;
$mC = $lMC->condicoes('', $_GET['categoria'], ListaMusicaCategorias::ID)->listar();

$iTAMC->trocar("titulo",			$mC->titulo);
$iTAMC->trocar("subTitulo",			$mC->subTitulo);
$iTAMC->trocar("gravadora",			$mC->gravadora);
$iTAMC->trocar("dataLancamento",	$mC->getDataLancamento()->mostrar("Y"));
$iTAMC->trocar("ordem", 			$mC->ordem);
if($mC->getCapa()->nome != "")
	$iTAMC->trocar("capa", 				$mC->getCapa()->showHTML(200, 200));

$iTAMC->trocar("linkVoltar", "?p=".$_GET['p']."&a=listarMusicaCategorias");

$iTAMC->createJavaScript();
$javaScript .= $iTAMC->javaScript->concluir();

$includePagina = $iTAMC->concluir();

?>