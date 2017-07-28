<?php

importar("Utilidades.Galerias.Lista.ListaGaleriaCategorias");
importar("Utils.Dados.JSON");

$tituloPagina = 'Utilidades > Galerias > Categorias';

$iTGC = new IFAdmin(new Arquivos(Sistema::$adminLayoutCaminhoDiretorio."/SistemaUtilidades/listarGaleriaCategorias.html"));

$iTGC->trocar("linkDeletar.GaleriaCategoria", "?p=".$_GET['p']."&a=".$_GET['a']."&");
$iTGC->trocar("linkBuscar.GaleriaCategoria", "?p=".$_GET['p']."&");

if(!empty($_GET['deletar'])){
	
	$lGC = new ListaGaleriaCategorias;
	$lGC->condicoes('', $_GET['deletar'], ListaGaleriaCategorias::ID);
	
	if($lGC->getTotal() > 0){
		
		try{
			$lGC->deletar($lGC->listar());
			$javaScript .= Aviso::criar("Categoria removida com sucesso!");
		}catch(Exception $e){
			
			$javaScript .= Aviso::criar($e->getMessage());	
			
		}
	
	
	}
	
}

$lGC = new ListaGaleriaCategorias;

if(isset($_GET['json'])){
	
	$a = array();
	
	while($bC = $lGC->listar())
		$a[] = array('id' => $bC->getId(), 'nome' => $bC->titulo);
	
	echo JSON::_Encode($a);
	exit;
	
}

$iTGC->createRepeticao("repetir->GaleriaCategorias");

if(!empty($_GET['busca']))
     $lGC->condicoes('', "%".$_GET['busca']."%", 'empresa', 'LIKE');

$iTGC->condicao("condicaoBusca", !empty($_SESSION['nivel']));

$iTGC->trocar("linkCadastrar.GaleriaCategoria", "?p=".$_GET['p']."&a=cadastrarGaleriaCategoria");

while($tx = $lGC->listar("ASC", ListaGaleriaCategorias::TITULO)){
	  
	   $iTGC->repetir();
	   
	   $iTGC->enterRepeticao()->condicao("condicaoRemover", !empty($_SESSION['nivel']));
	   
	   $bgColor = $lGC->getParametros()%2 == 0 ? '#FFFFFF' : '#EAEAEA';
	   $iTGC->enterRepeticao()->trocar("bgColorEmpresa", $bgColor);
	   
	   $iTGC->enterRepeticao()->trocar("id.GaleriaCategoria", $tx->getId());
	   $iTGC->enterRepeticao()->trocar("titulo.GaleriaCategoria", $tx->titulo);
	   
	   $iTGC->enterRepeticao()->trocar("linkAlterar.GaleriaCategoria", "?p=".$_GET['p']."&a=alterarGaleriaCategoria&categoria=".$tx->getId());
	   
	   $iTGC->enterRepeticao()->condicao("condicaoVisualizar", $tx->tipo == 1);
	 
}

$iTGC->trocar("linkVoltar.GaleriaCategoria", "?p=".$_GET['p']."&a=galerias");

$botoes = $iTGC->cutParte('botoes');

$includePagina = $iTGC->concluir();

?>