<?php

importar("Utilidades.Publicidades.Banners.Lista.ListaBannerCategorias");
importar("Utils.Dados.JSON");

$tituloPagina = 'Utilidades > Publicidades > Banners > Categorias';

$iTBC = new IFAdmin(new Arquivos(Sistema::$adminLayoutCaminhoDiretorio."/SistemaUtilidades/listarBannerCategorias.html"));

$iTBC->trocar("linkDeletar.BannerCategoria", "?p=".$_GET['p']."&a=".$_GET['a']."&");
$iTBC->trocar("linkBuscar.BannerCategoria", "?p=".$_GET['p']."&");

if(!empty($_GET['deletar'])){
	
	$lBC = new ListaBannerCategorias;
	$lBC->condicoes('', $_GET['deletar'], ListaBannerCategorias::ID);
	
	if($lBC->getTotal() > 0){
		
		try{
			$lBC->deletar($lBC->listar());
			$javaScript .= Aviso::criar("Categoria removida com sucesso!");
		}catch(Exception $e){
			
			$javaScript .= Aviso::criar($e->getMessage());	
			
		}
	
	
	}
	
}

$lBC = new ListaBannerCategorias;

if(isset($_GET['json'])){
	
	$a = array();
	
	while($bC = $lBC->listar())
		$a[] = array('id' => $bC->getId(), 'nome' => $bC->titulo);
	
	echo JSON::_Encode($a);
	exit;
	
}

$iTBC->createRepeticao("repetir->BannerCategorias");

if(!empty($_GET['busca']))
     $lBC->condicoes('', "%".$_GET['busca']."%", 'empresa', 'LIKE');

$iTBC->condicao("condicaoBusca", !empty($_SESSION['nivel']));

$iTBC->trocar("linkCadastrar.BannerCategoria", "?p=".$_GET['p']."&a=cadastrarBannerCategoria");

while($tx = $lBC->listar("ASC", ListaBannerCategorias::TITULO)){
	  
	   $iTBC->repetir();
	   
	   $iTBC->enterRepeticao()->condicao("condicaoRemover", !empty($_SESSION['nivel']));
	   
	   $bgColor = $lBC->getParametros()%2 == 0 ? '#FFFFFF' : '#EAEAEA';
	   $iTBC->enterRepeticao()->trocar("bgColorEmpresa", $bgColor);
	   
	   $iTBC->enterRepeticao()->trocar("id.BannerCategoria", $tx->getId());
	   $iTBC->enterRepeticao()->trocar("titulo.BannerCategoria", $tx->titulo);
	   
	   $iTBC->enterRepeticao()->trocar("linkAlterar.BannerCategoria", "?p=".$_GET['p']."&a=alterarBannerCategoria&categoria=".$tx->getId());
	   
	   $iTBC->enterRepeticao()->condicao("condicaoVisualizar", $tx->tipo == 1);
	 
}

$iTBC->trocar("linkVoltar.BannerCategoria", "?p=".$_GET['p']."&a=banners");

$botoes = $iTBC->cutParte('botoes');

$includePagina = $iTBC->concluir();

?>