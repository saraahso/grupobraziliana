<?php

importar("Utilidades.UploadsDownloads.Lista.ListaUploadDownloadArquivos");

$tituloPagina = 'Utilidades > Upload e Download > Arquivos > Cadastrar';

$iTCUDA = new IFAdmin(new Arquivos(Sistema::$adminLayoutCaminhoDiretorio."/SistemaUtilidades/uploadDownloadArquivo.html"));

if(!empty($_POST)){
	
	$erro = '';

	if(empty($erro)){
		
		try{
		
	    $uDA 				= new UploadDownloadArquivo;
		
		$uDA->produtos 		= trim($_POST['produtos']).' ';	
		$uDA->ordem 		= $_POST['ordem'];	
		
		if(!empty($_FILES['arquivo']['name']))
			$uDA->setArquivo(Arquivos::__OpenArquivoByTEMP($_FILES['arquivo']));
		
		$lUDA				= new ListaUploadDownloadArquivos;
		$lUDA->inserir($uDA);
		
		$_POST = '';
		
	    $javaScript .= Aviso::criar("Arquivo salvo com sucesso!");		
		
		}catch(Exception $e){
			
			$javaScript .= Aviso::criar($e->getMessage());
			
		}
	
	}else{
		
		$javaScript .= Aviso::criar($erro);
		
	}
	
}

$iTCUDA->condicao('condicao->alterar.UploadDownloadArquivo', true);
$iTCUDA->condicao('condicao->arquivo.UploadDownloadArquivo', true);

$iTCUDA->trocar("linkVoltar", "?p=".$_GET['p']."&a=listarUploadDownloadArquivos");

$iTCUDA->trocar("produtos",		$_POST['produtos']);
$iTCUDA->trocar("ordem", 		$_POST['ordem']);

$iTCUDA->createRepeticao("repetir->UploadDownloadArquivoCategorias.UploadDownloadArquivo");

$iTCUDA->createJavaScript();
$javaScript .= $iTCUDA->javaScript->concluir();

$includePagina = $iTCUDA->concluir();

?>