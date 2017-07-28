<?php

importar("Utilidades.Lista.ListaVendedores");

$tituloPagina = 'Utilidades > Vendedores > Alterar';

$iAT = new IFAdmin(new Arquivos(Sistema::$adminLayoutCaminhoDiretorio."/SistemaUtilidades/vendedor.html"));

if(!empty($_POST)){
	
	$erro = '';	
	
    if(empty($_POST['nome']))
	    $erro = "<b>Nome</b> não preenchido!<br><br>";
	if(empty($_POST['email']))
	    $erro = "<b>E-mail</b> não preenchido!<br><br>";

	if(empty($erro)){
		
		$lT = new ListaVendedores;
		$lT->condicoes('', $_GET['vendedor'], ListaVendedores::ID);
		$tx = $lT->listar();
		
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
			
		$lT->alterar($tx);
		
	    $javaScript .= Aviso::criar("Vendedor salvo com sucesso!");
	
	}else{
		
		$javaScript .= Aviso::criar($erro);
		
	}
	
}

$lT = new ListaVendedores;
$t = $lT->condicoes('', $_GET['vendedor'], ListaVendedores::ID)->listar();

$iAT->trocar("nome", 		$t->nome);
$iAT->trocar("email", 		$t->email);
$iAT->trocar("msn", 		$t->msn);
$iAT->trocar("skype", 		$t->skype);
$iAT->trocar("telefone", 	$t->telefone);
$iAT->trocar("ramal", 		$t->ramal);
$iAT->trocar("ordem", 		$t->ordem);
$iAT->trocar("voip", 		$t->voip);

$iAT->trocar("linkVoltar", "?p=".$_GET['p']."&a=listarVendedores");
												   
if($t->getImagem()->getImage()->nome != '')
	$iAT->trocar("imagem", $t->getImagem()->getImage()->showHTML(200, 200));

$iAT->createJavaScript();
$javaScript .= $iAT->javaScript->concluir();

$includePagina = $iAT->concluir();

?>