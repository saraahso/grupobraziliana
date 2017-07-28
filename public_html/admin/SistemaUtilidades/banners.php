<?php

$tituloPagina = 'Utilidades > Publicidades > Banners';

$iTB = new IFAdmin(new Arquivos(Sistema::$adminLayoutCaminhoDiretorio."/SistemaUtilidades/banners.html"));

$iTB->trocar("link.BannerCategorias", "?p=".$_GET['p']."&a=listarBannerCategorias");
$iTB->trocar("link.Banners", "?p=".$_GET['p']."&a=listarBanners");

$iTB->trocar("linkVoltar", "?p=".$_GET['p']."&a=publicidades");

$iTB->createJavaScript();
$javaScript .= $iTB->javaScript->concluir();

$includePagina = $iTB->concluir();

?>