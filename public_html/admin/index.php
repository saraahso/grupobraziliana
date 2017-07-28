<?php

include("../lib.conf/includes.php");

importar("Geral.Idiomas.Lista.ListaIdiomas");

importar("InterFaces.IFAdmin");

importar("Utils.Dados.DataHora");

importar("JavaScript.Alertas.Aviso");

importar("Geral.Lista.ListaUsuarios");

$erro = false;



if(isset($_POST['loginG'])){
	
	$aR[1] = array("campo" => ListaUsuarios::LOGIN, 'valor' => addslashes($_POST['loginG']));
	$aR[2] = array("campo" => ListaUsuarios::SENHA, 'valor' => str_replace("--", "", $_POST['senhaG']));
	
	$lU = new ListaUsuarios;
	$lU->condicoes($aR);
	
	if($lU->getTotal() > 0){

		$u = $lU->listar();
		
		$user_login = $u->login;
		$_SESSION['user_login'] = $user_login;
		

		$nivel = $u->nivel;
		$_SESSION['nivel'] = $nivel;
		

		$idUsuario = $u->getId();
		$_SESSION['idUsuario'] = $idUsuario;

		

	}else

	    $erro = true;

	

}



if(isset($_GET['logout'])){

   session_destroy();

   header("Location: ./");

}

if(!empty($_SESSION['user_login']) || isset($_GET['uploadFlash']) || isset($_GET['json'])){

	if($_SESSION['nivel'] == 4 && ($_GET['p'] != 'SistemaConfiguracoes' || $_GET['a'] != 'produtos'))
		header("Location: /admin/?p=SistemaConfiguracoes&a=produtos");
	
	$caminhoT = Sistema::$adminLayoutCaminhoDiretorio;


	$tem = new IFAdmin(new Arquivos($caminhoT."default.html"));
	

	if(date("H") >= 06 && date("H") <= 11) $nomeEntrada = "Bom dia";

	elseif(date("H") >= 12 && date("H") <= 18) $nomeEntrada = "Boa tarde";

	elseif(date("H") >= 19 || date("H") <= 5) $nomeEntrada = "Boa noite";
	

	$nomeEntrada .= " ".$_SESSION['user_login'];

	$tem->trocar("nomeEntrada", $nomeEntrada);

	$tem->trocar("idEmpresa", $_SESSION['id']);

	$tem->trocar("idUsuario", $_SESSION['idUsuario']);

	

	$d = new DataHora;

	$tem->trocar("data", $d->diaSemanaExtenso().", ".$d->mostrar("d")." de ".$d->mesExtenso().", ".$d->mostrar("Y"));
	
	$tem->condicao('condicao->1.Nivel', $_SESSION['nivel'] == 1);
	$tem->condicao('condicao->4.Nivel', $_SESSION['nivel'] == 4);
	
	$tem->condicao("condicaoMenu1", ereg('Produtos', 		$_GET['p']));
	$tem->condicao("condicaoMenu2", ereg('Utilidades',		$_GET['p']));
	$tem->condicao("condicaoMenu3", ereg('Configuracoes', 	$_GET['p']));
	$tem->condicao("condicaoMenu4", ereg('Clientes', 		$_GET['p']));
	$tem->condicao("condicaoMenu5", ereg('Pedidos', 		$_GET['p']));
	$tem->condicao("condicaoMenu6", ereg('Relatorios', 		$_GET['p']));
	
	$includePagina = true;

	$tem->condicao("condicaoCorpo",!empty($_GET['p']) && !empty($_GET['a'])); 

	if(!empty($_GET['p']) && !empty($_GET['a'])){

		$p = $_GET['p']."/".$_GET['a']; 
		include($p.".php");		

	}



	$tem->trocar("tituloPagina", $tituloPagina);

	$tem->trocar("botoes", $botoes);

	$tem->trocar("includePagina", $includePagina);
	
	$tem->trocar("javaScriptCodigo", $javaScript);

	

	$tem->mostrar();

	

}else{

	

	$tem = new IFAdmin(new Arquivos(Sistema::$adminLayoutCaminhoDiretorio."index.html"));

	

	if($erro)

	   $javaScript .= Aviso::criar("Login ou senha inválido!");

	

	$tem->trocar("javaScriptCodigo", "<script> window.addEvent('domready', function(){ ".$javaScript." }); </script>");

	

	$tem->mostrar();

	

}

?>