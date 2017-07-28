<?php

importar("Utilidades.Discografia.Lista.ListaMusicas");

$tituloPagina = 'Utilidades > Discografia > Músicas > Alterar';

$iTAM = new IFAdmin(new Arquivos(Sistema::$adminLayoutCaminhoDiretorio."/SistemaUtilidades/musica.html"));

if(!empty($_POST)){
	
	$erro = '';	
	
	if(empty($_POST['titulo']))
		$erro = "<b>Titulo</b> não preenchido!<br><br>";
	
	if(empty($erro)){
		
		$lM = new ListaMusicas;
		$lM->condicoes('', $_GET['musica'], ListaMusicas::ID);
		$m = $lM->listar();
		
		$m->titulo 		= $_POST['titulo'];
		$m->ordem 		= $_POST['ordem'];		
		
		$lM->alterar($m);
		
		$con = BDConexao::__Abrir();
		$con->deletar(Sistema::$BDPrefixo."relacionamento_musicas_categorias", "WHERE musica = '".$m->getId()."'");
		
		$lMC = new ListaMusicaCategorias;
		
		if(count($_POST['categoriasSelecionadas']) > 0){
		
			foreach($_POST['categoriasSelecionadas'] as $valor){
				
				$lMC->condicoes('', $valor, ListaMusicaCategorias::ID);
				
				if($lMC->getTotal() > 0){
					
					$mC = $lMC->listar();
					
					$m->addCategoria($mC);
					
				}
				
			}
		
		}
		
	    $javaScript .= Aviso::criar("Música salva com sucesso!");
	
	}else{
		
		$javaScript .= Aviso::criar($erro);
		
	}
	
}

$lM = new ListaMusicas;
$m = $lM->condicoes('', $_GET['musica'], ListaMusicas::ID)->listar();

$iTAM->condicao('condicao->alterar.Musica', 	false);
$iTAM->condicao('condicao->musica.Musica', 		false);

$iTAM->trocar("linkVoltar", "?p=".$_GET['p']."&a=listarMusicas");

$iTAM->trocar("titulo",				$m->titulo);
$iTAM->trocar("ordem", 				$m->ordem);

$m5 = new MD5;
$iTAM->trocar("url.Musica",			Sistema::$caminhoURL.'lib.conf/abrirArquivo.php?caminho='.$m5->criptografar(Sistema::$caminhoURL.Sistema::$caminhoDataDiscografia.$m->getMusica()->getNome()));
$iTAM->trocar("nome.Musica",		$m->getMusica()->nome.".".$m->getMusica()->extensao);

$iTAM->createRepeticao("repetir->MusicaCategorias.Musica");
while($mC = $m->getCategorias()->listar()){
	
	$iTAM->repetir();
	$iTAM->enterRepeticao()->trocar('id.MusicaCategoria.Musica', $mC->getId());
	$iTAM->enterRepeticao()->trocar('titulo.MusicaCategoria.Musica', $mC->titulo);
	
}

$iTAM->createJavaScript();
$javaScript .= $iTAM->javaScript->concluir();

$includePagina = $iTAM->concluir();

?>