<?php

importar("Utilidades.Publicidades.Mailing.Lista.ListaMailings");

$sCituloPagina = 'Utilidades > Publicidades > Mailings > Enviar';

$iTEM = new IFAdmin(new Arquivos(Sistema::$adminLayoutCaminhoDiretorio."/SistemaUtilidades/enviarMailing.html"));


if(!empty($_POST)){
	
	$erro = '';	
	
    if(empty($_POST['assunto']))
	    $erro = "<b>Assunto</b> não preenchido!<br><br>";

	if(empty($erro)){
		
		$lSC = new ListaMailings;
		$lSC->condicoes('', $_GET['mailing'], ListaMailings::ID);
		$sC = $lSC->listar();
		
		$sC->getTexto()->titulo 	= $_POST['assunto'];
		$sC->getTexto()->texto		= str_replace("\\", "", eregi_replace('\.\./', Sistema::$caminhoURL, $_POST['mensagem']));
		
		$lSC->alterar($sC);
		
		$sC->enviarPacote(Sistema::$nomeEmpresa."<".Sistema::$emailEmpresa.">", true);
		
		$con = BDConexao::__Abrir();
		$con->executar("SELECT * FROM ".Sistema::$BDPrefixo."mailing_pacotes_envio WHERE mailing = '".$_GET['mailing']."'");
		
		if($con->getTotal() == 0)
	    	$javaScript .= Aviso::criar("Mailing enviado com sucesso!");
		else
			$javaScript .= Aviso::criar("Para continuar o envio, clique em Enviar daqui 1 hora!");
	
	}else{
		
		$javaScript .= Aviso::criar($erro);
		
	}
	
}

$lSC = new ListaMailings;
$sC = $lSC->condicoes('', $_GET['mailing'], ListaMailings::ID)->listar();

$iTEM->trocar("assunto",		$sC->getTexto()->titulo);
$iTEM->trocar("titulo.PacoteMailing",			$sC->getPacote()->titulo);

$con = BDConexao::__Abrir();
$con->executar("SELECT * FROM ".Sistema::$BDPrefixo."mailing_pacotes_envio WHERE mailing = '".$_GET['mailing']."'");
$iTEM->trocar("total.Mailing",			$con->getTotal() == 0 ? 0 : $sC->getPacote()->getEmails()->getTotal()-$con->getTotal());
$iTEM->trocar("total.PacoteMailing",		$sC->getPacote()->getEmails()->getTotal());

$iTEM->trocar("mensagem",		$sC->getTexto()->texto);
$iTEM->trocar("status",			$sC->getStatus() == 1 ? 'Parado' : 'Em Processo');

$iTEM->trocar("linkVoltar", "?p=".$_GET['p']."&a=listarMailings");

$iTEM->createJavaScript();
$javaScript .= $iTEM->javaScript->concluir();

$includePagina = $iTEM->concluir();

?>