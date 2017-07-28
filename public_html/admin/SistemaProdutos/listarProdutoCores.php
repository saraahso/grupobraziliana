<?php

importar("LojaVirtual.Produtos.Lista.ListaProdutoCores");
importar("Utils.Ajax");

$tituloPagina = 'Produtos > Cores';

$iLPM = new IFAdmin(new Arquivos(Sistema::$adminLayoutCaminhoDiretorio."/SistemaProdutos/listarProdutoCores.html"));

$iLPM->trocar("linkDeletar.ProdutoCor", "?p=".$_GET['p']."&a=".$_GET['a']."&");
$iLPM->trocar("linkBuscar.ProdutoCor", "?p=".$_GET['p']."&");

if(!empty($_GET['deletar'])){
	
	$lPM = new ListaProdutoCores;
	$lPM->condicoes('', $_GET['deletar'], ListaProdutoCores::ID);
	
	if($lPM->getTotal() > 0){
		
		try{
			$lPM->deletar($lPM->listar());
			$javaScript .= Aviso::criar("Marca removida com sucesso!");
		}catch(Exception $e){
			
			$javaScript .= Aviso::criar($e->getMessage());	
			
		}
	
	
	}
	
}

$lPM = new ListaProdutoCores;
$iLPM->createRepeticao("repetir->ProdutoCores");

if(!empty($_GET['busca']))
     $lPM->condicoes('', "%".$_GET['busca']."%", 'empresa', 'LIKE');

$iLPM->condicao("condicaoBusca", !empty($_SESSION['nivel']));

if(isset($_GET['json'])){
	
	$cond['lista'] 	= true;
	
	while($pM = $lPM->listar("ASC", ListaProdutoCores::NOME)){
		$cond[$lPM->getParametros()+1]['id'] 	= $pM->getId();
		$cond[$lPM->getParametros()+1]['nome'] = $pM->nome;
	}
	
	$ajax = new Ajax;
	echo $ajax->getJSON()->converter($cond);
	exit;
	
}

$iLPM->trocar("linkCadastrar.ProdutoCor", "?p=".$_GET['p']."&a=cadastrarProdutoCor");

while($pM = $lPM->listar("ASC", ListaProdutoCores::NOME)){
	  
	   $iLPM->repetir();
	   
	   $iLPM->enterRepeticao()->condicao("condicaoRemover", !empty($_SESSION['nivel']));
	   
	   $bgColor = $lPM->getParametros()%2 == 0 ? '#FFFFFF' : '#EAEAEA';
	   $iLPM->enterRepeticao()->trocar("bgColorEmpresa", $bgColor);
	   
	   $iLPM->enterRepeticao()->trocar("id.ProdutoCor", $pM->getId());
	   $iLPM->enterRepeticao()->trocar("nome.ProdutoCor", $pM->nome);
	   $iLPM->enterRepeticao()->trocar("linkAlterar.ProdutoCor", "?p=".$_GET['p']."&a=alterarProdutoCor&cor=".$pM->getId());
	   
	   $iLPM->enterRepeticao()->condicao("condicaoVisualizar", $pM->tipo == 1);
	  
}

$iLPM->trocar("linkVoltar", "?p=".$_GET['p']."&a=produtos");

$botoes = $iLPM->cutParte('botoes');

$includePagina = $iLPM->concluir();

?>