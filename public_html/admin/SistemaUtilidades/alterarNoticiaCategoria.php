<?php

importar("Utilidades.Noticias.Lista.ListaNoticiaCategorias");

$tituloPagina = 'Utilidades > Noticias > Categorias > Alterar';

$iTAGC = new IFAdmin(new Arquivos(Sistema::$adminLayoutCaminhoDiretorio."/SistemaUtilidades/noticiaCategoria.html"));

$lI = new ListaIdiomas;

if(!empty($_POST)){
	
	$erro = '';	
	
    if(empty($_POST['titulo']))
	    $erro = "<b>Titulo</b> não preenchido!<br><br>";

	if(empty($erro)){
		
		$lGC = new ListaNoticiaCategorias;
		$lGC->condicoes('', $_GET['categoria'], ListaNoticiaCategorias::ID);
		$gC = $lGC->listar();
		
		$gC->ordem = $_POST['ordem'];
		
		$gC->getTexto()->titulo 	= $_POST['titulo'];
		$gC->getURL()->setURL($_POST['url'] ? $_POST['url'] : $gC->getId()."-".strtolower(Strings::__RemoveAcentos(str_replace(" ", "-", $_POST['titulo']))));
		
		$gC->getTexto()->texto = $_POST['descricao'];
		if(!empty($_FILES['imagem']['name']))
			$gC->getTexto()->getImagem()->setImage(new Image(Arquivos::__OpenArquivoByTEMP($_FILES['imagem'])));
		
		$lGC->alterar($gC);
		
		$lT = new ListaTextos;
		
		while($i = $lI->listar()){
			
			$t = $i->getTraducaoById(ListaTextos::TITULO, $lGC->getTabela(), $gC->getId());
			
			if($t->getId()){
				
				$t->conteudo = $gC->titulo;
				$t->traducao = $_POST['ititulo'][$i->getId()];
				$i->getTraducoes()->alterar($t);
			
			}else{
				
				$t = new Traducao;
				
				$t->conteudo = $gC->titulo;
				$t->traducao = $_POST['ititulo'][$i->getId()];
				$t->setIdConteudo($gC->getId());
				$t->setCampoConteudo(ListaTextos::TITULO);
				$t->setTabelaConteudo($lGC->getTabela());
				$i->addTraducao($t);
				
			}
			
			$t = $i->getTraducaoById(ListaTextos::TEXTO, $lT->getTabela(), $gC->getTexto()->getId());
			
			if($t->getId()){
				
				$t->conteudo = $gC->getTexto()->texto;
				$t->traducao = $_POST['idescricao'][$i->getId()];
				$i->getTraducoes()->alterar($t);
			
			}else{
				
				$t = new Traducao;
				
				$t->conteudo = $gC->getTexto()->texto;
				$t->traducao = $_POST['idescricao'][$i->getId()];
				$t->setIdConteudo($gC->getTexto()->getId());
				$t->setCampoConteudo(ListaTextos::TEXTO);
				$t->setTabelaConteudo($lT->getTabela());
				$i->addTraducao($t);
				
			}
			
		}
		
		$lI->setParametros(0);
		
	    $javaScript .= Aviso::criar("Categoria salva com sucesso!");
	
	}else{
		
		$javaScript .= Aviso::criar($erro);
		
	}
	
}

$lGC = new ListaNoticiaCategorias;
$gC = $lGC->condicoes('', $_GET['categoria'], ListaNoticiaCategorias::ID)->listar();

$iTAGC->trocar("titulo",	$gC->getTexto()->titulo);
$iTAGC->trocar("ordem",	$gC->ordem);
$iTAGC->trocar("url",		$gC->getURL()->url);
$iTAGC->trocar("descricao",	$gC->getTexto()->texto);
if($gC->getTexto()->getImagem()->getImage()->nome != '')
	$iTAGC->trocar("imagem",	$gC->getTexto()->getImagem()->getImage()->showHTML(200, 200));

$sub 	= "repetir->titulo.NoticiaCategorias.Idiomas";
$sub2 	= "repetir->descricao.NoticiaCategorias.Idiomas";
$iTAGC->createRepeticao($sub);
$iTAGC->createRepeticao($sub2);

$lT = new ListaTextos;

while($i = $lI->listar()){
	
	$iTAGC->repetir($sub);
	
	$iTAGC->enterRepeticao($sub)->trocar("nome.Idioma", $i->nome);
	$iTAGC->enterRepeticao($sub)->trocar("id.Idioma", $i->getId());
	$iTAGC->enterRepeticao($sub)->trocar("titulo.NoticiaCategoria.Idioma", $i->getTraducaoById(ListaNoticiaCategorias::TITULO, $lGC->getTabela(), $gC->getId())->traducao);
	
	$iTAGC->repetir($sub2);
	
	$iTAGC->enterRepeticao($sub2)->trocar("nome.Idioma", $i->nome);
	$iTAGC->enterRepeticao($sub2)->trocar("id.Idioma", $i->getId());
	$iTAGC->enterRepeticao($sub2)->trocar("texto.NoticiaCategoria.Idioma", $i->getTraducaoById(ListaNoticiaCategorias::TEXTO, $lGC->getTabela(), $gC->getId())->traducao);
	
}

$iTAGC->trocar("linkVoltar", "?p=".$_GET['p']."&a=listarNoticiaCategorias");

$iTAGC->createJavaScript();
$javaScript .= $iTAGC->javaScript->concluir();

$includePagina = $iTAGC->concluir();

?>