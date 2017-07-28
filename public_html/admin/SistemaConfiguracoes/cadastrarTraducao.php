<?php

importar("Geral.Idiomas.Lista.ListaIdiomas");

$tituloPagina = 'Configurações > Idiomas > Traduções > Cadastrar';

$iTCT = new IFAdmin(new Arquivos(Sistema::$adminLayoutCaminhoDiretorio."/SistemaConfiguracoes/traducao.html"));

$lI			= new ListaIdiomas;
$lI->condicoes('', $_GET['idioma'], ListaIdiomas::ID);

if($lI->getTotal() > 0)
	$i = $lI->listar();
else
	$i = new Idioma;
	
if(!empty($_POST)){
	
	$erro = '';
		
    if(empty($_POST['conteudo']))
	    $erro = "<b>Conteúdo</b> não preenchido!<br><br>";
	if(empty($_POST['traducao']))
	    $erro = "<b>Tradução</b> não preenchido!<br><br>";

	if(empty($erro)){
		
		try{
		
	    $tr 				= new Traducao;
		
		$tr->conteudo	= $_POST['conteudo'];		
		$tr->traducao	= $_POST['traducao'];
		
		$i->addTraducao($tr);
		
		$_POST = '';
		
	    $javaScript .= Aviso::criar("Tradução salva com sucesso!");		
		
		}catch(Exception $e){
			
			$javaScript .= Aviso::criar($e->getMessage());
			
		}
	
	}else{
		
		$javaScript .= Aviso::criar($erro);
		
	}
	
}


$iTCT->trocar("linkVoltar", "?p=".$_GET['p']."&a=listarTraducoes&idioma=".$i->getId());

$iTCT->trocar("conteudo", 	$_POST['conteudo']);
$iTCT->trocar("traducao",	$_POST['traducao']);

$iTCT->createJavaScript();
$javaScript .= $iTCT->javaScript->concluir();

$includePagina = $iTCT->concluir();

?>