<?php

importar("Utilidades.Noticias.Lista.ListaNoticiaCategorias");
importar("Utils.Dados.JSON");

$tituloPagina = 'Utilidades > Noticias > Categorias';

$iTGC = new IFAdmin(new Arquivos(Sistema::$adminLayoutCaminhoDiretorio."/SistemaUtilidades/listarNoticiaCategorias.html"));

$iTGC->trocar("linkDeletar.NoticiaCategoria", "?p=".$_GET['p']."&a=".$_GET['a']."&");
$iTGC->trocar("linkBuscar.NoticiaCategoria", "?p=".$_GET['p']."&");

if(!empty($_GET['deletar'])){
	
	$lGC = new ListaNoticiaCategorias;
	$lGC->condicoes('', $_GET['deletar'], ListaNoticiaCategorias::ID);
	
	if($lGC->getTotal() > 0){
		
		try{
			$lGC->deletar($lGC->listar());
			$javaScript .= Aviso::criar("Categoria removida com sucesso!");
		}catch(Exception $e){
			
			$javaScript .= Aviso::criar($e->getMessage());	
			
		}
	
	
	}
	
}

$lGC = new ListaNoticiaCategorias;

if(isset($_GET['json'])){
	
	$a = array();
	
	while($bC = $lGC->listar())
		$a[] = array('id' => $bC->getId(), 'nome' => $bC->getTexto()->titulo);
	
	echo JSON::_Encode($a);
	exit;
	
}

$iTGC->createRepeticao("repetir->NoticiaCategorias");

if(!empty($_GET['busca']))
     $lGC->condicoes('', "%".$_GET['busca']."%", 'empresa', 'LIKE');

$iTGC->condicao("condicaoBusca", !empty($_SESSION['nivel']));

$iTGC->trocar("linkCadastrar.NoticiaCategoria", "?p=".$_GET['p']."&a=cadastrarNoticiaCategoria");

while($tx = $lGC->listar("ASC", ListaNoticiaCategorias::ORDEM)){
	  
	   $iTGC->repetir();
	   
	   $iTGC->enterRepeticao()->condicao("condicaoRemover", !empty($_SESSION['nivel']));
	   
	   $bgColor = $lGC->getParametros()%2 == 0 ? '#FFFFFF' : '#EAEAEA';
	   $iTGC->enterRepeticao()->trocar("bgColorEmpresa", $bgColor);
	   
	   $iTGC->enterRepeticao()->trocar("id.NoticiaCategoria", $tx->getId());
	   $iTGC->enterRepeticao()->trocar("titulo.NoticiaCategoria", $tx->getTexto()->titulo);
	   
	   $iTGC->enterRepeticao()->trocar("linkAlterar.NoticiaCategoria", "?p=".$_GET['p']."&a=alterarNoticiaCategoria&categoria=".$tx->getId());
	   
	   $iTGC->enterRepeticao()->condicao("condicaoVisualizar", $tx->tipo == 1);
	 
}

$iTGC->trocar("linkVoltar.NoticiaCategoria", "?p=".$_GET['p']."&a=noticias");

$botoes = $iTGC->cutParte('botoes');

$includePagina = $iTGC->concluir();

?>