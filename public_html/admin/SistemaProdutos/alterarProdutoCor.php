<?php

importar("LojaVirtual.Produtos.Lista.ListaProdutoCores");

$tituloPagina = 'Produtos > Cores > Alterar';

$iAPCO = new IFAdmin(new Arquivos(Sistema::$adminLayoutCaminhoDiretorio."/SistemaProdutos/produtoCor.html"));

$lI = new ListaIdiomas;

if(!empty($_POST)){
	
	$erro = '';	
	
    if(empty($_POST['nome']))
	    $erro = "<b>Nome</b> não preenchido!<br><br>";

	if(empty($erro)){
		
		$lPCO = new ListaProdutoCores;
		$lPCO->condicoes('', $_GET['cor'], ListaProdutoCores::ID);
		$pCO = $lPCO->listar();
		
		$pCO->nome 			= $_POST['nome'];
		$pCO->hexadecimal 	= $_POST['hexadecimal'];
		
		if(!empty($_FILES['imagem']['name']))
			$pCO->setImagem(new Image(Arquivos::__OpenArquivoByTEMP($_FILES['imagem'])));
		
		$lPCO->alterar($pCO);
		
		while($i = $lI->listar()){
			
			$t = $i->getTraducaoById(ListaProdutoCores::NOME, $lPCO->getTabela(), $pCO->getId());
			
			if($t->getId()){
				
				$t->conteudo = $pCO->nome;
				$t->traducao = $_POST['inome'][$i->getId()];
				$i->getTraducoes()->alterar($t);
			
			}else{
				
				$t = new Traducao;
				
				$t->conteudo = $pCO->nome;
				$t->traducao = $_POST['inome'][$i->getId()];
				$t->setIdConteudo($pCO->getId());
				$t->setCampoConteudo(ListaProdutoCores::NOME);
				$t->setTabelaConteudo($lPCO->getTabela());
				$i->addTraducao($t);
				
			}
			
		}
		
		$lI->setParametros(0);
		
	    $javaScript .= Aviso::criar("Cor salva com sucesso!");
	
	}else{
		
		$javaScript .= Aviso::criar($erro);
		
	}
	
}

$lPC = new ListaProdutoCores;
$pCO = $lPC->condicoes('', $_GET['cor'], ListaProdutoCores::ID)->listar();

$iAPCO->trocar("nome", 				$pCO->nome);
$iAPCO->trocar("hexadecimal", 		$pCO->hexadecimal);

$sub 	= "repetir->nome.ProdutoCores.Idiomas";
$iAPCO->createRepeticao($sub);
while($i = $lI->listar()){
	
	$iAPCO->repetir($sub);
	
	$iAPCO->enterRepeticao($sub)->trocar("nome.Idioma", $i->nome);
	$iAPCO->enterRepeticao($sub)->trocar("id.Idioma", $i->getId());
	$iAPCO->enterRepeticao($sub)->trocar("nome.ProdutoCor.Idioma", $i->getTraducaoById(ListaProdutoCores::NOME, $lPC->getTabela(), $pCO->getId())->traducao);
	
}

$iAPCO->trocar("linkVoltar", "?p=".$_GET['p']."&a=listarProdutoCores");
												   
if($pCO->getImagem()->nome != '')
	$iAPCO->trocar("imagem", $pCO->getImagem()->showHTML(200, 200));

$iAPCO->createJavaScript();
$javaScript .= $iAPCO->javaScript->concluir();

$includePagina = $iAPCO->concluir();

?>