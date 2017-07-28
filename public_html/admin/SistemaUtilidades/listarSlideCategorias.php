<?php

importar("Utilidades.Publicidades.Slides.Lista.ListaSlideCategorias");
importar("Utils.Dados.JSON");

$tituloPagina = 'Utilidades > Publicidades > Slides > Categorias';

$iTPC = new IFAdmin(new Arquivos(Sistema::$adminLayoutCaminhoDiretorio."/SistemaUtilidades/listarSlideCategorias.html"));

$iTPC->trocar("linkDeletar.SlideCategoria", "?p=".$_GET['p']."&a=".$_GET['a']."&");
$iTPC->trocar("linkBuscar.SlideCategoria", "?p=".$_GET['p']."&");

if(!empty($_GET['deletar'])){
	
	$lPC = new ListaSlideCategorias;
	$lPC->condicoes('', $_GET['deletar'], ListaSlideCategorias::ID);
	
	if($lPC->getTotal() > 0){
		
		try{
			$lPC->deletar($lPC->listar());
			$javaScript .= Aviso::criar("Categoria removida com sucesso!");
		}catch(Exception $e){
			
			$javaScript .= Aviso::criar($e->getMessage());	
			
		}
	
	
	}
	
}

$lPC = new ListaSlideCategorias;

if(isset($_GET['json'])){
	
	$cond = array();
	
	while($pC = $lPC->listar()){
		$rs['id'] 	= $pC->getId();
		$rs['nome'] = $pC->titulo;
		$cond[] = $rs;
	}
	
	echo JSON::_Encode($cond);
	exit;
	
}

$iTPC->createRepeticao("repetir->SlideCategorias");

if(!empty($_GET['busca']))
     $lPC->condicoes('', "%".$_GET['busca']."%", 'empresa', 'LIKE');

$iTPC->condicao("condicaoBusca", !empty($_SESSION['nivel']));

$iTPC->trocar("linkCadastrar.SlideCategoria", "?p=".$_GET['p']."&a=cadastrarSlideCategoria");

while($tx = $lPC->listar("ASC", ListaSlideCategorias::TITULO)){
	  
	   $iTPC->repetir();
	   
	   $iTPC->enterRepeticao()->condicao("condicaoRemover", !empty($_SESSION['nivel']));
	   
	   $bgColor = $lPC->getParametros()%2 == 0 ? '#FFFFFF' : '#EAEAEA';
	   $iTPC->enterRepeticao()->trocar("bgColorEmpresa", $bgColor);
	   
	   $iTPC->enterRepeticao()->trocar("id.SlideCategoria", $tx->getId());
	   $iTPC->enterRepeticao()->trocar("titulo.SlideCategoria", $tx->titulo);
	   
	   $iTPC->enterRepeticao()->trocar("linkAlterar.SlideCategoria", "?p=".$_GET['p']."&a=alterarSlideCategoria&categoria=".$tx->getId());
	   
	   $iTPC->enterRepeticao()->condicao("condicaoVisualizar", $tx->tipo == 1);
	 
}

$iTPC->trocar("linkVoltar.SlideCategoria", "?p=".$_GET['p']."&a=slides");

$botoes = $iTPC->cutParte('botoes');

$includePagina = $iTPC->concluir();

?>