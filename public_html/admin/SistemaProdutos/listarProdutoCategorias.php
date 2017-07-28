<?php

importar("LojaVirtual.Categorias.Lista.ListaProdutoCategorias");
importar("Utils.Dados.JSON");

$tituloPagina = 'Produtos > Categorias';

$iLPC = new IFAdmin(new Arquivos(Sistema::$adminLayoutCaminhoDiretorio."/SistemaProdutos/listarProdutoCategorias.html"));

$iLPC->trocar("linkDeletar.ProdutoCategoria", "?p=".$_GET['p']."&a=".$_GET['a']."&");
$iLPC->trocar("linkBuscar.ProdutoCategoria", "?p=".$_GET['p']."&");

if(!empty($_GET['deletar'])){
	
	$lPC = new ListaProdutoCategorias;
	$lPC->condicoes('', $_GET['deletar'], ListaProdutoCategorias::ID);
	
	if($lPC->getTotal() > 0){
		
		try{
			$lPC->deletar($lPC->listar());
			$javaScript .= Aviso::criar("Categoria removida com sucesso!");
		}catch(Exception $e){
			
			$javaScript .= Aviso::criar($e->getMessage());	
			
		}
	
	
	}
	
}

$lPC = new ListaProdutoCategorias;
$iLPC->createRepeticao("repetir->ProdutoCategorias");

if(!empty($_GET['busca']))
     $lPC->condicoes('', "%".$_GET['busca']."%", 'empresa', 'LIKE');

$iLPC->condicao("condicaoBusca", !empty($_SESSION['nivel']));

if(!empty($_GET['categoria'])){
	
	$lPC->condicoes('', $_GET['categoria'], ListaProdutoCategorias::ID);
	if($lPC->getTotal() > 0)
		$cP = $lPC->listar();
	else
		$cP = new ProdutoCategoria;
	
}else
	$cP = new ProdutoCategoria;

$lPC = $cP->getSubCategorias();

if(isset($_GET['json'])){
	
	$cond = array();
	
	if(!empty($_GET['categoria'])){
		$rs['id'] 	= $cP->getIdCategoriaPai();
		$rs['nome'] 	= 'Voltar';
		$rs['filhos'] = '';
		$cond[] = $rs;
	}
	
	while($pC = $lPC->listar()){
		$rs['id'] 	= $pC->getId();
		$rs['nome'] = str_replace('"', "'", $pC->getNavegador());
		$rs['filhos'] = $pC->getSubCategorias()->getTotal();
		$cond[] = $rs;
	}
	
	echo JSON::_Encode($cond);
	exit;
	
}

$iLPC->trocar("linkCadastrar.ProdutoCategoria", "?p=".$_GET['p']."&a=cadastrarProdutoCategoria&categoria=".$cP->getId());

while($pC = $lPC->listar("ASC", ListaProdutoCategorias::ORDEM)){
	  
	  if(!empty($_POST['desabilitar'])){
		
			//Desabilitar
			if($_POST['desabilitar'][$pC->getId()])
				$pC->disponivel = true;
			else
				$pC->disponivel = false;
			//
			
			$lPC->alterar($pC);
		
		}
	  
	   $iLPC->repetir();
	   
	   $iLPC->enterRepeticao()->condicao("condicaoRemover", !empty($_SESSION['nivel']));
	   
	   $bgColor = $lPC->getParametros()%2 == 0 ? '#FFFFFF' : '#EAEAEA';
	   $iLPC->enterRepeticao()->trocar("bgColorEmpresa", $bgColor);
	   
	   $iLPC->enterRepeticao()->trocar("id.ProdutoCategoria", $pC->getId());
	   $iLPC->enterRepeticao()->trocar("nome.ProdutoCategoria", $pC->nome);
	   $iLPC->enterRepeticao()->trocar("linkVisualizar.ProdutoCategoria", "?p=".$_GET['p']."&a=listarProdutoCategorias&categoria=".$pC->getId());
	   $iLPC->enterRepeticao()->trocar("linkAlterar.ProdutoCategoria", "?p=".$_GET['p']."&a=alterarProdutoCategoria&categoria=".$pC->getId());
	   $iLPC->enterRepeticao()->trocar("disponivel.ProdutoCategoria", $pC->disponivel ? 'checked' : '');
	   $iLPC->enterRepeticao()->trocar("bg.Disponivel.ProdutoCategoria", !$pC->disponivel ? '#FF0000' : '');
	   
	   $iLPC->enterRepeticao()->condicao("condicaoVisualizar", $pC->tipo == 1);
	  
}

$iLPC->trocar("idCategoriaPai.ProdutoCategoria", $cP->getIdCategoriaPai());
$iLPC->trocar("id.ProdutoCategoria", $cP->getId());

$iLPC->trocar("linkVoltar", "?p=".$_GET['p']."&a=listarProdutoCategorias&categoria=".$cP->getIdCategoriaPai());

$botoes = $iLPC->cutParte('botoes');

$includePagina = $iLPC->concluir();

?>