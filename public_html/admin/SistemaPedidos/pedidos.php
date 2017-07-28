<?php

$tituloPagina = 'Pedidos';

$iPed = new IFAdmin(new Arquivos(Sistema::$adminLayoutCaminhoDiretorio."/SistemaPedidos/pedidos.html"));

$iPed->trocar("link.Pedidos", "?p=".$_GET['p']."&a=listarPedidos");

$iPed->createJavaScript();
$javaScript .= $iPed->javaScript->concluir();

$includePagina = $iPed->concluir();

?>