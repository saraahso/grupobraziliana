<?php

importar("Geral.Lista.ListaTextos");

$tituloPagina = 'Utilidades > Textos > Alterar';

$iAT = new IFAdmin(new Arquivos(Sistema::$adminLayoutCaminhoDiretorio."/SistemaUtilidades/texto.html"));

$lI = new ListaIdiomas;

if(!empty($_POST)){
	
	$erro = '';	
	
    if(empty($_POST['titulo']))
	    $erro = "<b>Titulo</b> não preenchido!<br><br>";
	if(empty($_POST['url']))
	    $erro = "<b>URL</b> não preenchido!<br><br>";

	if(empty($erro)){
		
		$lT = new ListaTextos;
		$lT->condicoes('', $_GET['texto'], ListaTextos::ID);
		$tx = $lT->listar();
		
		$tx->titulo 		= $_POST['titulo'];
		$tx->subTitulo		= $_POST['subTitulo'];
		$tx->ordem			= $_POST['ordem'];
		$tx->getURL()->setURL($_POST['url']);
		$tx->textoPequeno	= $_POST['textoPequeno'];
		$tx->texto			= $_POST['texto'];
		
		if(!empty($_FILES['imagem']['name']))
			$tx->getImagem()->setImage(new Image(Arquivos::__OpenArquivoByTEMP($_FILES['imagem'])));
		
		$lT->alterar($tx);
		
		while($i = $lI->listar()){
			
			$t = $i->getTraducaoById(ListaTextos::TITULO, $lT->getTabela(), $tx->getId());
			
			if($t->getId()){
				
				$t->conteudo = $tx->titulo;
				$t->traducao = $_POST['ititulo'][$i->getId()];
				$i->getTraducoes()->alterar($t);
			
			}else{
				
				$t = new Traducao;
				
				$t->conteudo = $tx->titulo;
				$t->traducao = $_POST['ititulo'][$i->getId()];
				$t->setIdConteudo($tx->getId());
				$t->setCampoConteudo(ListaTextos::TITULO);
				$t->setTabelaConteudo($lT->getTabela());
				$i->addTraducao($t);
				
			}			
			
			$t = $i->getTraducaoById(ListaTextos::TEXTO, $lT->getTabela(), $tx->getId());
			
			if($t->getId()){
				
				$t->conteudo = $tx->texto;
				$t->traducao = $_POST['itexto'][$i->getId()];
				$i->getTraducoes()->alterar($t);
			
			}else{
				
				$t = new Traducao;
				
				$t->conteudo = $tx->texto;
				$t->traducao = $_POST['itexto'][$i->getId()];
				$t->setIdConteudo($tx->getId());
				$t->setCampoConteudo(ListaTextos::TEXTO);
				$t->setTabelaConteudo($lT->getTabela());
				$i->addTraducao($t);
				
			}	
			
			$t = $i->getTraducaoById(ListaTextos::TEXTOPEQUENO, $lT->getTabela(), $tx->getId());
			
			if($t->getId()){
				
				$t->conteudo = $tx->textoPequeno;
				$t->traducao = $_POST['itextoPequeno'][$i->getId()];
				$i->getTraducoes()->alterar($t);
			
			}else{
				
				$t = new Traducao;
				
				$t->conteudo = $tx->textoPequeno;
				$t->traducao = $_POST['itextoPequeno'][$i->getId()];
				$t->setIdConteudo($tx->getId());
				$t->setCampoConteudo(ListaTextos::TEXTOPEQUENO);
				$t->setTabelaConteudo($lT->getTabela());
				$i->addTraducao($t);
				
			}	
			
			$t = $i->getTraducaoById(ListaTextos::SUBTITULO, $lT->getTabela(), $tx->getId());
			
			if($t->getId()){
				
				$t->conteudo = $tx->subTitulo;
				$t->traducao = $_POST['isubTitulo'][$i->getId()];
				$i->getTraducoes()->alterar($t);
			
			}else{
				
				$t = new Traducao;
				
				$t->conteudo = $tx->subTitulo;
				$t->traducao = $_POST['subTitulo'][$i->getId()];
				$t->setIdConteudo($tx->getId());
				$t->setCampoConteudo(ListaTextos::SUBTITULO);
				$t->setTabelaConteudo($lT->getTabela());
				$i->addTraducao($t);
				
			}	
			
		}
		
		$lI->setParametros(0);
		
	    $javaScript .= Aviso::criar("Texto salvo com sucesso!");
	
	}else{
		
		$javaScript .= Aviso::criar($erro);
		
	}
	
}

$lT = new ListaTextos;
$t = $lT->condicoes('', $_GET['texto'], ListaTextos::ID)->listar();

$iAT->trocar("titulo",			$t->titulo);
$iAT->trocar("subTitulo",		$t->subTitulo);
$iAT->trocar("url", 			$t->getURL()->getURL());
$iAT->trocar("ordem", 			$t->ordem);
$iAT->trocar("textoPequeno",	$t->textoPequeno);
$iAT->trocar("texto", 			$t->texto);

$sub 	= "repetir->titulo.Textos.Idiomas";
$sub2 	= "repetir->texto.Textos.Idiomas";
$sub3 	= "repetir->subTitulo.Textos.Idiomas";
$sub4 	= "repetir->textoPequeno.Textos.Idiomas";
$iAT->createRepeticao($sub);
$iAT->createRepeticao($sub2);
$iAT->createRepeticao($sub3);
$iAT->createRepeticao($sub4);
while($i = $lI->listar()){
	
	$iAT->repetir($sub);
	$iAT->repetir($sub2);
	$iAT->repetir($sub3);
	$iAT->repetir($sub4);
	
	$iAT->enterRepeticao($sub)->trocar("nome.Idioma", $i->nome);
	$iAT->enterRepeticao($sub)->trocar("id.Idioma", $i->getId());
	$iAT->enterRepeticao($sub)->trocar("titulo.Texto.Idioma", $i->getTraducaoById(ListaTextos::TITULO, $lT->getTabela(), $t->getId())->traducao);
	
	$iAT->enterRepeticao($sub2)->trocar("nome.Idioma", $i->nome);
	$iAT->enterRepeticao($sub2)->trocar("id.Idioma", $i->getId());
	$iAT->enterRepeticao($sub2)->trocar("texto.Texto.Idioma", $i->getTraducaoById(ListaTextos::TEXTO, $lT->getTabela(), $t->getId())->traducao);
	
	$iAT->enterRepeticao($sub3)->trocar("nome.Idioma", $i->nome);
	$iAT->enterRepeticao($sub3)->trocar("id.Idioma", $i->getId());
	$iAT->enterRepeticao($sub3)->trocar("subTitulo.Texto.Idioma", $i->getTraducaoById(ListaTextos::SUBTITULO, $lT->getTabela(), $t->getId())->traducao);
	
	$iAT->enterRepeticao($sub4)->trocar("nome.Idioma", $i->nome);
	$iAT->enterRepeticao($sub4)->trocar("id.Idioma", $i->getId());
	$iAT->enterRepeticao($sub4)->trocar("textoPequeno.Texto.Idioma", $i->getTraducaoById(ListaTextos::TEXTOPEQUENO, $lT->getTabela(), $t->getId())->traducao);
	
}

$iAT->trocar("linkVoltar", "?p=".$_GET['p']."&a=listarTextos");
												   
if($t->getImagem()->getImage()->nome != '')
	$iAT->trocar("imagem", $t->getImagem()->getImage()->showHTML(200, 200));

$iAT->createJavaScript();
$javaScript .= $iAT->javaScript->concluir();

$includePagina = $iAT->concluir();

?>