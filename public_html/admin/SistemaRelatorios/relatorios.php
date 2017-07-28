<?php

$tituloPagina = 'Relatórios';

$iRel = new IFAdmin(new Arquivos(Sistema::$adminLayoutCaminhoDiretorio."/SistemaRelatorios/relatorios.html"));

$iRel->trocar("link.Produtos", "?p=".$_GET['p']."&a=relatorioProdutos");
$iRel->trocar("link.Imagens", "?p=".$_GET['p']."&a=relatorioImagens");
$iRel->trocar("link.Pedidos", "?p=".$_GET['p']."&a=relatorioPedidos");
$iRel->trocar("link.Clientes", "?p=".$_GET['p']."&a=relatorioClientes");

$iRel->condicao("condicao->1.Nivel", $_SESSION['nivel'] == 1);

$iRel->createJavaScript();
$javaScript .= $iRel->javaScript->concluir();

$includePagina = $iRel->concluir();

?>