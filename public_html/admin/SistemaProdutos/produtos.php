<?php

$tituloPagina = 'Produtos';

$iProd = new IFAdmin(new Arquivos(Sistema::$adminLayoutCaminhoDiretorio."/SistemaProdutos/produtos.html"));

$iProd->trocar("link.ProdutoCategorias", "?p=".$_GET['p']."&a=listarProdutoCategorias");
$iProd->trocar("link.ProdutoMarcas", "?p=".$_GET['p']."&a=listarProdutoMarcas");
$iProd->trocar("link.ProdutoCores", "?p=".$_GET['p']."&a=listarProdutoCores");
$iProd->trocar("link.ProdutoTamanhos", "?p=".$_GET['p']."&a=listarProdutoTamanhos");
$iProd->trocar("link.ProdutoPedras", "?p=".$_GET['p']."&a=listarProdutoPedras");
$iProd->trocar("link.Produtos", "?p=".$_GET['p']."&a=listarProdutos");
$iProd->trocar("link.ProdutoOpcoes", "?p=".$_GET['p']."&a=listarProdutoOpcoes");
$iProd->trocar("link.OfertasColetivas", "?p=".$_GET['p']."&a=ofertasColetivas");

$iProd->createJavaScript();
$javaScript .= $iProd->javaScript->concluir();

$includePagina = $iProd->concluir();

?>