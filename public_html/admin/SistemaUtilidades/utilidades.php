<?php

$tituloPagina = 'Utilidades';

$iTU = new IFAdmin(new Arquivos(Sistema::$adminLayoutCaminhoDiretorio."/SistemaUtilidades/utilidades.html"));

$iTU->trocar("link.Textos", "?p=".$_GET['p']."&a=listarTextos");
$iTU->trocar("link.Galerias", "?p=".$_GET['p']."&a=galerias");
$iTU->trocar("link.Eventos", "?p=".$_GET['p']."&a=listarEventos");
$iTU->trocar("link.Noticias", "?p=".$_GET['p']."&a=noticias");
$iTU->trocar("link.Recados", "?p=".$_GET['p']."&a=listarRecados");
$iTU->trocar("link.Discografia", "?p=".$_GET['p']."&a=discografia");
$iTU->trocar("link.FAQ", "?p=".$_GET['p']."&a=FAQ");
$iTU->trocar("link.Tickets", "?p=".$_GET['p']."&a=listarTickets");
$iTU->trocar("link.Publicidades", "?p=".$_GET['p']."&a=publicidades");
$iTU->trocar("link.Vendedores", "?p=".$_GET['p']."&a=listarVendedores");
$iTU->trocar("link.UploadsDownloads", "?p=".$_GET['p']."&a=uploadsdownloads");

$iTU->createJavaScript();
$javaScript .= $iTU->javaScript->concluir();

$includePagina = $iTU->concluir();

?>