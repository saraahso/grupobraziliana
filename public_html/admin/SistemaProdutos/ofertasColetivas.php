<?php

$tituloPagina = 'Produtos > Ofertas Coletivas';

$iOC = new IFAdmin(new Arquivos(Sistema::$adminLayoutCaminhoDiretorio."/SistemaProdutos/ofertasColetivas.html"));

$iOC->trocar("link.listarEmpresasOfertaColetiva", "?p=".$_GET['p']."&a=listarEmpresasOfertaColetiva");
$iOC->trocar("link.listarOfertasColetivas", "?p=".$_GET['p']."&a=listarOfertasColetivas");

$iOC->createJavaScript();
$javaScript .= $iOC->javaScript->concluir();

$includePagina = $iOC->concluir();

?>