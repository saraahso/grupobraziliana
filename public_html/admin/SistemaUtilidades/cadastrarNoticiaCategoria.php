<?php

importar("Utilidades.Noticias.Lista.ListaNoticiaCategorias");

$tituloPagina = 'Utilidades > Noticias > Categorias > Cadastrar';

$iTCNC = new IFAdmin(new Arquivos(Sistema::$adminLayoutCaminhoDiretorio."/SistemaUtilidades/noticiaCategoria.html"));

$lI 	= new ListaIdiomas;

if(!empty($_POST)){
	
	$erro = '';

	
    if(empty($_POST['titulo']))
	    $erro = "<b>Titulo</b> não preenchido!<br><br>";

	if(empty($erro)){
	
	    $gC 			= new NoticiaCategoria;
		
		$gC->ordem		= $_POST['ordem'];
		
		$gC->getTexto()->titulo 	= $_POST['titulo'];
		
		$gC->getTexto()->texto 		= $_POST['descricao'];
		if(!empty($_FILES['imagem']['name']))
			$gC->getTexto()->getImagem()->setImage(new Image(Arquivos::__OpenArquivoByTEMP($_FILES['imagem'])));
		
		$lB				= new ListaNoticiaCategorias;
		$lB->inserir($gC);
		$gC->getURL()->setURL($_POST['url'] ? $_POST['url'] : $gC->getId()."-".URL::cleanURL($_POST['titulo']));
		$lB->alterar($gC);
		
		$lT = new ListaTextos;
		
		while($i = $lI->listar()){
			
			$t = new Traducao;
			
			$t->setIdConteudo($gC->getTexto()->getId());
			$t->setCampoConteudo(ListaTextos::TITULO);
			$t->setTabelaConteudo($lT->getTabela());
			$t->conteudo = $gC->getTexto()->titulo;
			$t->traducao = $_POST['ititulo'][$i->getId()];
			$i->addTraducao($t);
			
			$t = new Traducao;
			
			$t->setIdConteudo($gC->getTexto()->getId());
			$t->setCampoConteudo(ListaTextos::TEXTO);
			$t->setTabelaConteudo($lT->getTabela());
			$t->conteudo = $gC->getTexto()->texto;
			$t->traducao = $_POST['idescricao'][$i->getId()];
			$i->addTraducao($t);
			
		}
		
		$lI->resetCondicoes();
		$lI->setParametros(0);
	    
		$_POST = '';
		
	    $javaScript .= Aviso::criar("Categoria salva com sucesso!");
	
	}else{
		
		$javaScript .= Aviso::criar($erro);
		
	}
	
}


$iTCNC->trocar("linkVoltar", "?p=".$_GET['p']."&a=listarNoticiaCategorias");

$iTCNC->trocar("titulo", 	$_POST['titulo']);
$iTCNC->trocar("url", 	$_POST['url']);
$iTCNC->trocar("descricao",	$_POST['descricao']);

$sub 	= "repetir->titulo.NoticiaCategorias.Idiomas";
$sub2 	= "repetir->descricao.NoticiaCategorias.Idiomas";
$iTCNC->createRepeticao($sub);
$iTCNC->createRepeticao($sub2);

while($i = $lI->listar()){
	
	$iTCNC->repetir($sub);
	
	$iTCNC->enterRepeticao($sub)->trocar("nome.Idioma", $i->nome);
	$iTCNC->enterRepeticao($sub)->trocar("id.Idioma", $i->getId());
	if(!empty($_POST)) 
		$iTCNC->enterRepeticao($sub)->trocar("titulo.NoticiaCategorias.Idioma", $_POST['ititulo'][$i->getId()]);
		
	$iTCNC->repetir($sub2);
	
	$iTCNC->enterRepeticao($sub2)->trocar("nome.Idioma", $i->nome);
	$iTCNC->enterRepeticao($sub2)->trocar("id.Idioma", $i->getId());
	if(!empty($_POST)) 
		$iTCNC->enterRepeticao($sub2)->trocar("descricao.NoticiaCategorias.Idioma", $_POST['idescricao'][$i->getId()]);
	
	
}

$iTCNC->createJavaScript();
$javaScript .= $iTCNC->javaScript->concluir();

$includePagina = $iTCNC->concluir();

?>