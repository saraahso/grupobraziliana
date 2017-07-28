<?php

importar("Utilidades.UploadsDownloads.Lista.ListaUploadDownloadCategorias");

$tituloPagina = 'Utilidades > Upload e Download > Categorias > Cadastrar';

$iTCUDC = new IFAdmin(new Arquivos(Sistema::$adminLayoutCaminhoDiretorio."/SistemaUtilidades/uploadDownloadCategoria.html"));

$lI 	= new ListaIdiomas;

if(!empty($_POST)){
	
	$erro = '';

	
    if(empty($_POST['titulo']))
	    $erro = "<b>Titulo</b> não preenchido!<br><br>";

	if(empty($erro)){
	
	    $uDC 			= new UploadDownloadCategoria;
		
		$uDC->titulo 	= $_POST['titulo'];		
		$uDC->ordem		= $_POST['ordem'];
		
		$lUDC				= new ListaUploadDownloadCategorias;
		$lUDC->inserir($uDC);
		
		while($i = $lI->listar()){
			
			$t = new Traducao;
			
			$t->setIdConteudo($uDC->getId());
			$t->setCampoConteudo(ListaUploadDownloadCategorias::TITULO);
			$t->setTabelaConteudo($lUDC->getTabela());
			$t->conteudo = $uDC->titulo;
			$t->traducao = $_POST['ititulo'][$i->getId()];
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


$iTCUDC->trocar("linkVoltar", "?p=".$_GET['p']."&a=listarUploadDownloadCategorias");

$iTCUDC->trocar("titulo", $_POST['titulo']);
$iTCUDC->trocar("ordem", $_POST['ordem']);


$sub 	= "repetir->titulo.UploadDownloadCategorias.Idiomas";

$iTCUDC->createRepeticao($sub);

while($i = $lI->listar()){
	
	$iTCUDC->repetir($sub);
;
	
	$iTCUDC->enterRepeticao($sub)->trocar("nome.Idioma", $i->nome);
	$iTCUDC->enterRepeticao($sub)->trocar("id.Idioma", $i->getId());
	if(!empty($_POST)) 
		$iTCUDC->enterRepeticao($sub)->trocar("titulo.UploadDownloadCategoria.Idioma", $_POST['ititulo'][$i->getId()]);
	
	
}

$iTCUDC->createJavaScript();
$javaScript .= $iTCUDC->javaScript->concluir();

$includePagina = $iTCUDC->concluir();

?>