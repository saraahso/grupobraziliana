<?php

importar("Geral.Idiomas.Lista.ListaTraducoes");

$tituloPagina = 'Configurações > Idiomas > Traduções > Alterar';

$iTAT = new IFAdmin(new Arquivos(Sistema::$adminLayoutCaminhoDiretorio."/SistemaConfiguracoes/traducao.html"));

$lT			= new ListaTraducoes;
$lT->condicoes('', $_GET['traducao'], ListaTraducoes::ID);

if($lT->getTotal() > 0)
	$t = $lT->listar();
else
	$t = new Traducao;

if(!empty($_POST)){
	
	$erro = '';	
	
    if(empty($_POST['conteudo']))
	    $erro = "<b>Conteúdo</b> não preenchido!<br><br>";
	if(empty($_POST['traducao']))
	    $erro = "<b>Tradução</b> não preenchido!<br><br>";

	if(empty($erro)){
		
		$t->conteudo = $_POST['conteudo'];
		$t->traducao = $_POST['traducao'];
		
		$lT->alterar($t);
		
	    $javaScript .= Aviso::criar("Tradução salva com sucesso!");
	
	}else{
		
		$javaScript .= Aviso::criar($erro);
		
	}
	
}


$iTAT->trocar("conteudo",	$t->conteudo);
$iTAT->trocar("traducao",	$t->traducao);

$iTAT->trocar("linkVoltar", "?p=".$_GET['p']."&a=listarTraducoes&idioma=".$t->getIdIdioma());

$iTAT->createJavaScript();
$javaScript .= $iTAT->javaScript->concluir();

$includePagina = $iTAT->concluir();

?>