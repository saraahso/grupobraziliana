<?php

importar("LojaVirtual.Produtos.Lista.ListaProdutoTamanhos");

$tituloPagina = 'Produtos > Tamanhos > Cadastrar';

$iCPCO = new IFAdmin(new Arquivos(Sistema::$adminLayoutCaminhoDiretorio."/SistemaProdutos/produtoTamanho.html"));

$lI 	= new ListaIdiomas;

if(!empty($_POST)){
	
	$erro = '';
	
	$lPCO = new ListaProdutoTamanhos;
	
	
    if(empty($_POST['nome']))
	    $erro = "<b>Nome</b> não preenchido!<br><br>";

	if(empty($erro)){
	
	    $pCo 				= new ProdutoTamanho;
		
		$pCo->nome 			= $_POST['nome'];
		
		if(!empty($_FILES['imagem']['name']))
			$pCo->setImagem(new Image(Arquivos::__OpenArquivoByTEMP($_FILES['imagem'])));
		
		$lPCO->inserir($pCo);
		
		while($i = $lI->listar()){
			
			$t = new Traducao;
			
			$t->setIdConteudo($pCo->getId());
			$t->setCampoConteudo(ListaProdutoTamanhos::NOME);
			$t->setTabelaConteudo($lPCO->getTabela());
			$t->conteudo = $pCo->nome;
			$t->traducao = $_POST['inome'][$i->getId()];
			$i->addTraducao($t);
			
		}
		
		$lI->resetCondicoes();
		$lI->setParametros(0);
	    
		$_POST = '';
		
	    $javaScript .= Aviso::criar("Tamanho salvo com sucesso!");
	
	}else{
		
		$javaScript .= Aviso::criar($erro);
		
	}
	
}


$iCPCO->trocar("linkVoltar", "?p=".$_GET['p']."&a=listarProdutoTamanhos");

$iCPCO->trocar("nome", $_POST['nome']);

$sub 	= "repetir->nome.ProdutoTamanhos.Idiomas";
$iCPCO->createRepeticao($sub);
while($i = $lI->listar()){
	
	$iCPCO->repetir($sub);
	
	$iCPCO->enterRepeticao($sub)->trocar("nome.Idioma", $i->nome);
	$iCPCO->enterRepeticao($sub)->trocar("id.Idioma", $i->getId());
	if(!empty($_POST)) 
		$iCPCO->enterRepeticao($sub)->trocar("nome.ProdutoTamanho.Idioma", $_POST['inome'][$i->getId()]);
	
}

$iCPCO->createJavaScript();
$javaScript .= $iCPCO->javaScript->concluir();

$includePagina = $iCPCO->concluir();

?>