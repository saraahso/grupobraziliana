<?php

importar("Utilidades.Publicidades.Slides.Lista.ListaSlides");

$tituloPagina = 'Utilidades > Publicidades > Slides';

$iTLP = new IFAdmin(new Arquivos(Sistema::$adminLayoutCaminhoDiretorio."/SistemaUtilidades/listarSlides.html"));

$iTLP->trocar("linkDeletar.Slide", "?p=".$_GET['p']."&a=".$_GET['a']."&");
$iTLP->trocar("linkBuscar.Slide", "?p=".$_GET['p']."&");

if(!empty($_GET['deletar'])){
	
	$lT = new ListaSlides;
	$lT->condicoes('', $_GET['deletar'], ListaSlides::ID);
	
	if($lT->getTotal() > 0){
		
		try{
			$lT->deletar($lT->listar());
			$javaScript .= Aviso::criar("Slide removido com sucesso!");
		}catch(Exception $e){
			
			$javaScript .= Aviso::criar($e->getMessage());	
			
		}
	
	
	}
	
}

$lT = new ListaSlides;
$iTLP->createRepeticao("repetir->Slides");

if(!empty($_GET['busca']))
     $lT->condicoes('', "%".$_GET['busca']."%", 'empresa', 'LIKE');

$iTLP->condicao("condicaoBusca", !empty($_SESSION['nivel']));

$iTLP->trocar("linkCadastrar.Slide", "?p=".$_GET['p']."&a=cadastrarSlide");

$lT->condicoes($a);

while($s = $lT->listar("ASC", ListaSlides::TITULO)){
	  
	   $iTLP->repetir();
	   
	   $iTLP->enterRepeticao()->condicao("condicaoRemover", !empty($_SESSION['nivel']));
	   
	   $bgColor = $lT->getParametros()%2 == 0 ? '#FFFFFF' : '#EAEAEA';
	   $iTLP->enterRepeticao()->trocar("bgColorEmpresa", $bgColor);
	  
	   $iTLP->enterRepeticao()->trocar("id.Slide", $s->getId());
	   $iTLP->enterRepeticao()->trocar("titulo.Slide", $s->titulo);
	   $iTLP->enterRepeticao()->trocar("linkVisualizar.Slide", "?p=".$_GET['p']."&a=listarSlides&slide=".$s->getId());
	   $iTLP->enterRepeticao()->trocar("linkAlterar.Slide", "?p=".$_GET['p']."&a=alterarSlide&slide=".$s->getId());
	   
	   $iTLP->enterRepeticao()->condicao("condicaoVisualizar", $s->tipo == 1);
	 
}

$iTLP->trocar("linkVoltar.Slide", "?p=".$_GET['p']."&a=slides");

$botoes = $iTLP->cutParte('botoes');

$includePagina = $iTLP->concluir();

?>