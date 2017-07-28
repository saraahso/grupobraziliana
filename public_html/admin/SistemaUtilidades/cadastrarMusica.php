<?php

importar("Utilidades.Discografia.Lista.ListaMusicas");

$tituloPagina = 'Utilidades > Discografia > Músicas > Cadastrar';

$iTCM = new IFAdmin(new Arquivos(Sistema::$adminLayoutCaminhoDiretorio."/SistemaUtilidades/musica.html"));

if(!empty($_POST)){
	
	$erro = '';
	
	if(empty($_POST['titulo']))
		$erro = "<b>Titulo</b> não preenchido!<br><br>";

	if(empty($erro)){
		
		try{
		
	    $m 				= new Musica;
		
		$m->titulo 		= $_POST['titulo'];
		$m->ordem 		= $_POST['ordem'];	
		
		if(!empty($_FILES['musica']['name']))
			$m->setMusica(Arquivos::__OpenArquivoByTEMP($_FILES['musica']));
		
		$lM				= new ListaMusicas;
		$lM->inserir($m);
		
		$_POST = '';
		
	    $javaScript .= Aviso::criar("Música salva com sucesso!");		
		
		}catch(Exception $e){
			
			$javaScript .= Aviso::criar($e->getMessage());
			
		}
	
	}else{
		
		$javaScript .= Aviso::criar($erro);
		
	}
	
}

$iTCM->condicao('condicao->alterar.Musica', true);
$iTCM->condicao('condicao->musica.Musica', true);

$iTCM->trocar("linkVoltar", "?p=".$_GET['p']."&a=listarMusicas");

$iTCM->trocar("titulo",		$_POST['titulo']);
$iTCM->trocar("ordem", 		$_POST['ordem']);

$iTCM->createRepeticao("repetir->MusicaCategorias.Musica");

$iTCM->createJavaScript();
$javaScript .= $iTCM->javaScript->concluir();

$includePagina = $iTCM->concluir();

?>