<?php

$tituloPagina = 'Utilidades > Galerias';

$iTG = new IFAdmin(new Arquivos(Sistema::$adminLayoutCaminhoDiretorio."/SistemaUtilidades/galerias.html"));

$iTG->trocar("link.GaleriaCategorias", "?p=".$_GET['p']."&a=listarGaleriaCategorias");
$iTG->trocar("link.Galerias", "?p=".$_GET['p']."&a=listarGalerias");

$iTG->createJavaScript();
$javaScript .= $iTG->javaScript->concluir();

$includePagina = $iTG->concluir();

?>