<?php

importar("LojaVirtual.Produtos.Opcoes.Lista.ListaProdutoOpcoes");
importar("LojaVirtual.Produtos.Opcoes.Lista.ListaProdutoOpcaoValores");

$tituloPagina = 'Produtos > Opções > Valores > Cadastrar';

$iCPOV = new IFAdmin(new Arquivos(Sistema::$adminLayoutCaminhoDiretorio."/SistemaProdutos/produtoOpcaoValor.html"));

$lI 	= new ListaIdiomas;

$lPO	= new ListaProdutoOpcoes;
$lPO->condicoes('', $_GET['opcao'], ListaProdutoOpcoes::ID);
if($lPO->getTotal() > 0)
	$pO = $lPO->listar();
else{
	header("Location: ?p=SistemaProdutos&a=listarProdutoOpcoes");
	exit;
}

if(!empty($_POST)){
	
	$erro = '';
	
	$lPOV = new ListaProdutoOpcaoValores;	
	
    if(empty($_POST['valor']))
	    $erro = "<b>Valor</b> não preenchido!<br><br>";

	if(empty($erro)){
	
	    $pOV 				= new ProdutoOpcaoValor;
		
		$pOV->valor 		= addslashes(str_replace("\"", "'", $_POST['valor']));
		$pOV->cor 			= $_POST['cor'];
		
		if(!empty($_FILES['imagem']['name']))
			$pOV->setImagem(new Image(Arquivos::__OpenArquivoByTEMP($_FILES['imagem'])));
		
		$lPOV->inserir($pOV, $pO);
		
		while($i = $lI->listar()){
			
			$t = new Traducao;
			
			$t->setIdConteudo($pOV->getId());
			$t->setCampoConteudo(ListaProdutoOpcaoValores::VALOR);
			$t->setTabelaConteudo($lPOV->getTabela());
			$t->conteudo = $pOV->valor;
			$t->traducao = $_POST['ivalor'][$i->getId()];
			$i->addTraducao($t);
			
		}
		
		$lI->resetCondicoes();
		$lI->setParametros(0);
		
		$_POST = '';
		
		$javaScript .= Aviso::criar("Valor salvo com sucesso!");
	
	}else{
		
		$javaScript .= Aviso::criar($erro);
		
	}
	
}


$iCPOV->trocar("linkVoltar", "?p=".$_GET['p']."&a=listarProdutoOpcaoValores&opcao=".$_GET['opcao']);

$iCPOV->trocar("valor", $_POST['valor']);

$iCPOV->condicao('condicao->Cor', $pO->tipo == 2);
$iCPOV->trocar("cor", $_POST['cor']);

$iCPOV->condicao('condicao->Imagem', $pO->tipo == 1);

$sub 	= "repetir->valor.ProdutoOpcaoValores.Idiomas";
$iCPOV->createRepeticao($sub);
while($i = $lI->listar()){
	
	$iCPOV->repetir($sub);
	
	$iCPOV->enterRepeticao($sub)->trocar("valor.Idioma", $i->nome);
	$iCPOV->enterRepeticao($sub)->trocar("id.Idioma", $i->getId());
	if(!empty($_POST)) 
		$iCPOV->enterRepeticao($sub)->trocar("valor.ProdutoOpcaoValores.Idioma", $_POST['ivalor'][$i->getId()]);
	
}

$iCPOV->createJavaScript();
$javaScript .= $iCPOV->javaScript->concluir();

$includePagina = $iCPOV->concluir();

?>