<?php

importar("Utilidades.Publicidades.Banners.Lista.ListaBanners");

$tituloPagina = 'Utilidades > Publicidades > Banners';

$iTLP = new IFAdmin(new Arquivos(Sistema::$adminLayoutCaminhoDiretorio."/SistemaUtilidades/listarBanners.html"));

$iTLP->trocar("linkDeletar.Banner", "?p=".$_GET['p']."&a=".$_GET['a']."&");
$iTLP->trocar("linkBuscar.Banner", "?p=".$_GET['p']."&");

if(!empty($_GET['deletar'])){
	
	$lT = new ListaBanners;
	$lT->condicoes('', $_GET['deletar'], ListaBanners::ID);
	
	if($lT->getTotal() > 0){
		
		try{
			$lT->deletar($lT->listar());
			$javaScript .= Aviso::criar("Banner removido com sucesso!");
		}catch(Exception $e){
			
			$javaScript .= Aviso::criar($e->getMessage());	
			
		}
	
	
	}
	
}

$lT = new ListaBanners;
$iTLP->createRepeticao("repetir->Banners");

if(!empty($_GET['busca']))
     $lT->condicoes('', "%".$_GET['busca']."%", 'empresa', 'LIKE');

$iTLP->condicao("condicaoBusca", !empty($_SESSION['nivel']));

$iTLP->trocar("linkCadastrar.Banner", "?p=".$_GET['p']."&a=cadastrarBanner");

$lT->condicoes($a);

while($tx = $lT->listar("ASC", ListaBanners::TITULO)){
	  
	   $iTLP->repetir();
	   
	   $iTLP->enterRepeticao()->condicao("condicaoRemover", !empty($_SESSION['nivel']));
	   
	   $bgColor = $lT->getParametros()%2 == 0 ? '#FFFFFF' : '#EAEAEA';
	   $iTLP->enterRepeticao()->trocar("bgColorEmpresa", $bgColor);
	   
	   $iTLP->enterRepeticao()->trocar("id.Banner", $tx->getId());
	   $iTLP->enterRepeticao()->trocar("titulo.Banner", $tx->titulo);
	   $iTLP->enterRepeticao()->trocar("linkVisualizar.Banner", "?p=".$_GET['p']."&a=listarBanners&banner=".$tx->getId());
	   $iTLP->enterRepeticao()->trocar("linkAlterar.Banner", "?p=".$_GET['p']."&a=alterarBanner&banner=".$tx->getId());
	   
	   $iTLP->enterRepeticao()->condicao("condicaoVisualizar", $tx->tipo == 1);
	 
}

$iTLP->trocar("linkVoltar.Banner", "?p=".$_GET['p']."&a=banners");

$botoes = $iTLP->cutParte('botoes');

$includePagina = $iTLP->concluir();

?>