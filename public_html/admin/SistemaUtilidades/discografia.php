<?php

$tituloPagina = 'Utilidades > Discografia';

$iTD = new IFAdmin(new Arquivos(Sistema::$adminLayoutCaminhoDiretorio."/SistemaUtilidades/discografia.html"));

$iTD->trocar("link.MusicaCategorias", "?p=".$_GET['p']."&a=listarMusicaCategorias");
$iTD->trocar("link.Musicas", "?p=".$_GET['p']."&a=listarMusicas");

$iTD->createJavaScript();
$javaScript .= $iTD->javaScript->concluir();

$includePagina = $iTD->concluir();

?>