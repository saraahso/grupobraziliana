<?php

importar("LojaVirtual.Produtos.Opcoes.Lista.ListaProdutoOpcaoValores");
importar("Utils.Dados.JSON");

$tituloPagina = 'Produtos > Opções > Valores';

$iLPOV = new IFAdmin(new Arquivos(Sistema::$adminLayoutCaminhoDiretorio."/SistemaProdutos/listarProdutoOpcaoValores.html"));

$iLPOV->trocar("linkDeletar.ProdutoOpcaoValor", "?p=".$_GET['p']."&a=".$_GET['a']."&opcao=".$_GET['opcao']."&");
$iLPOV->trocar("linkBuscar.ProdutoOpcaoValor", "?p=".$_GET['p']."&opcao=".$_GET['opcao']."&");

if(!empty($_GET['deletar'])){
	
	$lPOV = new ListaProdutoOpcaoValores;
	$lPOV->condicoes('', $_GET['deletar'], ListaProdutoOpcaoValores::ID);
	
	if($lPOV->getTotal() > 0){
		
		try{
			$lPOV->deletar($lPOV->listar());
			$javaScript .= Aviso::criar("Valor removido com sucesso!");
		}catch(Exception $e){
			
			$javaScript .= Aviso::criar($e->getMessage());	
			
		}
	
	
	}
	
}

$lPOV = new ListaProdutoOpcaoValores;
$lPOV->condicoes('', $_GET['opcao'], ListaProdutoOpcaoValores::OPCAO);
$iLPOV->createRepeticao("repetir->ProdutoOpcaoValores");

if(!empty($_GET['busca']))
     $lPOV->condicoes('', "%".$_GET['busca']."%", 'empresa', 'LIKE');

$iLPOV->condicao("condicaoBusca", !empty($_SESSION['nivel']));

if(isset($_GET['json'])){
	
	$cond = array();
	
	while($pOV = $lPOV->listar("ASC", ListaProdutoOpcaoValores::VALOR)){
		$rs['id'] 	= $pOV->getId();
		$rs['valor'] = $pOV->valor;
		$cond[] = $rs;
	}
	
	echo JSON::_Encode($cond);
	exit;
	
}

$iLPOV->trocar("linkCadastrar.ProdutoOpcaoValor", "?p=".$_GET['p']."&a=cadastrarProdutoOpcaoValor&opcao=".$_GET['opcao']);

while($pOV = $lPOV->listar("ASC", ListaProdutoOpcaoValores::VALOR)){
	  
	   $iLPOV->repetir();
	   
	   $iLPOV->enterRepeticao()->condicao("condicaoRemover", !empty($_SESSION['nivel']));
	   
	   $bgColor = $lPOV->getParametros()%2 == 0 ? '#FFFFFF' : '#EAEAEA';
	   $iLPOV->enterRepeticao()->trocar("bgColorEmpresa", $bgColor);
	   
	   $iLPOV->enterRepeticao()->trocar("id.ProdutoOpcaoValor", $pOV->getId());
	   $iLPOV->enterRepeticao()->trocar("valor.ProdutoOpcaoValor", $pOV->valor);
	   $iLPOV->enterRepeticao()->trocar("linkAlterar.ProdutoOpcaoValor", "?p=".$_GET['p']."&a=alterarProdutoOpcaoValor&valor=".$pOV->getId()."&opcao=".$_GET['opcao']);
	   
	   $iLPOV->enterRepeticao()->condicao("condicaoVisualizar", $pOV->tipo == 1);
	  
}

$iLPOV->trocar("linkVoltar", "?p=".$_GET['p']."&a=listarProdutoOpcoes");

$botoes = $iLPOV->cutParte('botoes');

$includePagina = $iLPOV->concluir();

?>