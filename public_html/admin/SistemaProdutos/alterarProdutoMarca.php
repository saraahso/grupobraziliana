<?php

importar("LojaVirtual.Produtos.Lista.ListaProdutoMarcas");

$tituloPagina = 'Produtos > Marcas > Alterar';

$iAPM = new IFAdmin(new Arquivos(Sistema::$adminLayoutCaminhoDiretorio."/SistemaProdutos/produtoMarca.html"));

$lI = new ListaIdiomas;

if(!empty($_POST)){
	
	$erro = '';	
	
    if(empty($_POST['nome']))
	    $erro = "<b>Nome</b> não preenchido!<br><br>";

	if(empty($erro)){
		
		$lPM = new ListaProdutoMarcas;
		$lPM->condicoes('', $_GET['marca'], ListaProdutoMarcas::ID);
		$pM = $lPM->listar();
		
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
		
		$lPM->alterar($pM);
		
		while($i = $lI->listar()){
			
			$t = $i->getTraducaoById(ListaProdutoMarcas::NOME, $lPM->getTabela(), $pM->getId());
			
			if($t->getId()){
				
				$t->conteudo = $pM->nome;
				$t->traducao = $_POST['inome'][$i->getId()];
				$i->getTraducoes()->alterar($t);
			
			}else{
				
				$t = new Traducao;
				
				$t->conteudo = $pM->nome;
				$t->traducao = $_POST['inome'][$i->getId()];
				$t->setIdConteudo($pM->getId());
				$t->setCampoConteudo(ListaProdutoMarcas::NOME);
				$t->setTabelaConteudo($lPM->getTabela());
				$i->addTraducao($t);
				
			}
			
			$t = $i->getTraducaoById(ListaProdutoMarcas::DESCRICAO, $lPM->getTabela(), $pM->getId());
			
			if($t->getId()){
				
				$t->conteudo = $pM->descricao;
				$t->traducao = $_POST['idescricao'][$i->getId()];
				$i->getTraducoes()->alterar($t);
			
			}else{
				
				$t = new Traducao;
				
				$t->conteudo = $pM->descricao;
				$t->traducao = $_POST['idescricao'][$i->getId()];
				$t->setIdConteudo($pM->getId());
				$t->setCampoConteudo(ListaProdutoMarcas::DESCRICAO);
				$t->setTabelaConteudo($lPM->getTabela());
				$i->addTraducao($t);
				
			}
			
		}
		
		$lI->setParametros(0);
		
	    $javaScript .= Aviso::criar("Marca salva com sucesso!");
	
	}else{
		
		$javaScript .= Aviso::criar($erro);
		
	}
	
}

$lPC = new ListaProdutoMarcas;
$pM = $lPC->condicoes('', $_GET['marca'], ListaProdutoMarcas::ID)->listar();

$iAPM->trocar("nome", 			$pM->nome);
$iAPM->trocar("url", 			$pM->getURL()->getURL());
$iAPM->trocar("descricao", 		$pM->descricao);
$iAPM->trocar("enderecoURL", 	$pM->enderecoURL);
$iAPM->trocar("disponivel", 	$pM->disponivel);

$sub 	= "repetir->nome.ProdutoMarcas.Idiomas";
$sub2 	= "repetir->descricao.ProdutoMarcas.Idiomas";
$iAPM->createRepeticao($sub);
$iAPM->createRepeticao($sub2);
while($i = $lI->listar()){
	
	$iAPM->repetir($sub);
	$iAPM->repetir($sub2);
	
	$iAPM->enterRepeticao($sub)->trocar("nome.Idioma", $i->nome);
	$iAPM->enterRepeticao($sub)->trocar("id.Idioma", $i->getId());
	$iAPM->enterRepeticao($sub)->trocar("nome.ProdutoMarca.Idioma", $i->getTraducaoById(ListaProdutoMarcas::NOME, $lPC->getTabela(), $pM->getId())->traducao);
	
	$iAPM->enterRepeticao($sub2)->trocar("nome.Idioma", $i->nome);
	$iAPM->enterRepeticao($sub2)->trocar("id.Idioma", $i->getId());
	$iAPM->enterRepeticao($sub2)->trocar("descricao.ProdutoMarca.Idioma", $i->getTraducaoById(ListaProdutoMarcas::DESCRICAO, $lPC->getTabela(), $pM->getId())->traducao);
	
}

$iAPM->trocar("linkVoltar", "?p=".$_GET['p']."&a=listarProdutoMarcas");
												   
if($pM->getImagem()->nome != '')
	$iAPM->trocar("imagem", $pM->getImagem()->showHTML(200, 200));

$iAPM->createJavaScript();
$javaScript .= $iAPM->javaScript->concluir();

$includePagina = $iAPM->concluir();