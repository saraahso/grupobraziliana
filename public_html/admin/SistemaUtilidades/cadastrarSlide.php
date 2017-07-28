<?php

importar("Utilidades.Publicidades.Slides.Lista.ListaSlideCategorias");

$tituloPagina = 'Utilidades > Publicidades > Slides > Cadastrar';

$iTCS = new IFAdmin(new Arquivos(Sistema::$adminLayoutCaminhoDiretorio."/SistemaUtilidades/slide.html"));

$lI 	= new ListaIdiomas;

if(!empty($_POST)){
	
	$erro = '';
		
    if(empty($_POST['titulo']))
	    $erro = "<b>Titulo</b> não preenchido!<br><br>";

	if(empty($erro)){
		
		try{
		
	    $s 				= new Slide;
		
		$s->titulo 		= $_POST['titulo'];		
		$s->enderecoURL	= $_POST['enderecoURL'];
		$s->tipo		= $_POST['tipo'];
		$s->legenda		= $_POST['legenda'];
		$s->segundos	= $_POST['segundos'];
		$s->corfundo	= $_POST['corfundo'];
		$s->ordem		= $_POST['ordem'];
		
		$s->ativo		= $_POST['ativo'] == ListaSlides::VALOR_ATIVO_TRUE ? true : false;
		
		if(!empty($_FILES['flash']['name'])){
			$arq = Arquivos::__OpenArquivoByTEMP($_FILES['flash']);
			$arq->saveArquivo(Sistema::$caminhoDiretorio.Sistema::$caminhoDataSlides);
			$s->setFlash($_FILES['flash']['name']);
		}
				
		if(!empty($_FILES['imagem']['name']))
			$s->setImagem(new Image(Arquivos::__OpenArquivoByTEMP($_FILES['imagem'])));
		
		$lS				= new ListaSlides;
		$lS->inserir($s);
		
		while($i = $lI->listar()){
			
			$t = new Traducao;
			
			$t->setIdConteudo($s->getId());
			$t->setCampoConteudo(ListaSlides::TITULO);
			$t->setTabelaConteudo($lS->getTabela());
			$t->conteudo = $s->titulo;
			$t->traducao = $_POST['ititulo'][$i->getId()];
			$i->addTraducao($t);
			
			$t->setCampoConteudo(ListaSlides::LEGENDA);
			$t->conteudo = $s->legenda;
			$t->traducao = $_POST['ilegenda'][$i->getId()];
			$i->addTraducao($t);
			
		}
		
		$_POST = '';
		
		header("Location: ?p=SistemaUtilidades&a=alterarSlide&slide=".$s->getId());
		
	    $javaScript .= Aviso::criar("Slide salvo com sucesso!");		
		
		}catch(Exception $e){
			
			$javaScript .= Aviso::criar($e->getMessage());
			
		}
	
	}else{
		
		$javaScript .= Aviso::criar($erro);
		
	}
	
}

$iTCS->condicao('condicao->alterar.Slide', true);

$iTCS->trocar("linkVoltar", "?p=".$_GET['p']."&a=listarSlides");

$iTCS->trocar("titulo", 		$_POST['titulo']);
$iTCS->trocar("enderecoURL",	$_POST['enderecoURL']);
$iTCS->trocar("tipo", 			$_POST['tipo']);
$iTCS->trocar("legenda",		$_POST['legenda']);
$iTCS->trocar("corfundo",		$_POST['corfundo']);
$iTCS->trocar("ordem",			$_POST['ordem']);

$iTCS->trocar("ativo", 			$_POST['ativo']);

$iTCS->trocar("segundos", 		$_POST['segundos']);

$iTCS->createRepeticao("repetir->SlideCategorias.Slide");

$sub 	= "repetir->titulo.Slides.Idiomas";
$sub2 	= "repetir->legenda.Slides.Idiomas";

$iTCS->createRepeticao($sub);
$iTCS->createRepeticao($sub2);

while($i = $lI->listar()){
	
	$iTCS->repetir($sub);
	$iTCS->repetir($sub2);
	
	$iTCS->enterRepeticao($sub)->trocar("nome.Idioma", $i->nome);
	$iTCS->enterRepeticao($sub)->trocar("id.Idioma", $i->getId());
	if(!empty($_POST)) 
		$iTCS->enterRepeticao($sub)->trocar("titulo.Slide.Idioma", $_POST['ititulo'][$i->getId()]);
		
	$iTCS->enterRepeticao($sub2)->trocar("nome.Idioma", $i->nome);
	$iTCS->enterRepeticao($sub2)->trocar("id.Idioma", $i->getId());
	if(!empty($_POST)) 
		$iTCS->enterRepeticao($sub2)->trocar("legenda.Slide.Idioma", $_POST['ilegenda'][$i->getId()]);
	
}

$iTCS->createJavaScript();
$javaScript .= $iTCS->javaScript->concluir();

$includePagina = $iTCS->concluir();

?>