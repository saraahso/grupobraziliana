<?php

$tituloPagina = 'Utilidades > Publicidades > Slides';

$iTS = new IFAdmin(new Arquivos(Sistema::$adminLayoutCaminhoDiretorio."/SistemaUtilidades/slides.html"));

$iTS->trocar("link.SlideCategorias", "?p=".$_GET['p']."&a=listarSlideCategorias");
$iTS->trocar("link.Slides", "?p=".$_GET['p']."&a=listarSlides");

$iTS->trocar("linkVoltar", "?p=".$_GET['p']."&a=publicidades");

$iTS->createJavaScript();
$javaScript .= $iTS->javaScript->concluir();

$includePagina = $iTS->concluir();

?>