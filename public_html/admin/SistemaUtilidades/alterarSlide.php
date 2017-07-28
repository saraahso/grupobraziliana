<?php

importar("Utilidades.Publicidades.Slides.Lista.ListaSlides");

$tituloPagina = 'Utilidades > Publicidades > Slides > Alterar';

$iTAS = new IFAdmin(new Arquivos(Sistema::$adminLayoutCaminhoDiretorio."/SistemaUtilidades/slide.html"));

$lI = new ListaIdiomas;

if(!empty($_POST)){
	
	$erro = '';	
	
    if(empty($_POST['titulo']))
	    $erro = "<b>Titulo</b> não preenchido!<br><br>";

	if(empty($erro)){
		
		$lS = new ListaSlides;
		$lS->condicoes('', $_GET['slide'], ListaSlides::ID);
		$s = $lS->listar();
		
		$s->titulo 		= $_POST['titulo'];		
		$s->enderecoURL	= $_POST['enderecoURL'];
		$s->tipo		= $_POST['tipo'];
		$s->legenda		= $_POST['legenda'];
		$s->corfundo		= $_POST['corfundo'];
		$s->segundos	= $_POST['segundos'];
		$s->ordem		= $_POST['ordem'];
		
		$s->ativo		= $_POST['ativo'] == ListaSlides::VALOR_ATIVO_TRUE ? true : false;
		
		if(!empty($_FILES['flash']['name'])){
			$arq = Arquivos::__OpenArquivoByTEMP($_FILES['flash']);
			$arq->saveArquivo(Sistema::$caminhoDiretorio.Sistema::$caminhoDataSlides);
			$s->setFlash($_FILES['flash']['name']);
		}
		
		if(!empty($_FILES['imagem']['name'])){
			$s->setImagem(new Image(Arquivos::__OpenArquivoByTEMP($_FILES['imagem'])));
		}
		
		$lS->alterar($s);
		
		while($i = $lI->listar()){
			
			$t = $i->getTraducaoById(ListaSlides::TITULO, $lS->getTabela(), $s->getId());
			
			if($t->getId()){
				$t->conteudo = $s->titulo;
				$t->traducao = $_POST['ititulo'][$i->getId()];
				$i->getTraducoes()->alterar($t);
			
			}else{
				
				$t = new Traducao;
				
				$t->conteudo = $s->titulo;
				$t->traducao = $_POST['ititulo'][$i->getId()];
				$t->setIdConteudo($s->getId());
				$t->setCampoConteudo(ListaSlides::TITULO);
				$t->setTabelaConteudo($lS->getTabela());
				$i->addTraducao($t);
				
			}
			
			$t = $i->getTraducaoById(ListaSlides::LEGENDA, $lS->getTabela(), $s->getId());
			
			if($t->getId()){
				$t->conteudo = $s->legenda;
				$t->traducao = $_POST['ilegenda'][$i->getId()];
				$i->getTraducoes()->alterar($t);
			
			}else{
				
				$t = new Traducao;
				
				$t->conteudo = $s->legenda;
				$t->traducao = $_POST['ilegenda'][$i->getId()];
				$t->setIdConteudo($s->getId());
				$t->setCampoConteudo(ListaSlides::LEGENDA);
				$t->setTabelaConteudo($lS->getTabela());
				$i->addTraducao($t);
				
			}
			
		}
		
		$con = BDConexao::__Abrir();
		$con->deletar(Sistema::$BDPrefixo."relacionamento_slides_categorias", "WHERE slide = '".$s->getId()."'");
		
		$lSC = new ListaSlideCategorias;
		
		if(count($_POST['categoriasSelecionadas']) > 0){
		
			foreach($_POST['categoriasSelecionadas'] as $valor){
				
				$lSC->condicoes('', $valor, ListaSlideCategorias::ID);
				
				if($lSC->getTotal() > 0){
					
					$sC = $lSC->listar();
					
					$s->addCategoria($sC);
					
				}
				
			}
		
		}
		
	    $javaScript .= Aviso::criar("Slide salvo com sucesso!");
	
	}else{
		
		$javaScript .= Aviso::criar($erro);
		
	}
	
}

$lS = new ListaSlides;
$s = $lS->condicoes('', $_GET['slide'], ListaSlides::ID)->listar();

$iTAS->condicao('condicao->alterar.Slide', false);

$iTAS->trocar("linkVoltar", "?p=".$_GET['p']."&a=listarSlides");

$iTAS->trocar("titulo", 			$s->titulo);
$iTAS->trocar("enderecoURL",		$s->enderecoURL);
$iTAS->trocar("segundos", 			$s->segundos);
$iTAS->trocar("tipo", 				$s->tipo);
$iTAS->trocar("legenda", 			$s->legenda);
$iTAS->trocar("corfundo", 			$s->corfundo);
$iTAS->trocar("ordem", 				$s->ordem);

$iTAS->trocar("ativo", 				$s->ativo 	? 1 : 0);

if($s->getImagem()->nome != '')
	$iTAS->trocar("imagem", 		$s->getImagem()->showHTML(200, 200));
	
if($s->getFlash() != ''){
	$iTAS->trocar("url.Flash", 		Sistema::$caminhoURL.Sistema::$caminhoDataSlides.$s->getFlash());
}

$iTAS->createRepeticao("repetir->SlideCategorias.Slide");
while($sC = $s->getCategorias()->listar()){
	
	$iTAS->repetir();
	$iTAS->enterRepeticao()->trocar('id.SlideCategoria.Slide', $sC->getId());
	$iTAS->enterRepeticao()->trocar('titulo.SlideCategoria.Slide', $sC->titulo);
	
}

$lI 	= new ListaIdiomas;
$sub 	= "repetir->titulo.Slides.Idiomas";
$sub2 	= "repetir->legenda.Slides.Idiomas";
$iTAS->createRepeticao($sub);
$iTAS->createRepeticao($sub2);
while($i = $lI->listar()){
	
	$iTAS->repetir($sub);
	$iTAS->repetir($sub2);
	
	$iTAS->enterRepeticao($sub)->trocar("nome.Idioma", $i->nome);
	$iTAS->enterRepeticao($sub)->trocar("id.Idioma", $i->getId());
	$iTAS->enterRepeticao($sub)->trocar("titulo.Slide.Idioma", $i->getTraducaoById(ListaSlides::TITULO, $lS->getTabela(), $s->getId())->traducao);
	
	$iTAS->enterRepeticao($sub2)->trocar("nome.Idioma", $i->nome);
	$iTAS->enterRepeticao($sub2)->trocar("id.Idioma", $i->getId());
	$iTAS->enterRepeticao($sub2)->trocar("legenda.Slide.Idioma", $i->getTraducaoById(ListaSlides::LEGENDA, $lS->getTabela(), $s->getId())->traducao);
	
}

$iTAS->createJavaScript();
$javaScript .= $iTAS->javaScript->concluir();

$includePagina = $iTAS->concluir();

?>