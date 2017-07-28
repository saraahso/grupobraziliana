<?php

importar("Geral.Idiomas.Lista.ListaIdiomas");

$tituloPagina = 'Configurações > Idiomas > Alterar Idioma';

$iAI = new IFAdmin(new Arquivos(Sistema::$adminLayoutCaminhoDiretorio."/SistemaConfiguracoes/idioma.html"));

$iAI->trocar('linkVoltar', "?p=".$_GET['p']."&a=listarIdiomas");

$lI = new ListaIdiomas;
$lI->condicoes('', $_GET['id'], 'id');
if($lI->getTotal() > 0)
	$i = $lI->listar();
else
	$i = new Idioma;

if(!empty($_POST)){
	
	$erro = '';
	
	if(empty($_POST['nome']))
	    $erro = "<b>Nome</b> não preenchido!<br><br>";
	if(empty($_POST['sigla']))
	    $erro = "<b>Sigla</b> não preenchido!<br><br>";

	if(empty($erro)){
	
		$i->nome 	= $_POST['nome'];
		$i->sigla 	= $_POST['sigla'];
		
		if(!empty($_FILES['imagem']['name']))
			$i->setImagem(new Image(Arquivos::__OpenArquivoByTEMP($_FILES['imagem'])));
		
		$lI = new ListaIdiomas;
		if($i->getId() != ''){
			$lI->alterar($i);
			$javaScript .= Aviso::criar("Idioma salvo com sucesso!");
		}else
			$javaScript .= Aviso::criar("Idioma não salvo.");
	
	}else{
		
		$javaScript .= Aviso::criar($erro);
		
	}
	
}

$iAI->trocar('nome', 	$i->nome);
$iAI->trocar('sigla', 	$i->sigla);

if($i->getImagem()->nome != '')
	$iAI->trocar('imagem', $i->getImagem()->showHTML(200, 200));

$iAI->createJavaScript();
$javaScript .= $iAI->javaScript->concluir();

$includePagina = $iAI->concluir();

?>