<?php

$tituloPagina = 'Utilidades > FAQ';

$iTF = new IFAdmin(new Arquivos(Sistema::$adminLayoutCaminhoDiretorio."/SistemaUtilidades/FAQ.html"));

$iTF->trocar("link.PerguntaCategorias", "?p=".$_GET['p']."&a=listarPerguntaCategorias");
$iTF->trocar("link.Perguntas", "?p=".$_GET['p']."&a=listarPerguntas");

$iTF->createJavaScript();
$javaScript .= $iTF->javaScript->concluir();

$includePagina = $iTF->concluir();

?>