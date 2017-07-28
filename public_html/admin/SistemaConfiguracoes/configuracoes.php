<?php

$tituloPagina = "Configurações";

$iConfig = new IFAdmin(new Arquivos(Sistema::$adminLayoutCaminhoDiretorio."/SistemaConfiguracoes/configuracoes.html"));

$iConfig->trocar("linkIdiomas", "?p=".$_GET['p']."&a=listarIdiomas");
$iConfig->trocar("linkMarcadAgua", "?p=".$_GET['p']."&a=marcadAgua");
$iConfig->trocar("linkProdutos", "?p=".$_GET['p']."&a=produtos");
$iConfig->trocar("linkFrete", "?p=".$_GET['p']."&a=frete");
$iConfig->trocar("linkPagamentos", "?p=".$_GET['p']."&a=pagamentos");
$iConfig->trocar("linkUsuarios", "?p=".$_GET['p']."&a=listarUsuarios");

$iConfig->condicao('condicao->1.Nivel', $_SESSION['nivel'] == 1);

$iConfig->createJavaScript();
$javaScript .= $iConfig->javaScript->concluir();

$includePagina = $iConfig->concluir();

?>