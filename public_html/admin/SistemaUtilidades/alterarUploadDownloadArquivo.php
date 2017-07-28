<?php

importar("Utilidades.UploadsDownloads.Lista.ListaUploadDownloadArquivos");

$tituloPagina = 'Utilidades > Upload e Download > Arquivos > Alterar';

$iTAUDA = new IFAdmin(new Arquivos(Sistema::$adminLayoutCaminhoDiretorio."/SistemaUtilidades/uploadDownloadArquivo.html"));

if(!empty($_POST)){
	
	$erro = '';	
	
	if(empty($erro)){
		
		$lUDA = new ListaUploadDownloadArquivos;
		$lUDA->condicoes('', $_GET['arquivo'], ListaUploadDownloadArquivos::ID);
		$uDA = $lUDA->listar();
		
		$uDA->produtos 		= trim($_POST['produtos']).' ';
		$uDA->ordem 		= $_POST['ordem'];		
		
		$lUDA->alterar($uDA);
		
		$con = BDConexao::__Abrir();
		$con->deletar(Sistema::$BDPrefixo."relacionamento_arquivos_categorias", "WHERE arquivo = '".$uDA->getId()."'");
		
		$lUDAC = new ListaUploadDownloadCategorias;
		
		if(count($_POST['categoriasSelecionadas']) > 0){
		
			foreach($_POST['categoriasSelecionadas'] as $valor){
				
				$lUDAC->condicoes('', $valor, ListaUploadDownloadCategorias::ID);
				
				if($lUDAC->getTotal() > 0){
					
					$uDAC = $lUDAC->listar();
					
					$uDA->addCategoria($uDAC);
					
				}
				
			}
		
		}
		
	    $javaScript .= Aviso::criar("Arquivo salvo com sucesso!");
	
	}else{
		
		$javaScript .= Aviso::criar($erro);
		
	}
	
}

$lUDA = new ListaUploadDownloadArquivos;
$uDA = $lUDA->condicoes('', $_GET['arquivo'], ListaUploadDownloadArquivos::ID)->listar();

$iTAUDA->condicao('condicao->alterar.UploadDownloadArquivo', 	false);
$iTAUDA->condicao('condicao->arquivo.UploadDownloadArquivo', 	false);

$iTAUDA->trocar("linkVoltar", "?p=".$_GET['p']."&a=listarUploadDownloadArquivos");

$iTAUDA->trocar("produtos", 		$uDA->produtos);
$iTAUDA->trocar("ordem", 			$uDA->ordem);
$iTAUDA->trocar("url.Arquivo",		$uDA->getArquivo()->url);
$iTAUDA->trocar("nome.Arquivo",		$uDA->getArquivo()->nome.".".$uDA->getArquivo()->extensao);

$iTAUDA->createRepeticao("repetir->UploadDownloadArquivoCategorias.UploadDownloadArquivo");
while($uDAC = $uDA->getCategorias()->listar()){
	
	$iTAUDA->repetir();
	$iTAUDA->enterRepeticao()->trocar('id.UploadDownloadArquivoCategoria.UploadDownloadArquivo', $uDAC->getId());
	$iTAUDA->enterRepeticao()->trocar('titulo.UploadDownloadArquivoCategoria.UploadDownloadArquivo', $uDAC->titulo);
	
}

$iTAUDA->createJavaScript();
$javaScript .= $iTAUDA->javaScript->concluir();

$includePagina = $iTAUDA->concluir();

?>