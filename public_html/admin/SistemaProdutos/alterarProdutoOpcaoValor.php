<?php

importar("LojaVirtual.Produtos.Opcoes.Lista.ListaProdutoOpcaoValores");

$tituloPagina = 'Produtos > Opções > Valores > Alterar';

$iAPOV = new IFAdmin(new Arquivos(Sistema::$adminLayoutCaminhoDiretorio."/SistemaProdutos/produtoOpcaoValor.html"));

$lI = new ListaIdiomas;

$lPO	= new ListaProdutoOpcoes;
$lPO->condicoes('', $_GET['opcao'], ListaProdutoOpcaoValores::ID);
if($lPO->getTotal() > 0)
	$pO = $lPO->listar();
else{
	header("Location: ?p=SistemaProdutos&a=listarProdutoOpcaoValores");
	exit;
}

if(!empty($_POST)){
	
	$erro = '';	
	
    if(empty($_POST['valor']))
	    $erro = "<b>Valor</b> não preenchido!<br><br>";

	if(empty($erro)){
		
		$lPOV = new ListaProdutoOpcaoValores;
		$lPOV->condicoes('', $_GET['valor'], ListaProdutoOpcaoValores::ID);
		$pOV = $lPOV->listar();
		
		$pOV->valor			= addslashes(str_replace("\"", "'", $_POST['valor']));
		$pOV->cor 			= $_POST['cor'];
		
		if(!empty($_FILES['imagem']['name']))
			$pOV->setImagem(new Image(Arquivos::__OpenArquivoByTEMP($_FILES['imagem'])));
		
		$lPOV->alterar($pOV);
		
		while($i = $lI->listar()){
			
			$t = $i->getTraducaoById(ListaProdutoOpcaoValores::VALOR, $lPOV->getTabela(), $pOV->getId());
			
			if($t->getId()){
				
				$t->conteudo = $pOV->valor;
				$t->traducao = $_POST['ivalor'][$i->getId()];
				$i->getTraducoes()->alterar($t);
			
			}else{
				
				$t = new Traducao;
				
				$t->conteudo = $pOV->valor;
				$t->traducao = $_POST['inome'][$i->getId()];
				$t->setIdConteudo($pOV->getId());
				$t->setCampoConteudo(ListaProdutoOpcaoValores::VALOR);
				$t->setTabelaConteudo($lPOV->getTabela());
				$i->addTraducao($t);
				
			}
			
		}
		
		$lI->setParametros(0);
		
	    $javaScript .= Aviso::criar("Valor salvo com sucesso!");
	
	}else{
		
		$javaScript .= Aviso::criar($erro);
		
	}
	
}

$lPOV = new ListaProdutoOpcaoValores;
$pOV = $lPOV->condicoes('', $_GET['valor'], ListaProdutoOpcaoValores::ID)->listar();

$iAPOV->trocar("valor", $pOV->valor);

$iAPOV->condicao('condicao->Cor', $pO->tipo == 2);
$iAPOV->trocar("cor", $pOV->cor);

$iAPOV->condicao('condicao->Imagem', $pO->tipo == 1);
if($pOV->getImagem()->nome != '')
	$iAPOV->trocar("imagem", $pOV->getImagem()->showHTML(200, 200));

$sub 	= "repetir->valor.ProdutoOpcaoValores.Idiomas";
$iAPOV->createRepeticao($sub);
while($i = $lI->listar()){
	
	$iAPOV->repetir($sub);
	
	$iAPOV->enterRepeticao($sub)->trocar("valor.Idioma", $i->valor);
	$iAPOV->enterRepeticao($sub)->trocar("id.Idioma", $i->getId());
	$iAPOV->enterRepeticao($sub)->trocar("valor.ProdutoOpcaoValor.Idioma", $i->getTraducaoById(ListaProdutoOpcaoValores::VALOR, $lPOV->getTabela(), $pOV->getId())->traducao);
	
}

$iAPOV->trocar("linkVoltar", "?p=".$_GET['p']."&a=listarProdutoOpcaoValores&opcao=".$_GET['opcao']);

$iAPOV->createJavaScript();
$javaScript .= $iAPOV->javaScript->concluir();

$includePagina = $iAPOV->concluir();

?>