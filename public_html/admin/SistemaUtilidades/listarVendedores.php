<?php

importar("Utilidades.Lista.ListaVendedores");

$tituloPagina = 'Utilidades > Vendedores';

$ILT = new IFAdmin(new Arquivos(Sistema::$adminLayoutCaminhoDiretorio."/SistemaUtilidades/listarVendedores.html"));

$ILT->trocar("linkDeletar.Vendedores", "?p=".$_GET['p']."&a=".$_GET['a']."&");
$ILT->trocar("linkBuscar.Vendedores", "?p=".$_GET['p']."&");

if(!empty($_GET['deletar'])){
	
	$lT = new ListaVendedores;
	$lT->condicoes('', $_GET['deletar'], ListaVendedores::ID);
	
	if($lT->getTotal() > 0){
		
		try{
			$lT->deletar($lT->listar());
			$javaScript .= Aviso::criar("Vendedor removido com sucesso!");
		}catch(Exception $e){
			
			$javaScript .= Aviso::criar($e->getMessage());	
			
		}
	
	
	}
	
}

$lT = new ListaVendedores;
$ILT->createRepeticao("repetir->Vendedores");

if(!empty($_GET['busca']))
$lT->condicoes('', "%".$_GET['busca']."%", 'empresa', 'LIKE');

$ILT->condicao("condicaoBusca", !empty($_SESSION['nivel']));

$ILT->trocar("linkCadastrar.Vendedores", "?p=".$_GET['p']."&a=cadastrarVendedor");

$lT->condicoes($a);

while($tx = $lT->listar("ASC", ListaVendedores::NOME)){
	  
	   $ILT->repetir();
	   
	   $ILT->enterRepeticao()->condicao("condicaoRemover", !empty($_SESSION['nivel']));
	   
	   $bgColor = $lT->getParametros()%2 == 0 ? '#FFFFFF' : '#EAEAEA';
	   $ILT->enterRepeticao()->trocar("bgColorEmpresa", $bgColor);
	   
	   $ILT->enterRepeticao()->trocar("id.Vendedores", $tx->getId());
	   $ILT->enterRepeticao()->trocar("titulo.Vendedores", $tx->nome);
	   $ILT->enterRepeticao()->trocar("linkVisualizar.Vendedores", "?p=".$_GET['p']."&a=listarVendedor&vendedor=".$tx->getId());
	   $ILT->enterRepeticao()->trocar("linkAlterar.Vendedores", "?p=".$_GET['p']."&a=alterarVendedor&vendedor=".$tx->getId());
	   
	   $ILT->enterRepeticao()->condicao("condicaoVisualizar", $tx->tipo == 1);
	 
}

$botoes = $ILT->cutParte('botoes');
$ILT->trocar('link.Voltar', "?p=".$_GET['p']."&a=produtos");

$includePagina = $ILT->concluir();

?>