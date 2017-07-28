<?php

importar("Utilidades.Publicidades.Mailing.Lista.ListaMailings");
importar("Utilidades.Publicidades.Mailing.Lista.ListaPacoteMailings");

$tituloPagina = 'Utilidades > Publicidades > Mailings > Criar';

$iTCM = new IFAdmin(new Arquivos(Sistema::$adminLayoutCaminhoDiretorio."/SistemaUtilidades/mailing.html"));

if(!empty($_POST)){
	
	$erro = '';

	
    if(empty($_POST['assunto']))
	    $erro = "<b>Assunto</b> não preenchido!<br><br>";
	elseif(empty($_POST['pacote']))
	    $erro = "<b>Pacote</b> não escolhido!<br><br>";

	if(empty($erro)){
	
	    $sC 			= new Mailing;
		
		$sC->getTexto()->titulo = $_POST['assunto'];
		$sC->getTexto()->texto 	= $_POST['mensagem'];
		
		$lPM = new ListaPacoteMailings;
		$lPM->condicoes('', $_POST['pacote'], ListaPacoteMailings::ID);
		$sC->setPacote($lPM->listar());
		
		$lS				= new ListaMailings;
		$lS->inserir($sC);
	    
		$_POST = '';
		
	    $javaScript .= Aviso::criar("Mailing salva com sucesso!");
	
	}else{
		
		$javaScript .= Aviso::criar($erro);
		
	}
	
}


$iTCM->trocar("linkVoltar", "?p=".$_GET['p']."&a=listarMailings");

$lPM = new ListaPacoteMailings;
$iTCM->createRepeticao("repetir->PacoteMailings");
while($pM = $lPM->listar("ASC", ListaPacoteMailings::TITULO)){
	
	$iTCM->repetir();
	$iTCM->enterRepeticao()->trocar("id.PacoteMailing", $pM->getId());
	$iTCM->enterRepeticao()->trocar("titulo.PacoteMailing", $pM->titulo);
	
}

$iTCM->trocar("assunto", $_POST['assunto']);
$iTCM->trocar("pacote", $_POST['pacote']);
$iTCM->trocar("mensagem", $_POST['mensagem']);

$iTCM->createJavaScript();
$javaScript .= $iTCM->javaScript->concluir();

$includePagina = $iTCM->concluir();

?>