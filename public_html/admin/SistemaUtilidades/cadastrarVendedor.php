<?php

importar("Utilidades.Lista.ListaVendedores");

$tituloPagina = 'Utilidades > Vendedores > Cadastrar';

$iCT = new IFAdmin(new Arquivos(Sistema::$adminLayoutCaminhoDiretorio."/SistemaUtilidades/vendedor.html"));

if(!empty($_POST)){
	
	$erro = '';

	if(empty($_POST['nome']))
	    $erro = "<b>Nome</b> não preenchido!<br><br>";
	if(empty($_POST['email']))
	    $erro = "<b>E-mail</b> não preenchido!<br><br>";

	if(empty($erro)){
	
	    $tx 				= new Vendedor;
		
		$tx->nome		 	= $_POST['nome'];	
		$tx->email 			= $_POST['email'];	
		$tx->msn			= $_POST['msn'];
		$tx->skype		 	= $_POST['skype'];	
		$tx->voip 			= $_POST['voip'];	
		$tx->telefone		= $_POST['telefone'];
		$tx->ramal		 	= $_POST['ramal'];	
		$tx->ordem 			= $_POST['ordem'];	
		
		if(!empty($_FILES['imagem']['name']))
			$tx->getImagem()->setImage(new Image(Arquivos::__OpenArquivoByTEMP($_FILES['imagem'])));
		
		$lT				= new ListaVendedores;
		$lT->inserir($tx);
	    
		$_POST = '';
		
	    $javaScript .= Aviso::criar("Vendedor salvo com sucesso!");
	
	}else{
		
		$javaScript .= Aviso::criar($erro);
		
	}
	
}


$iCT->trocar("linkVoltar", "?p=".$_GET['p']."&a=listarVendedores");

$iCT->trocar("nome", 		$_POST['nome']);
$iCT->trocar("email", 		$_POST['email']);
$iCT->trocar("msn", 		$_POST['msn']);
$iCT->trocar("skype", 		$_POST['skype']);
$iCT->trocar("telefone", 	$_POST['telefone']);
$iCT->trocar("ramal", 		$_POST['ramal']);
$iCT->trocar("ordem", 		$_POST['ordem']);
$iCT->trocar("voip", 		$_POST['voip']);


$iCT->createJavaScript();
$javaScript .= $iCT->javaScript->concluir();

$includePagina = $iCT->concluir();

?>