<?php

importar("Utilidades.Discografia.Lista.ListaMusicaCategorias");
importar("Utils.Ajax");

$tituloPagina = 'Utilidades > Discografia > Albúns';

$iTMC = new IFAdmin(new Arquivos(Sistema::$adminLayoutCaminhoDiretorio."/SistemaUtilidades/listarMusicaCategorias.html"));

$iTMC->trocar("linkDeletar.MusicaCategoria", "?p=".$_GET['p']."&a=".$_GET['a']."&");
$iTMC->trocar("linkBuscar.MusicaCategoria", "?p=".$_GET['p']."&");

if(!empty($_GET['deletar'])){
	
	$lUDC = new ListaMusicaCategorias;
	$lUDC->condicoes('', $_GET['deletar'], ListaMusicaCategorias::ID);
	
	if($lUDC->getTotal() > 0){
		
		try{
			$lUDC->deletar($lUDC->listar());
			$javaScript .= Aviso::criar("Albúm removido com sucesso!");
		}catch(Exception $e){
			
			$javaScript .= Aviso::criar($e->getMessage());	
			
		}
	
	
	}
	
}

$lUDC = new ListaMusicaCategorias;

if(isset($_GET['json'])){
	
	$cond['lista'] 	= true;
	
	while($pC = $lUDC->listar()){
		$cond[$lUDC->getParametros()]['id'] 	= $pC->getId();
		$cond[$lUDC->getParametros()]['nome'] = $pC->titulo;
	}
	
	$ajax = new Ajax;
	echo $ajax->getJSON()->converter($cond);
	exit;
	
}

$iTMC->createRepeticao("repetir->MusicaCategorias");

if(!empty($_GET['busca']))
     $lUDC->condicoes('', "%".$_GET['busca']."%", 'empresa', 'LIKE');

$iTMC->condicao("condicaoBusca", !empty($_SESSION['nivel']));

$iTMC->trocar("linkCadastrar.MusicaCategoria", "?p=".$_GET['p']."&a=cadastrarMusicaCategoria");

while($uDC = $lUDC->listar("ASC", ListaMusicaCategorias::TITULO)){
	  
	   $iTMC->repetir();
	   
	   $iTMC->enterRepeticao()->condicao("condicaoRemover", !empty($_SESSION['nivel']));
	   
	   $bgColor = $lUDC->getParametros()%2 == 0 ? '#FFFFFF' : '#EAEAEA';
	   $iTMC->enterRepeticao()->trocar("bgColorEmpresa", $bgColor);
	   
	   $iTMC->enterRepeticao()->trocar("id.MusicaCategoria", $uDC->getId());
	   $iTMC->enterRepeticao()->trocar("titulo.MusicaCategoria", $uDC->titulo);
	   
	   $iTMC->enterRepeticao()->trocar("linkAlterar.MusicaCategoria", "?p=".$_GET['p']."&a=alterarMusicaCategoria&categoria=".$uDC->getId());
	   
	   $iTMC->enterRepeticao()->condicao("condicaoVisualizar", $uDC->tipo == 1);
	 
}

$iTMC->trocar("linkVoltar.MusicaCategoria", "?p=".$_GET['p']."&a=discografia");

$botoes = $iTMC->cutParte('botoes');

$includePagina = $iTMC->concluir();

?>