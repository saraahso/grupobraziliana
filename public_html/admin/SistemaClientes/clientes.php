<?php

$tituloPagina = 'Clientes';

$iCli = new IFAdmin(new Arquivos(Sistema::$adminLayoutCaminhoDiretorio."/SistemaClientes/clientes.html"));

$iCli->trocar("link.Clientes", "?p=".$_GET['p']."&a=listarClientes");

$iCli->createJavaScript();
$javaScript .= $iCli->javaScript->concluir();

$includePagina = $iCli->concluir();

?>