<?php

importar("Utilidades.Galerias.Lista.ListaGaleriaCategorias");

$tituloPagina = 'Utilidades > Galerias > Categorias > Alterar';

$iTAGC = new IFAdmin(new Arquivos(Sistema::$adminLayoutCaminhoDiretorio."/SistemaUtilidades/galeriaCategoria.html"));

$lI = new ListaIdiomas;

if(!empty($_POST)){
	
	$erro = '';	
	
    if(empty($_POST['titulo']))
	    $erro = "<b>Titulo</b> não preenchido!<br><br>";

	if(empty($erro)){
		
		$lGC = new ListaGaleriaCategorias;
		$lGC->condicoes('', $_GET['categoria'], ListaGaleriaCategorias::ID);
		$gC = $lGC->listar();
		
		$gC->titulo 	= $_POST['titulo'];
		$gC->getURL()->setURL($_POST['url'] ? $_POST['url'] : URL::cleanURL($_POST['titulo']));
		$gC->protegido	= $_POST['protegido'] == ListaGaleriaCategorias::VALOR_PROTEGIDO_TRUE ? true : false;
		
		$gC->setLargura($_POST['largura']);
		$gC->setAltura($_POST['altura']);
		
		$gC->setLarguraMedia($_POST['larguram']);
		$gC->setAlturaMedia($_POST['alturam']);
		
		$gC->setLarguraPequena($_POST['largurap']);
		$gC->setAlturaPequena($_POST['alturap']);
		
		$gC->getTexto()->subTitulo    = $_POST['subTitulo'];
		$gC->getTexto()->textoPequeno = $_POST['textoPequeno'];
		$gC->getTexto()->texto = $_POST['descricao'];
		
		if(!empty($_FILES['imagem']['name']))
			$gC->getTexto()->getImagem()->setImage(new Image(Arquivos::__OpenArquivoByTEMP($_FILES['imagem'])));
		
		$lGC->alterar($gC);
		
		$lT = new ListaTextos;
		
		while($i = $lI->listar()){
			
			$t = $i->getTraducaoById(ListaGaleriaCategorias::TITULO, $lGC->getTabela(), $gC->getId());
			
			if($t->getId()){
				
				$t->conteudo = $gC->titulo;
				$t->traducao = $_POST['ititulo'][$i->getId()];
				$i->getTraducoes()->alterar($t);
			
			}else{
				
				$t = new Traducao;
				
				$t->conteudo = $gC->titulo;
				$t->traducao = $_POST['ititulo'][$i->getId()];
				$t->setIdConteudo($gC->getId());
				$t->setCampoConteudo(ListaGaleriaCategorias::TITULO);
				$t->setTabelaConteudo($lGC->getTabela());
				$i->addTraducao($t);
				
			}
			
			$t = $i->getTraducaoById(ListaTextos::SUBTITULO, $lT->getTabela(), $gC->getTexto()->getId());
			
			if($t->getId()){
				
				$t->conteudo = $gC->getTexto()->subTitulo;
				$t->traducao = $_POST['iSubTitulo'][$i->getId()];
				$i->getTraducoes()->alterar($t);
			
			}else{
				
				$t = new Traducao;
				
				$t->conteudo = $gC->getTexto()->texto;
				$t->traducao = $_POST['isubTitulo'][$i->getId()];
				$t->setIdConteudo($gC->getTexto()->getId());
				$t->setCampoConteudo(ListaTextos::SUBTITULO);
				$t->setTabelaConteudo($lT->getTabela());
				$i->addTraducao($t);
				
			}
			
			$t = $i->getTraducaoById(ListaTextos::TEXTOPEQUENO, $lT->getTabela(), $gC->getTexto()->getId());
			
			if($t->getId()){
				
				$t->conteudo = $gC->getTexto()->texto;
				$t->traducao = $_POST['itextoPequeno'][$i->getId()];
				$i->getTraducoes()->alterar($t);
			
			}else{
				
				$t = new Traducao;
				
				$t->conteudo = $gC->getTexto()->texto;
				$t->traducao = $_POST['itextoPequeno'][$i->getId()];
				$t->setIdConteudo($gC->getTexto()->getId());
				$t->setCampoConteudo(ListaTextos::TEXTOPEQUENO);
				$t->setTabelaConteudo($lT->getTabela());
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

$lGC = new ListaGaleriaCategorias;
$gC = $lGC->condicoes('', $_GET['categoria'], ListaGaleriaCategorias::ID)->listar();

$iTAGC->trocar("titulo",		$gC->titulo);
$iTAGC->trocar("url",			$gC->getURL()->url);
$iTAGC->trocar("subTitulo",		$gC->getTexto()->subTitulo);
$iTAGC->trocar("textoPequeno",	$gC->getTexto()->textoPequeno);
$iTAGC->trocar("descricao",		$gC->getTexto()->texto);

if($gC->getTexto()->getImagem()->getImage()->nome != '')
	$iTAGC->trocar("imagem",	$gC->getTexto()->getImagem()->getImage()->showHTML(200, 200));
	
$iTAGC->trocar("protegido",		$gC->protegido ? ListaGaleriaCategorias::VALOR_PROTEGIDO_TRUE : ListaGaleriaCategorias::VALOR_PROTEGIDO_FALSE);

$iTAGC->trocar("largura", 		$gC->getLargura()->formatar());
$iTAGC->trocar("altura", 		$gC->getAltura()->formatar());

$iTAGC->trocar("larguram", 		$gC->getLarguraMedia()->formatar());
$iTAGC->trocar("alturam", 		$gC->getAlturaMedia()->formatar());

$iTAGC->trocar("largurap", 		$gC->getLarguraPequena()->formatar());
$iTAGC->trocar("alturap", 		$gC->getAlturaPequena()->formatar());

$sub 	= "repetir->titulo.GaleriaCategorias.Idiomas";
$sub2 	= "repetir->descricao.GaleriaCategorias.Idiomas";
$sub3 	= "repetir->subTitulo.GaleriaCategorias.Idiomas";
$sub4 	= "repetir->textoPequeno.GaleriaCategorias.Idiomas";
$iTAGC->createRepeticao($sub);
$iTAGC->createRepeticao($sub2);
$iTAGC->createRepeticao($sub3);
$iTAGC->createRepeticao($sub4);

$lT = new ListaTextos;

while($i = $lI->listar()){
	
	$iTAGC->repetir($sub);
	
	$iTAGC->enterRepeticao($sub)->trocar("nome.Idioma", $i->nome);
	$iTAGC->enterRepeticao($sub)->trocar("id.Idioma", $i->getId());
	$iTAGC->enterRepeticao($sub)->trocar("titulo.GaleriaCategorias.Idioma", $i->getTraducaoById(ListaGaleriaCategorias::TITULO, $lGC->getTabela(), $gC->getId())->traducao);
	
	$iTAGC->repetir($sub2);
	
	$iTAGC->enterRepeticao($sub2)->trocar("nome.Idioma", $i->nome);
	$iTAGC->enterRepeticao($sub2)->trocar("id.Idioma", $i->getId());
	$iTAGC->enterRepeticao($sub2)->trocar("texto.GaleriaCategorias.Idioma", $i->getTraducaoById(ListaGaleriaCategorias::TEXTO, $lGC->getTabela(), $gC->getId())->traducao);
	
	$iTAGC->repetir($sub3);
	
	$iTAGC->enterRepeticao($sub3)->trocar("nome.Idioma", $i->nome);
	$iTAGC->enterRepeticao($sub3)->trocar("id.Idioma", $i->getId());
	$iTAGC->enterRepeticao($sub3)->trocar("subTitulo.GaleriaCategorias.Idioma", $i->getTraducaoById(ListaGaleriaCategorias::SUBTITULO, $lGC->getTabela(), $gC->getId())->traducao);
	
	$iTAGC->repetir($sub4);
	
	$iTAGC->enterRepeticao($sub4)->trocar("nome.Idioma", $i->nome);
	$iTAGC->enterRepeticao($sub4)->trocar("id.Idioma", $i->getId());
	$iTAGC->enterRepeticao($sub4)->trocar("textoPequeno.GaleriaCategorias.Idioma", $i->getTraducaoById(ListaGaleriaCategorias::TEXTOPEQUENO, $lGC->getTabela(), $gC->getId())->traducao);
	
}

$iTAGC->trocar("linkVoltar", "?p=".$_GET['p']."&a=listarGaleriaCategorias");

$iTAGC->createJavaScript();
$javaScript .= $iTAGC->javaScript->concluir();

$includePagina = $iTAGC->concluir();

?>