<?php

importar("Utilidades.UploadsDownloads.Lista.ListaUploadDownloadCategorias");

$uDCituloPagina = 'Utilidades > Upload e Download > Categorias > Alterar';

$iTAUDC = new IFAdmin(new Arquivos(Sistema::$adminLayoutCaminhoDiretorio."/SistemaUtilidades/uploadDownloadCategoria.html"));

$lI = new ListaIdiomas;

if(!empty($_POST)){
	
	$erro = '';	
	
    if(empty($_POST['titulo']))
	    $erro = "<b>Titulo</b> não preenchido!<br><br>";

	if(empty($erro)){
		
		$lUDC = new ListaUploadDownloadCategorias;
		$lUDC->condicoes('', $_GET['categoria'], ListaUploadDownloadCategorias::ID);
		$uDC = $lUDC->listar();
		
		$uDC->titulo 	= $_POST['titulo'];
		$uDC->ordem		= $_POST['ordem'];
		
		$lUDC->alterar($uDC);
		
		while($i = $lI->listar()){
			
			$t = $i->getTraducaoById(ListaUploadDownloadCategorias::TITULO, $lUDC->getTabela(), $uDC->getId());
			
			if($t){
				
				$t->conteudo = $uDC->titulo;
				$t->traducao = $_POST['ititulo'][$i->getId()];
				$i->getTraducoes()->alterar($t);
			
			}else{
				
				$t = new Traducao;
				
				$t->conteudo = $uDC->titulo;
				$t->traducao = $_POST['ititulo'][$i->getId()];
				$t->setIdConteudo($uDC->getId());
				$t->setCampoConteudo(ListaUploadDownloadCategorias::TITULO);
				$t->setTabelaConteudo($lUDC->getTabela());
				$i->addTraducao($t);
				
			}
			
		}
		
		$lI->setParametros(0);
		
	    $javaScript .= Aviso::criar("Categoria salva com sucesso!");
	
	}else{
		
		$javaScript .= Aviso::criar($erro);
		
	}
	
}

$lUDC = new ListaUploadDownloadCategorias;
$uDC = $lUDC->condicoes('', $_GET['categoria'], ListaUploadDownloadCategorias::ID)->listar();

$iTAUDC->trocar("titulo",		$uDC->titulo);
$iTAUDC->trocar("ordem", 		$uDC->ordem);

$sub 	= "repetir->titulo.UploadDownloadCategorias.Idiomas";

$iTAUDC->createRepeticao($sub);

while($i = $lI->listar()){
	
	$iTAUDC->repetir($sub);
	
	$iTAUDC->enterRepeticao($sub)->trocar("nome.Idioma", $i->nome);
	$iTAUDC->enterRepeticao($sub)->trocar("id.Idioma", $i->getId());
	$iTAUDC->enterRepeticao($sub)->trocar("titulo.UploadDownloadCategoria.Idioma", $i->getTraducaoById(ListaUploadDownloadCategorias::TITULO, $lUDC->getTabela(), $uDC->getId())->traducao);
	
}

$iTAUDC->trocar("linkVoltar", "?p=".$_GET['p']."&a=listarUploadDownloadCategorias");

$iTAUDC->createJavaScript();
$javaScript .= $iTAUDC->javaScript->concluir();

$includePagina = $iTAUDC->concluir();

?>