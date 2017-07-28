<?php

importar("Geral.Lista.ListaTextos");

$tituloPagina = 'Utilidades > Textos';

$ILT = new IFAdmin(new Arquivos(Sistema::$adminLayoutCaminhoDiretorio."/SistemaUtilidades/listarTextos.html"));

$ILT->trocar("linkDeletar.Texto", "?p=".$_GET['p']."&a=".$_GET['a']."&");
$ILT->trocar("linkBuscar.Texto", "?p=".$_GET['p']."&");

if(!empty($_GET['deletar'])){
	
	$lT = new ListaTextos;
	$lT->condicoes('', $_GET['deletar'], ListaTextos::ID);
	
	if($lT->getTotal() > 0){
		
		try{
			$lT->deletar($lT->listar());
			$javaScript .= Aviso::criar("Texto removido com sucesso!");
		}catch(Exception $e){
			
			$javaScript .= Aviso::criar($e->getMessage());	
			
		}
	
	
	}
	
}

$lT = new ListaTextos;
$ILT->createRepeticao("repetir->Textos");

if(!empty($_GET['busca']))
     $lT->condicoes('', "%".$_GET['busca']."%", 'empresa', 'LIKE');

$ILT->condicao("condicaoBusca", !empty($_SESSION['nivel']));

$ILT->trocar("linkCadastrar.Texto", "?p=".$_GET['p']."&a=cadastrarTexto");

$a[1] = array('campo' => Lista::URL, 'valor' => '', 'operador' => '<>');

$lT->condicoes($a);

while($tx = $lT->listar("ASC", ListaTextos::TITULO)){
	  
	   $ILT->repetir();
	   
	   $ILT->enterRepeticao()->condicao("condicaoRemover", !empty($_SESSION['nivel']));
	   
	   $bgColor = $lT->getParametros()%2 == 0 ? '#FFFFFF' : '#EAEAEA';
	   $ILT->enterRepeticao()->trocar("bgColorEmpresa", $bgColor);
	   
	   $ILT->enterRepeticao()->trocar("id.Texto", $tx->getId());
	   $ILT->enterRepeticao()->trocar("titulo.Texto", $tx->titulo);
	   $ILT->enterRepeticao()->trocar("linkVisualizar.Texto", "?p=".$_GET['p']."&a=listarTextos&texto=".$tx->getId());
	   $ILT->enterRepeticao()->trocar("linkAlterar.Texto", "?p=".$_GET['p']."&a=alterarTexto&texto=".$tx->getId());
	   
	   $ILT->enterRepeticao()->condicao("condicaoVisualizar", $tx->tipo == 1);
	 
}

$botoes = $ILT->cutParte('botoes');
$ILT->trocar('link.Voltar', "?p=".$_GET['p']."&a=utilidades");

$includePagina = $ILT->concluir();

?>