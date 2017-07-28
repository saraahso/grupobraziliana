<?php

importar("LojaVirtual.Produtos.Lista.ListaProdutoTamanhos");
importar("Utils.Ajax");

$tituloPagina = 'Produtos > Tamanhos';

$iLPM = new IFAdmin(new Arquivos(Sistema::$adminLayoutCaminhoDiretorio."/SistemaProdutos/listarProdutoTamanhos.html"));

$iLPM->trocar("linkDeletar.ProdutoTamanho", "?p=".$_GET['p']."&a=".$_GET['a']."&");
$iLPM->trocar("linkBuscar.ProdutoTamanho", "?p=".$_GET['p']."&");

if(!empty($_GET['deletar'])){
	
	$lPM = new ListaProdutoTamanhos;
	$lPM->condicoes('', $_GET['deletar'], ListaProdutoTamanhos::ID);
	
	if($lPM->getTotal() > 0){
		
		try{
			$lPM->deletar($lPM->listar());
			$javaScript .= Aviso::criar("Tamanho removido com sucesso!");
		}catch(Exception $e){
			
			$javaScript .= Aviso::criar($e->getMessage());	
			
		}
	
	
	}
	
}

$lPM = new ListaProdutoTamanhos;
$iLPM->createRepeticao("repetir->ProdutoTamanhos");

if(!empty($_GET['busca']))
     $lPM->condicoes('', "%".$_GET['busca']."%", 'empresa', 'LIKE');

$iLPM->condicao("condicaoBusca", !empty($_SESSION['nivel']));

if(isset($_GET['json'])){
	
	$cond['lista'] 	= true;
	
	while($pM = $lPM->listar("ASC", ListaProdutoTamanhos::NOME)){
		$cond[$lPM->getParametros()+1]['id'] 	= $pM->getId();
		$cond[$lPM->getParametros()+1]['nome'] = $pM->nome;
	}
	
	$ajax = new Ajax;
	echo $ajax->getJSON()->converter($cond);
	exit;
	
}

$iLPM->trocar("linkCadastrar.ProdutoTamanho", "?p=".$_GET['p']."&a=cadastrarProdutoTamanho");

while($pM = $lPM->listar("ASC", ListaProdutoTamanhos::NOME)){
	  
	   $iLPM->repetir();
	   
	   $iLPM->enterRepeticao()->condicao("condicaoRemover", !empty($_SESSION['nivel']));
	   
	   $bgColor = $lPM->getParametros()%2 == 0 ? '#FFFFFF' : '#EAEAEA';
	   $iLPM->enterRepeticao()->trocar("bgColorEmpresa", $bgColor);
	   
	   $iLPM->enterRepeticao()->trocar("id.ProdutoTamanho", $pM->getId());
	   $iLPM->enterRepeticao()->trocar("nome.ProdutoTamanho", $pM->nome);
	   $iLPM->enterRepeticao()->trocar("linkAlterar.ProdutoTamanho", "?p=".$_GET['p']."&a=alterarProdutoTamanho&tamanho=".$pM->getId());
	   
	   $iLPM->enterRepeticao()->condicao("condicaoVisualizar", $pM->tipo == 1);
	  
}

$iLPM->trocar("linkVoltar", "?p=".$_GET['p']."&a=produtos");

$botoes = $iLPM->cutParte('botoes');

$includePagina = $iLPM->concluir();

?>