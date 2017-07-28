<?php

importar("Utilidades.Discografia.Lista.ListaMusicas");

$tituloPagina = 'Utilidades > Discografia > Músicas';

$lTM = new IFAdmin(new Arquivos(Sistema::$adminLayoutCaminhoDiretorio."/SistemaUtilidades/listarMusicas.html"));

$lTM->trocar("linkDeletar.Musica", "?p=".$_GET['p']."&a=".$_GET['a']."&");
$lTM->trocar("linkBuscar.Musica", "?p=".$_GET['p']."&");

if(!empty($_GET['deletar'])){
	
	$lM = new ListaMusicas;
	$lM->condicoes('', $_GET['deletar'], ListaMusicas::ID);
	
	if($lM->getTotal() > 0){
		
		try{
			$lM->deletar($lM->listar());
			$javaScript .= Aviso::criar("Música removida com sucesso!");
		}catch(Exception $e){
			
			$javaScript .= Aviso::criar($e->getMessage());	
			
		}
	
	
	}
	
}

$lM = new ListaMusicas;
$lTM->createRepeticao("repetir->Musicas");

if(!empty($_GET['busca']))
     $lM->condicoes('', "%".$_GET['busca']."%", 'empresa', 'LIKE');

$lTM->condicao("condicaoBusca", !empty($_SESSION['nivel']));

$lTM->trocar("linkCadastrar.Musica", "?p=".$_GET['p']."&a=cadastrarMusica");

$lM->condicoes($a);

$m5 = new MD5;

while($m = $lM->listar("ASC", ListaMusicas::MUSICA)){
	  
	   $lTM->repetir();
	   
	   $lTM->enterRepeticao()->condicao("condicaoRemover", !empty($_SESSION['nivel']));
	   
	   $bgColor = $lM->getParametros()%2 == 0 ? '#FFFFFF' : '#EAEAEA';
	   $lTM->enterRepeticao()->trocar("bgColorEmpresa", $bgColor);
	   
	   $lTM->enterRepeticao()->trocar("id.Musica", $m->getId());
	   $lTM->enterRepeticao()->trocar("titulo.Musica", $m->titulo);
	   $lTM->enterRepeticao()->trocar("nome.Musica.Musica", $m->getMusica()->nome.".".$m->getMusica()->extensao);
	   $lTM->enterRepeticao()->trocar("url.Musica.Musica", Sistema::$caminhoURL.'lib.conf/abrirArquivo.php?caminho='.$m5->criptografar(Sistema::$caminhoURL.Sistema::$caminhoDataDiscografia.$m->getMusica()->getNome()));
	   $lTM->enterRepeticao()->trocar("linkVisualizar.Musica", "?p=".$_GET['p']."&a=listarMusicas&musica=".$m->getId());
	   $lTM->enterRepeticao()->trocar("linkAlterar.Musica", "?p=".$_GET['p']."&a=alterarMusica&musica=".$m->getId());
	   
	   $lTM->enterRepeticao()->condicao("condicaoVisualizar", $m->tipo == 1);
	 
}

$lTM->trocar("linkVoltar.Musica", "?p=".$_GET['p']."&a=discografia");

$botoes = $lTM->cutParte('botoes');

$includePagina = $lTM->concluir();

?>