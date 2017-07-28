<?php

importar("Utilidades.Discografia.Lista.ListaMusicaCategorias");

$tituloPagina = 'Utilidades > Discografia > Albúns > Cadastrar';

$iTCMC = new IFAdmin(new Arquivos(Sistema::$adminLayoutCaminhoDiretorio."/SistemaUtilidades/musicaCategoria.html"));

if(!empty($_POST)){
	
	$erro = '';

	
    if(empty($_POST['titulo']))
	    $erro = "<b>Titulo</b> não preenchido!<br><br>";
	if(empty($_POST['gravadora']))
	    $erro = "<b>Gravadora</b> não preenchido!<br><br>";
	if(empty($_POST['dataLancamento']))
	    $erro = "<b>Ano de lançamento</b> não preenchido!<br><br>";

	if(empty($erro)){
	
	    $mC 			= new MusicaCategoria;
		
		$mC->titulo 	= $_POST['titulo'];	
		$mC->subTitulo 	= $_POST['subTitulo'];	
		$mC->gravadora 	= $_POST['gravadora'];	
		$mC->ordem		= $_POST['ordem'];
		
		$mC->setDataLancamento(new DataHora($_POST['dataLancamento']."0101"));
		
		if(!empty($_FILES['capa']['name']))
			$mC->setCapa(new Image(Arquivos::__OpenArquivoByTEMP($_FILES['capa'])));
		
		$lMC				= new ListaMusicaCategorias;
		$lMC->inserir($mC);
	    
		$_POST = '';
		
	    $javaScript .= Aviso::criar("Albúm salvo com sucesso!");
	
	}else{
		
		$javaScript .= Aviso::criar($erro);
		
	}
	
}


$iTCMC->trocar("linkVoltar", "?p=".$_GET['p']."&a=listarMusicaCategorias");

$iTCMC->trocar("titulo", 			$_POST['titulo']);
$iTCMC->trocar("subTitulo", 		$_POST['subTitulo']);
$iTCMC->trocar("gravadora", 		$_POST['gravadora']);
$iTCMC->trocar("dataLancamento", 	$_POST['dataLancamento']);
$iTCMC->trocar("ordem", 			$_POST['ordem']);


$iTCMC->createJavaScript();
$javaScript .= $iTCMC->javaScript->concluir();

$includePagina = $iTCMC->concluir();

?>