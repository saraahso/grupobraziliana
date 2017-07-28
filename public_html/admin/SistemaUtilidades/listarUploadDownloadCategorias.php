<?php

importar("Utilidades.UploadsDownloads.Lista.ListaUploadDownloadCategorias");
importar("Utils.Dados.JSON");

$tituloPagina = 'Utilidades > Upload e Download > Categorias';

$iTUDC = new IFAdmin(new Arquivos(Sistema::$adminLayoutCaminhoDiretorio."/SistemaUtilidades/listarUploadDownloadCategorias.html"));

$iTUDC->trocar("linkDeletar.UploadDownloadCategoria", "?p=".$_GET['p']."&a=".$_GET['a']."&");
$iTUDC->trocar("linkBuscar.UploadDownloadCategoria", "?p=".$_GET['p']."&");

if(!empty($_GET['deletar'])){
	
	$lUDC = new ListaUploadDownloadCategorias;
	$lUDC->condicoes('', $_GET['deletar'], ListaUploadDownloadCategorias::ID);
	
	if($lUDC->getTotal() > 0){
		
		try{
			$lUDC->deletar($lUDC->listar());
			$javaScript .= Aviso::criar("Categoria removida com sucesso!");
		}catch(Exception $e){
			
			$javaScript .= Aviso::criar($e->getMessage());	
			
		}
	
	
	}
	
}

$lUDC = new ListaUploadDownloadCategorias;

if(isset($_GET['json'])){
	
	$cond = array();
	
	while($pC = $lUDC->listar()){
		$rs['id'] 	= $pC->getId();
		$rs['nome'] = $pC->titulo;
		$cond[] = $rs;
	}
	
	echo JSON::_Encode($cond);
	exit;
	
}

$iTUDC->createRepeticao("repetir->UploadDownloadCategorias");

if(!empty($_GET['busca']))
     $lUDC->condicoes('', "%".$_GET['busca']."%", 'empresa', 'LIKE');

$iTUDC->condicao("condicaoBusca", !empty($_SESSION['nivel']));

$iTUDC->trocar("linkCadastrar.UploadDownloadCategoria", "?p=".$_GET['p']."&a=cadastrarUploadDownloadCategoria");

while($uDC = $lUDC->listar("ASC", ListaUploadDownloadCategorias::TITULO)){
	  
	   $iTUDC->repetir();
	   
	   $iTUDC->enterRepeticao()->condicao("condicaoRemover", !empty($_SESSION['nivel']));
	   
	   $bgColor = $lUDC->getParametros()%2 == 0 ? '#FFFFFF' : '#EAEAEA';
	   $iTUDC->enterRepeticao()->trocar("bgColorEmpresa", $bgColor);
	   
	   $iTUDC->enterRepeticao()->trocar("id.UploadDownloadCategoria", $uDC->getId());
	   $iTUDC->enterRepeticao()->trocar("titulo.UploadDownloadCategoria", $uDC->titulo);
	   
	   $iTUDC->enterRepeticao()->trocar("linkAlterar.UploadDownloadCategoria", "?p=".$_GET['p']."&a=alterarUploadDownloadCategoria&categoria=".$uDC->getId());
	   
	   $iTUDC->enterRepeticao()->condicao("condicaoVisualizar", $uDC->tipo == 1);
	 
}

$iTUDC->trocar("linkVoltar.UploadDownloadCategoria", "?p=".$_GET['p']."&a=uploadsdownloads");

$botoes = $iTUDC->cutParte('botoes');

$includePagina = $iTUDC->concluir();

?>