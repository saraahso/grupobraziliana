<?php

importar("Utilidades.Galerias.Lista.ListaGalerias");

$tituloPagina = 'Utilidades > Galerias';

$ILT = new IFAdmin(new Arquivos(Sistema::$adminLayoutCaminhoDiretorio."/SistemaUtilidades/listarGalerias.html"));

$ILT->trocar("linkDeletar.Galeria", "?p=".$_GET['p']."&a=".$_GET['a']."&");
$ILT->trocar("linkBuscar.Galeria", "?p=".$_GET['p']."&");

if(!empty($_GET['deletar'])){
	
	$lT = new ListaGalerias;
	$lT->condicoes('', $_GET['deletar'], ListaGalerias::ID);
	
	if($lT->getTotal() > 0){
		
		try{
			$lT->deletar($lT->listar());
			$javaScript .= Aviso::criar("Galeria removida com sucesso!");
		}catch(Exception $e){
			
			$javaScript .= Aviso::criar($e->getMessage());	
			
		}
	
	
	}
	
}

$lT = new ListaGalerias;
$ILT->createRepeticao("repetir->Galerias");

if(!empty($_GET['busca']))
     $lT->condicoes('', "%".$_GET['busca']."%", 'empresa', 'LIKE');

$ILT->condicao("condicaoBusca", !empty($_SESSION['nivel']));

$ILT->trocar("linkCadastrar.Galeria", "?p=".$_GET['p']."&a=cadastrarGaleria");

while($g = $lT->listar("DESC", ListaGalerias::DATA)){
	  
	   $ILT->repetir();
	   
	   $ILT->enterRepeticao()->condicao("condicaoRemover", !empty($_SESSION['nivel']));
	   
	   $bgColor = $lT->getParametros()%2 == 0 ? '#FFFFFF' : '#EAEAEA';
	   $ILT->enterRepeticao()->trocar("bgColorEmpresa", $bgColor);
	   
	   $ILT->enterRepeticao()->trocar("id.Galeria", $g->getId());
	   $ILT->enterRepeticao()->trocar("titulo.Galeria", $g->titulo);
	   $ILT->enterRepeticao()->trocar("tipo.Galeria", $g->tipo);

	   $ILT->enterRepeticao()->trocar("linkAlterar.Galeria", "?p=".$_GET['p']."&a=alterarGaleria&galeria=".$g->getId());
	   
	   $ILT->enterRepeticao()->condicao("condicaoVisualizar", $g->tipo == 1);
	 
}

$ILT->trocar("linkVoltar", "?p=".$_GET['p']."&a=utilidades");

$botoes = $ILT->cutParte('botoes');

$includePagina = $ILT->concluir();

?>