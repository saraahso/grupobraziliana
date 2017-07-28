<?php

importar("Utilidades.Publicidades.Mailing.Lista.ListaPacoteMailings");

$tituloPagina = 'Utilidades > Publicidades > Mailings > Pacotes > Cadastrar';

$iTCPM = new IFAdmin(new Arquivos(Sistema::$adminLayoutCaminhoDiretorio."/SistemaUtilidades/pacoteMailing.html"));

if(!empty($_POST)){
	
	$erro = '';
		
    if(empty($_POST['titulo']))
	    $erro = "<b>Titulo</b> não preenchido!<br><br>";

	if(empty($erro)){
		
		try{
		
	    $s 				= new PacoteMailing;
		
		$s->titulo 		= $_POST['titulo'];		
		
		$lS				= new ListaPacoteMailings;
		$lS->inserir($s);
		
		
		$_POST = '';
		
	    $javaScript .= Aviso::criar("Pacote salvo com sucesso!");		
		
		}catch(Exception $e){
			
			$javaScript .= Aviso::criar($e->getMessage());
			
		}
	
	}else{
		
		$javaScript .= Aviso::criar($erro);
		
	}
	
}

$iTCPM->condicao('condicao->alterar.Mailing', true);

$iTCPM->trocar("linkVoltar", "?p=".$_GET['p']."&a=listarPacoteMailings");

$iTCPM->trocar("titulo", 		$_POST['titulo']);

$iTCPM->createJavaScript();
$javaScript .= $iTCPM->javaScript->concluir();

$includePagina = $iTCPM->concluir();

?>