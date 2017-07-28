<?php

$tituloPagina = 'Utilidades > Notícias';

$iTG = new IFAdmin(new Arquivos(Sistema::$adminLayoutCaminhoDiretorio."/SistemaUtilidades/noticias.html"));

$iTG->trocar("link.NoticiaCategorias", "?p=".$_GET['p']."&a=listarNoticiaCategorias");
$iTG->trocar("link.Noticias", "?p=".$_GET['p']."&a=listarNoticias");

$iTG->createJavaScript();
$javaScript .= $iTG->javaScript->concluir();

$includePagina = $iTG->concluir();

?>