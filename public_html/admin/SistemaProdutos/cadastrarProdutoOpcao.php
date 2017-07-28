<?php

importar("LojaVirtual.Produtos.Opcoes.Lista.ListaProdutoOpcoes");

$tituloPagina = 'Produtos > Opções > Cadastrar';

$iCPO = new IFAdmin(new Arquivos(Sistema::$adminLayoutCaminhoDiretorio."/SistemaProdutos/produtoOpcao.html"));

$lI 	= new ListaIdiomas;

if(!empty($_POST)){
	
	$erro = '';
	
	$lPO = new ListaProdutoOpcoes;	
	
    if(empty($_POST['nome']))
	    $erro = "<b>Nome</b> não preenchido!<br><br>";

	if(empty($erro)){
	
	    $pO 				= new ProdutoOpcao;
		
		$pO->nome 			= addslashes(str_replace("\"", "'", $_POST['nome']));
		$pO->tipo 			= $_POST['tipo'];
		$pO->multi	= $_POST['selecionavel'] == ListaProdutoOpcoes::VALOR_MULTI_TRUE ? true : false;
		$pO->filtro	= $_POST['filtro'] == ListaProdutoOpcoes::VALOR_FILTRO_TRUE ? true : false;
		$pO->aberto	= $_POST['aberto'] == ListaProdutoOpcoes::VALOR_ABERTO_TRUE ? true : false;
		
		$lPO->inserir($pO);
		
		while($i = $lI->listar()){
			
			$t = new Traducao;
			
			$t->setIdConteudo($pO->getId());
			$t->setCampoConteudo(ListaProdutoOpcoes::NOME);
			$t->setTabelaConteudo($lPO->getTabela());
			$t->conteudo = $pO->nome;
			$t->traducao = $_POST['inome'][$i->getId()];
			$i->addTraducao($t);
			
		}
		
		$lI->resetCondicoes();
		$lI->setParametros(0);
	    
		$_POST = '';
		
	    $javaScript .= Aviso::criar("Opção salva com sucesso!");
	
	}else{
		
		$javaScript .= Aviso::criar($erro);
		
	}
	
}


$iCPO->trocar("linkVoltar", "?p=".$_GET['p']."&a=listarProdutoOpcoes");

$iCPO->trocar("nome", $_POST['nome']);
$iCPO->trocar("tipo", $_POST['tipo']);
$iCPO->trocar("selecionavel", $_POST['selecionavel']);
$iCPO->trocar("filtro", $_POST['filtro']);
$iCPO->trocar("aberto", $_POST['aberto']);

$sub 	= "repetir->nome.ProdutoOpcoes.Idiomas";
$iCPO->createRepeticao($sub);
while($i = $lI->listar()){
	
	$iCPO->repetir($sub);
	
	$iCPO->enterRepeticao($sub)->trocar("nome.Idioma", $i->nome);
	$iCPO->enterRepeticao($sub)->trocar("id.Idioma", $i->getId());
	if(!empty($_POST)) 
		$iCPO->enterRepeticao($sub)->trocar("nome.ProdutoOpcao.Idioma", $_POST['inome'][$i->getId()]);
	
}

$iCPO->createJavaScript();
$javaScript .= $iCPO->javaScript->concluir();

$includePagina = $iCPO->concluir();

?>