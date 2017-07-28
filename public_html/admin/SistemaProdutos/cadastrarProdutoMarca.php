<?php

importar("LojaVirtual.Produtos.Lista.ListaProdutoMarcas");

$tituloPagina = 'Produtos > Marcas > Cadastrar';

$iCPM = new IFAdmin(new Arquivos(Sistema::$adminLayoutCaminhoDiretorio."/SistemaProdutos/produtoMarca.html"));

$lI 	= new ListaIdiomas;

if(!empty($_POST)){
	
	$erro = '';
	
	$lCPM = new ListaProdutoMarcas;
	
	
    if(empty($_POST['nome']))
	    $erro = "<b>Nome</b> não preenchido!<br><br>";

	if(empty($erro)){
	
	    $pM 				= new ProdutoMarca;
		
		$pM->nome 			= $_POST['nome'];
		
		if(!empty($_POST['url']))
			$pM->getURL()->setURL($_POST['url']);
		else
			$pM->getURL()->setURL("marca-".URL::cleanURL($_POST['nome']));
		
		$pM->descricao		= $_POST['descricao'];
		$pM->enderecoURL	= $_POST['enderecoURL'];
		$pM->disponivel		= $_POST['disponivel'] == ListaProdutoMarcas::VALOR_DISPONIVEL_TRUE ? true : false;
		
		if(!empty($_FILES['imagem']['name']))
			$pM->setImagem(new Image(Arquivos::__OpenArquivoByTEMP($_FILES['imagem'])));
		
		$lCPM->inserir($pM);
		
		while($i = $lI->listar()){
			
			$t = new Traducao;
			
			$t->setIdConteudo($pM->getId());
			$t->setCampoConteudo(ListaProdutoMarcas::NOME);
			$t->setTabelaConteudo($lCPM->getTabela());
			$t->conteudo = $pM->nome;
			$t->traducao = $_POST['inome'][$i->getId()];
			$i->addTraducao($t);
			
			$t->setCampoConteudo(ListaProdutoMarcas::DESCRICAO);
			$t->conteudo = $pM->descricao;
			$t->traducao = $_POST['idescricao'][$i->getId()];
			$i->addTraducao($t);
			
		}
		
		$lI->resetCondicoes();
		$lI->setParametros(0);
	    
		$_POST = '';
		
	    $javaScript .= Aviso::criar("Marca salva com sucesso!");
	
	}else{
		
		$javaScript .= Aviso::criar($erro);
		
	}
	
}


$iCPM->trocar("linkVoltar", "?p=".$_GET['p']."&a=listarProdutoMarcas");

$iCPM->trocar("nome", $_POST['nome']);
$iCPM->trocar("url", $_POST['url']);
$iCPM->trocar("descricao", $_POST['descricao']);
$iCPM->trocar("enderecoURL", $_POST['enderecoURL']);
$iCPM->trocar("disponivel", $_POST['disponivel']);

$sub 	= "repetir->nome.ProdutoMarcas.Idiomas";
$sub2 	= "repetir->descricao.ProdutoMarcas.Idiomas";
$iCPM->createRepeticao($sub);
$iCPM->createRepeticao($sub2);
while($i = $lI->listar()){
	
	$iCPM->repetir($sub);
	$iCPM->repetir($sub2);
	
	$iCPM->enterRepeticao($sub)->trocar("nome.Idioma", $i->nome);
	$iCPM->enterRepeticao($sub)->trocar("id.Idioma", $i->getId());
	if(!empty($_POST)) 
		$iCPM->enterRepeticao($sub)->trocar("nome.ProdutoMarca.Idioma", $_POST['inome'][$i->getId()]);
	
	$iCPM->enterRepeticao($sub2)->trocar("nome.Idioma", $i->nome);
	$iCPM->enterRepeticao($sub2)->trocar("id.Idioma", $i->getId());
	if(!empty($_POST)) 
		$iCPM->enterRepeticao($sub2)->trocar("descricao.ProdutoMarca.Idioma", $_POST['idescricao'][$i->getId()]);
	
}

$iCPM->createJavaScript();
$javaScript .= $iCPM->javaScript->concluir();

$includePagina = $iCPM->concluir();

?>