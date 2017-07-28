<?php

importar("LojaVirtual.Produtos.Lista.ListaProdutoPedras");
importar("Utils.Ajax");

$tituloPagina = 'Produtos > Pedras';

$iLPM = new IFAdmin(new Arquivos(Sistema::$adminLayoutCaminhoDiretorio."/SistemaProdutos/listarProdutoPedras.html"));

$iLPM->trocar("linkDeletar.ProdutoPedra", "?p=".$_GET['p']."&a=".$_GET['a']."&");
$iLPM->trocar("linkBuscar.ProdutoPedra", "?p=".$_GET['p']."&");

if(!empty($_GET['deletar'])){
	
	$lPM = new ListaProdutoPedras;
	$lPM->condicoes('', $_GET['deletar'], ListaProdutoPedras::ID);
	
	if($lPM->getTotal() > 0){
		
		try{
			$lPM->deletar($lPM->listar());
			$javaScript .= Aviso::criar("Pedra removido com sucesso!");
		}catch(Exception $e){
			
			$javaScript .= Aviso::criar($e->getMessage());	
			
		}
	
	
	}
	
}

$lPM = new ListaProdutoPedras;
$iLPM->createRepeticao("repetir->ProdutoPedras");

if(!empty($_GET['busca']))
     $lPM->condicoes('', "%".$_GET['busca']."%", 'empresa', 'LIKE');

$iLPM->condicao("condicaoBusca", !empty($_SESSION['nivel']));

if(isset($_GET['json'])){
	
	$cond['lista'] 	= true;
	
	while($pM = $lPM->listar("ASC", ListaProdutoPedras::NOME)){
		$cond[$lPM->getParametros()+1]['id'] 	= $pM->getId();
		$cond[$lPM->getParametros()+1]['nome'] = $pM->nome;
	}
	
	$ajax = new Ajax;
	echo $ajax->getJSON()->converter($cond);
	exit;
	
}

$iLPM->trocar("linkCadastrar.ProdutoPedra", "?p=".$_GET['p']."&a=cadastrarProdutoPedra");

while($pM = $lPM->listar("ASC", ListaProdutoPedras::NOME)){
	  
	   $iLPM->repetir();
	   
	   $iLPM->enterRepeticao()->condicao("condicaoRemover", !empty($_SESSION['nivel']));
	   
	   $bgColor = $lPM->getParametros()%2 == 0 ? '#FFFFFF' : '#EAEAEA';
	   $iLPM->enterRepeticao()->trocar("bgColorEmpresa", $bgColor);
	   
	   $iLPM->enterRepeticao()->trocar("id.ProdutoPedra", $pM->getId());
	   $iLPM->enterRepeticao()->trocar("nome.ProdutoPedra", $pM->nome);
	   $iLPM->enterRepeticao()->trocar("linkAlterar.ProdutoPedra", "?p=".$_GET['p']."&a=alterarProdutoPedra&pedra=".$pM->getId());
	   
	   $iLPM->enterRepeticao()->condicao("condicaoVisualizar", $pM->tipo == 1);
	  
}

$iLPM->trocar("linkVoltar", "?p=".$_GET['p']."&a=produtos");

$botoes = $iLPM->cutParte('botoes');

$includePagina = $iLPM->concluir();

?>