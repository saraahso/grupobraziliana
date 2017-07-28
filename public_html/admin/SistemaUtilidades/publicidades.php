<?php

$tituloPagina = 'Utilidades > Publicidades';

$iTPu = new IFAdmin(new Arquivos(Sistema::$adminLayoutCaminhoDiretorio."/SistemaUtilidades/publicidades.html"));

$iTPu->trocar("link.Banners", "?p=".$_GET['p']."&a=banners");
$iTPu->trocar("link.Slides", "?p=".$_GET['p']."&a=slides");
$iTPu->trocar("link.Mailings", "?p=".$_GET['p']."&a=mailings");

$iTPu->createJavaScript();
$javaScript .= $iTPu->javaScript->concluir();

$includePagina = $iTPu->concluir();

?>