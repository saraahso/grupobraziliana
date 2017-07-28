<?php

importar("LojaVirtual.Produtos.Opcoes.Lista.ListaProdutoOpcoes");
importar("Utils.Dados.JSON");

$tituloPagina = 'Produtos > Opções';

$iLPO = new IFAdmin(new Arquivos(Sistema::$adminLayoutCaminhoDiretorio."/SistemaProdutos/listarProdutoOpcoes.html"));

$iLPO->trocar("linkDeletar.ProdutoOpcao", "?p=".$_GET['p']."&a=".$_GET['a']."&");
$iLPO->trocar("linkBuscar.ProdutoOpcao", "?p=".$_GET['p']."&");

if(!empty($_GET['deletar'])){
	
	$lPO = new ListaProdutoOpcoes;
	$lPO->condicoes('', $_GET['deletar'], ListaProdutoOpcoes::ID);
	
	if($lPO->getTotal() > 0){
		
		try{
			$lPO->deletar($lPO->listar());
			$javaScript .= Aviso::criar("Opção removida com sucesso!");
		}catch(Exception $e){
			
			$javaScript .= Aviso::criar($e->getMessage());	
			
		}
	
	
	}
	
}

$lPO = new ListaProdutoOpcoes;
$iLPO->createRepeticao("repetir->ProdutoOpcoes");

if(!empty($_GET['busca']))
     $lPO->condicoes('', "%".$_GET['busca']."%", 'empresa', 'LIKE');

$iLPO->condicao("condicaoBusca", !empty($_SESSION['nivel']));

if(isset($_GET['json'])){
	
	$cond 	= array();
	
	while($pO = $lPO->listar("ASC", ListaProdutoOpcoes::NOME)){
		$rs['id'] 	= $pO->getId();
		$rs['nome'] = $pO->nome;
		$cond[] = $rs;
	}
	
	echo JSON::_Encode($cond);
	exit;
	
}

$iLPO->trocar("linkCadastrar.ProdutoOpcao", "?p=".$_GET['p']."&a=cadastrarProdutoOpcao");

while($pO = $lPO->listar("ASC", ListaProdutoOpcoes::NOME)){
	  
	   $iLPO->repetir();
	   
	   $iLPO->enterRepeticao()->condicao("condicaoRemover", !empty($_SESSION['nivel']));
	   
	   $bgColor = $lPO->getParametros()%2 == 0 ? '#FFFFFF' : '#EAEAEA';
	   $iLPO->enterRepeticao()->trocar("bgColorEmpresa", $bgColor);
	   
	   $iLPO->enterRepeticao()->trocar("id.ProdutoOpcao", $pO->getId());
	   $iLPO->enterRepeticao()->trocar("nome.ProdutoOpcao", $pO->nome);
	   $iLPO->enterRepeticao()->trocar("linkVisualizar.ProdutoOpcaoValores.ProdutoOpcao", "?p=".$_GET['p']."&a=listarProdutoOpcaoValores&opcao=".$pO->getId());
	   $iLPO->enterRepeticao()->trocar("linkAlterar.ProdutoOpcao", "?p=".$_GET['p']."&a=alterarProdutoOpcao&opcao=".$pO->getId());
	   
	   $iLPO->enterRepeticao()->condicao("condicaoVisualizar", $pO->tipo == 1);
	  
}

$iLPO->trocar("linkVoltar", "?p=".$_GET['p']."&a=produtos");

$botoes = $iLPO->cutParte('botoes');

$includePagina = $iLPO->concluir();

?>