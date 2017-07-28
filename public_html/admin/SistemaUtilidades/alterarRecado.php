<?php

importar("Utilidades.Lista.ListaRecados");

$tituloPagina = 'Utilidades > Recados > Alterar';

$iAR = new IFAdmin(new Arquivos(Sistema::$adminLayoutCaminhoDiretorio."/SistemaUtilidades/recado.html"));

if(!empty($_POST)){
	
	$erro = '';	

	if(empty($erro)){
		
		$lR = new ListaRecados;
		$lR->condicoes('', $_GET['recado'], ListaRecados::ID);
		$r = $lR->listar();
		
		$r->getTexto()->titulo = $_POST['titulo'];
		$r->local = $_POST['local'];
		$r->nome = $_POST['nome'];
		$r->email = $_POST['email'];
		$r->getTexto()->texto	= html_entity_decode($_POST['texto']);
		
		if($_POST['liberado'] == ListaRecados::VALOR_LIBERADO_TRUE)
			$r->liberar();
		else
			$r->trancar();
		
		if(!empty($_FILES['imagem']['name']))
			$r->getTexto()->getImagem()->setImage(new Image(Arquivos::__OpenArquivoByTEMP($_FILES['imagem'])));
		
		$lR->alterar($r);
		
		$lT = new listaTextos;
		
	    $javaScript .= Aviso::criar("Recado salvo com sucesso!");
	
	}else{
		
		$javaScript .= Aviso::criar($erro);
		
	}
	
}

$lR = new ListaRecados;
$t = $lR->condicoes('', $_GET['recado'], ListaRecados::ID)->listar();

$iAR->trocar("titulo",		$t->getTexto()->titulo);
$iAR->trocar("local",		$t->local);
$iAR->trocar("nome", 		$t->nome);
$iAR->trocar("email", 		$t->email);
$iAR->trocar("liberado", 	$t->getSituacao() ? ListaRecados::VALOR_LIBERADO_TRUE : ListaRecados::VALOR_LIBERADO_FALSE);
$iAR->trocar("data", 		$t->getData()->mostrar("d/m/Y"));
$iAR->trocar("texto", 		$t->getTexto()->texto);


$iAR->trocar("linkVoltar", "?p=".$_GET['p']."&a=listarRecados");

if($t->getTexto()->getImagem()->getImage()->nome != '')
	$iAR->trocar("imagem", $t->getTexto()->getImagem()->getImage()->showHTML(200, 200));

$iAR->createJavaScript();
$javaScript .= $iAR->javaScript->concluir();

$includePagina = $iAR->concluir();

?>