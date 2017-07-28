<?php

$tituloPagina = 'Utilidades > Publicidades > Mailings';

$iTS = new IFAdmin(new Arquivos(Sistema::$adminLayoutCaminhoDiretorio."/SistemaUtilidades/mailings.html"));

$iTS->trocar("link.PacoteMailings", "?p=".$_GET['p']."&a=listarPacoteMailings");
$iTS->trocar("link.Mailings", "?p=".$_GET['p']."&a=listarMailings");

$iTS->createJavaScript();
$javaScript .= $iTS->javaScript->concluir();

$includePagina = $iTS->concluir();

?>