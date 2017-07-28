<?php

importar("Utilidades.Lista.ListaEventos");

$tituloPagina = 'Utilidades > Eventos - Agenda';

$ILT = new IFAdmin(new Arquivos(Sistema::$adminLayoutCaminhoDiretorio."/SistemaUtilidades/listarEventos.html"));

$ILT->trocar("linkDeletar.Evento", "?p=".$_GET['p']."&a=".$_GET['a']."&");
$ILT->trocar("linkBuscar.Evento", "?p=".$_GET['p']."&");

if(!empty($_GET['deletar'])){
	
	$lT = new ListaEventos;
	$lT->condicoes('', $_GET['deletar'], ListaEventos::ID);
	
	if($lT->getTotal() > 0){
		
		try{
			$lT->deletar($lT->listar());
			$javaScript .= Aviso::criar("Evento removido com sucesso!");
		}catch(Exception $e){
			
			$javaScript .= Aviso::criar($e->getMessage());	
			
		}
	
	
	}
	
}

$lT = new ListaEventos;
$ILT->createRepeticao("repetir->Eventos");

if(!empty($_GET['busca']))
     $lT->condicoes('', "%".$_GET['busca']."%", 'empresa', 'LIKE');

$ILT->condicao("condicaoBusca", !empty($_SESSION['nivel']));

$ILT->trocar("linkCadastrar.Evento", "?p=".$_GET['p']."&a=cadastrarEvento");

while($eve = $lT->listar("DESC", ListaEventos::DATA)){
	  
	   $ILT->repetir();
	   
	   $ILT->enterRepeticao()->condicao("condicaoRemover", !empty($_SESSION['nivel']));
	   
	   $bgColor = $lT->getParametros()%2 == 0 ? '#FFFFFF' : '#EAEAEA';
	   $ILT->enterRepeticao()->trocar("bgColorEmpresa", $bgColor);
	   
	   $ILT->enterRepeticao()->trocar("id.Evento", $eve->getId());
	   $ILT->enterRepeticao()->trocar("titulo.Evento", $eve->getTexto()->titulo);

	   $ILT->enterRepeticao()->trocar("linkAlterar.Evento", "?p=".$_GET['p']."&a=alterarEvento&evento=".$eve->getId());
	   
	   $ILT->enterRepeticao()->condicao("condicaoVisualizar", $eve->tipo == 1);
	 
}

$ILT->trocar("linkVoltar", "?p=".$_GET['p']."&a=utilidades");

$botoes = $ILT->cutParte('botoes');

$includePagina = $ILT->concluir();

?>