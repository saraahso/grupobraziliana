<?php

$tituloPagina = 'Utilidades > Upload e Download';

$iTUD = new IFAdmin(new Arquivos(Sistema::$adminLayoutCaminhoDiretorio."/SistemaUtilidades/uploadsdownloads.html"));

$iTUD->trocar("link.UploadDownloadCategorias", "?p=".$_GET['p']."&a=listarUploadDownloadCategorias");
$iTUD->trocar("link.UploadDownloadArquivos", "?p=".$_GET['p']."&a=listarUploadDownloadArquivos");
$iTUD->trocar("linkVoltar", "?p=".$_GET['p']."&a=utilidades");

$iTUD->createJavaScript();
$javaScript .= $iTUD->javaScript->concluir();

$includePagina = $iTUD->concluir();

?>