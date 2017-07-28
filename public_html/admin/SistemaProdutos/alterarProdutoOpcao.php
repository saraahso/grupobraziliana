<?php

importar("LojaVirtual.Produtos.Opcoes.Lista.ListaProdutoOpcoes");

$tituloPagina = 'Produtos > Opções > Alterar';

$iAPO = new IFAdmin(new Arquivos(Sistema::$adminLayoutCaminhoDiretorio."/SistemaProdutos/produtoOpcao.html"));

$lI = new ListaIdiomas;

if(!empty($_POST)){
	
	$erro = '';	
	
    if(empty($_POST['nome']))
	    $erro = "<b>Nome</b> não preenchido!<br><br>";

	if(empty($erro)){
		
		$lPO = new ListaProdutoOpcoes;
		$lPO->condicoes('', $_GET['opcao'], ListaProdutoOpcoes::ID);
		$pO = $lPO->listar();
		
		$pO->nome 			= addslashes(str_replace("\"", "'", $_POST['nome']));
		$pO->tipo 			= $_POST['tipo'];
		$pO->multi	= $_POST['selecionavel'] == ListaProdutoOpcoes::VALOR_MULTI_TRUE ? true : false;
		$pO->filtro	= $_POST['filtro'] == ListaProdutoOpcoes::VALOR_FILTRO_TRUE ? true : false;
		$pO->aberto	= $_POST['aberto'] == ListaProdutoOpcoes::VALOR_ABERTO_TRUE ? true : false;
		
		$lPO->alterar($pO);
		
		while($i = $lI->listar()){
			
			$t = $i->getTraducaoById(ListaProdutoOpcoes::NOME, $lPO->getTabela(), $pO->getId());
			
			if($t->getId()){
				
				$t->conteudo = $pO->nome;
				$t->traducao = $_POST['inome'][$i->getId()];
				$i->getTraducoes()->alterar($t);
			
			}else{
				
				$t = new Traducao;
				
				$t->conteudo = $pO->nome;
				$t->traducao = $_POST['inome'][$i->getId()];
				$t->setIdConteudo($pO->getId());
				$t->setCampoConteudo(ListaProdutoOpcoes::NOME);
				$t->setTabelaConteudo($lPO->getTabela());
				$i->addTraducao($t);
				
			}
			
		}
		
		$lI->setParametros(0);
		
	    $javaScript .= Aviso::criar("Opção salva com sucesso!");
	
	}else{
		
		$javaScript .= Aviso::criar($erro);
		
	}
	
}

$lPO = new ListaProdutoOpcoes;
$pO = $lPO->condicoes('', $_GET['opcao'], ListaProdutoOpcoes::ID)->listar();

$iAPO->trocar("nome", $pO->nome);
$iAPO->trocar("tipo", $pO->tipo);
$iAPO->trocar("selecionavel", $pO->multi);
$iAPO->trocar("filtro", $pO->filtro);
$iAPO->trocar("aberto", $pO->aberto);

$sub 	= "repetir->nome.ProdutoOpcoes.Idiomas";
$iAPO->createRepeticao($sub);
while($i = $lI->listar()){
	
	$iAPO->repetir($sub);
	
	$iAPO->enterRepeticao($sub)->trocar("nome.Idioma", $i->nome);
	$iAPO->enterRepeticao($sub)->trocar("id.Idioma", $i->getId());
	$iAPO->enterRepeticao($sub)->trocar("nome.ProdutoOpcao.Idioma", $i->getTraducaoById(ListaProdutoOpcoes::NOME, $lPO->getTabela(), $pO->getId())->traducao);
	
}

$iAPO->trocar("linkVoltar", "?p=".$_GET['p']."&a=listarProdutoOpcoes");

$iAPO->createJavaScript();
$javaScript .= $iAPO->javaScript->concluir();

$includePagina = $iAPO->concluir();

?>