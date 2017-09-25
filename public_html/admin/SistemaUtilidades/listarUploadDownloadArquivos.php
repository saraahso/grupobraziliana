<?php

importar("Utilidades.UploadsDownloads.Lista.ListaUploadDownloadArquivos");

$tituloPagina = 'Utilidades > Upload e Download > Arquivos';

$lTUDA = new IFAdmin(new Arquivos(Sistema::$adminLayoutCaminhoDiretorio."/SistemaUtilidades/listarUploadDownloadArquivos.html"));

$lTUDA->trocar("linkDeletar.UploadDownloadArquivo", "?p=".$_GET['p']."&a=".$_GET['a']."&");
$lTUDA->trocar("linkBuscar.UploadDownloadArquivo", "?p=".$_GET['p']."&");

if(!empty($_GET['deletar'])){
	
	$lUDA = new ListaUploadDownloadArquivos;
	$lUDA->condicoes('', $_GET['deletar'], ListaUploadDownloadArquivos::ID);
	
	if($lUDA->getTotal() > 0){
		
		try{
			$lUDA->deletar($lUDA->listar());
			$javaScript .= Aviso::criar("Arquivo removido com sucesso!");
		}catch(Exception $e){
			
			$javaScript .= Aviso::criar($e->getMessage());	
			
		}
	
	
	}
	
}

$lUDA = new ListaUploadDownloadArquivos;
$lTUDA->createRepeticao("repetir->UploadDownloadArquivos");

if(!empty($_GET['busca']))
     $lUDA->condicoes('', "%".$_GET['busca']."%", 'empresa', 'LIKE');

$lTUDA->condicao("condicaoBusca", !empty($_SESSION['nivel']));

$lTUDA->trocar("linkCadastrar.UploadDownloadArquivo", "?p=".$_GET['p']."&a=cadastrarUploadDownloadArquivo");

$lUDA->condicoes($a);

$m = new MD5;

while($uDA = $lUDA->listar("ASC", ListaUploadDownloadArquivos::ARQUIVO)){
	  
	   $lTUDA->repetir();
	   
	   $lTUDA->enterRepeticao()->condicao("condicaoRemover", !empty($_SESSION['nivel']));
	   
	   $bgColor = $lUDA->getParametros()%2 == 0 ? '#FFFFFF' : '#EAEAEA';
	   $lTUDA->enterRepeticao()->trocar("bgColorEmpresa", $bgColor);
	   
	   $lTUDA->enterRepeticao()->trocar("id.UploadDownloadArquivo", $uDA->getId());
	   $lTUDA->enterRepeticao()->trocar("nome.Arquivo.UploadDownloadArquivo", $uDA->getArquivo()->nome.".".$uDA->getArquivo()->extensao);
	   $lTUDA->enterRepeticao()->trocar("url.Arquivo.UploadDownloadArquivo", Sistema::$caminhoURL.Sistema::$caminhoDataUploadsDownloads.$uDA->getArquivo()->getNome());
	   $lTUDA->enterRepeticao()->trocar("linkVisualizar.UploadDownloadArquivo", "?p=".$_GET['p']."&a=listarUploadDownloadArquivos&arquivo=".$uDA->getId());
	   $lTUDA->enterRepeticao()->trocar("linkAlterar.UploadDownloadArquivo", "?p=".$_GET['p']."&a=alterarUploadDownloadArquivo&arquivo=".$uDA->getId());
	   
	   $lTUDA->enterRepeticao()->condicao("condicaoVisualizar", $uDA->tipo == 1);
	 
}

$lTUDA->trocar("linkVoltar.UploadDownloadArquivo", "?p=".$_GET['p']."&a=uploadsdownloads");

$botoes = $lTUDA->cutParte('botoes');

$includePagina = $lTUDA->concluir();

?>