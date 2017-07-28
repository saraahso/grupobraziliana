<?php

importar("Utilidades.Publicidades.Banners.Lista.ListaBannerCategorias");

$tituloPagina = 'Utilidades > Publicidades > Banners > Cadastrar';

$iTCB = new IFAdmin(new Arquivos(Sistema::$adminLayoutCaminhoDiretorio."/SistemaUtilidades/banner.html"));

$lI 	= new ListaIdiomas;

if(!empty($_POST)){
	
	$erro = '';
		
    if(empty($_POST['titulo']))
	    $erro = "<b>Titulo</b> não preenchido!<br><br>";

	if(empty($erro)){
		
		try{
		
	    $b 				= new Banner;
		
		$b->titulo 		= $_POST['titulo'];		
		$b->enderecoURL	= $_POST['enderecoURL'];
		$b->tipo		= $_POST['tipo'];
		
		$b->setDataInicio(new DataHora($_POST['dataInicio']));
		$b->setDataFim(new DataHora($_POST['dataFim']));		
		
		$b->ativo		= $_POST['ativo'] == ListaBanners::VALOR_ATIVO_TRUE ? true : false;
		
		if(!empty($_FILES['flash']['name'])){
			$arq = Arquivos::__OpenArquivoByTEMP($_FILES['flash']);
			$arq->saveArquivo(Sistema::$caminhoDiretorio.Sistema::$caminhoDataBanners);
			$b->setFlash($_FILES['flash']['name']);
			$b->setLargura($_POST['largura']);
			$b->setAltura($_POST['altura']);
		}
				
		if(!empty($_FILES['imagem']['name']))
			$b->setImagem(new Image(Arquivos::__OpenArquivoByTEMP($_FILES['imagem'])));
		
		$lB				= new ListaBanners;
		$lB->inserir($b);
		
		while($i = $lI->listar()){
			
			$t = new Traducao;
			
			$t->setIdConteudo($b->getId());
			$t->setCampoConteudo(ListaBanners::TITULO);
			$t->setTabelaConteudo($lB->getTabela());
			$t->conteudo = $b->titulo;
			$t->traducao = $_POST['ititulo'][$i->getId()];
			$i->addTraducao($t);
			
		}
		
		$_POST = '';
		
		header("Location: ".Sistema::$adminCaminhoURL."?p=SistemaUtilidades&a=alterarBanner&banner=".$b->getId());
	    //$javaScript .= Aviso::criar("Banner salvo com sucesso!");		
		
		}catch(Exception $e){
			
			$javaScript .= Aviso::criar($e->getMessage());
			
		}
	
	}else{
		
		$javaScript .= Aviso::criar($erro);
		
	}
	
}

$iTCB->condicao('condicao->alterar.Banner', true);

$iTCB->trocar("linkVoltar", "?p=".$_GET['p']."&a=listarBanners");

$iTCB->trocar("titulo", 		$_POST['titulo']);
$iTCB->trocar("enderecoURL",	$_POST['enderecoURL']);
$iTCB->trocar("tipo", 			$_POST['tipo']);
$iTCB->trocar("dataInicio",		$_POST['dataInicio']);
$iTCB->trocar("dataFim", 		$_POST['dataFim']);

$iTCB->trocar("ativo", 			$_POST['ativo']);

$iTCB->trocar("largura", 		$_POST['largura']);
$iTCB->trocar("altura", 		$_POST['altura']);

$iTCB->createRepeticao("repetir->BannerCategorias.Banner");

$sub 	= "repetir->titulo.Banners.Idiomas";

$iTCB->createRepeticao($sub);


while($i = $lI->listar()){
	
	$iTCB->repetir($sub);

	
	$iTCB->enterRepeticao($sub)->trocar("nome.Idioma", $i->nome);
	$iTCB->enterRepeticao($sub)->trocar("id.Idioma", $i->getId());
	if(!empty($_POST)) 
		$iTCB->enterRepeticao($sub)->trocar("titulo.Banner.Idioma", $_POST['ititulo'][$i->getId()]);
	
}

$iTCB->createJavaScript();
$javaScript .= $iTCB->javaScript->concluir();

$includePagina = $iTCB->concluir();

?>