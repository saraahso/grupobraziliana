<?php

importar("Utilidades.FAQ.Lista.ListaPerguntaCategorias");
importar("Utils.Ajax");

$tituloPagina = 'Utilidades > FAQ > Categorias';

$iTPC = new IFAdmin(new Arquivos(Sistema::$adminLayoutCaminhoDiretorio."/SistemaUtilidades/listarPerguntaCategorias.html"));

$iTPC->trocar("linkDeletar.PerguntaCategoria", "?p=".$_GET['p']."&a=".$_GET['a']."&");
$iTPC->trocar("linkBuscar.PerguntaCategoria", "?p=".$_GET['p']."&");

if(!empty($_GET['deletar'])){
	
	$lPC = new ListaPerguntaCategorias;
	$lPC->condicoes('', $_GET['deletar'], ListaPerguntaCategorias::ID);
	
	if($lPC->getTotal() > 0){
		
		try{
			$lPC->deletar($lPC->listar());
			$javaScript .= Aviso::criar("Categoria removida com sucesso!");
		}catch(Exception $e){
			
			$javaScript .= Aviso::criar($e->getMessage());	
			
		}
	
	
	}
	
}

$lPC = new ListaPerguntaCategorias;

if(isset($_GET['json'])){
	
	$cond['lista'] 	= true;
	
	while($pC = $lPC->listar()){
		$cond[$lPC->getParametros()]['id'] 	= $pC->getId();
		$cond[$lPC->getParametros()]['nome'] = $pC->titulo;
	}
	
	$ajax = new Ajax;
	echo $ajax->getJSON()->converter($cond);
	exit;
	
}

$iTPC->createRepeticao("repetir->PerguntaCategorias");

if(!empty($_GET['busca']))
     $lPC->condicoes('', "%".$_GET['busca']."%", 'empresa', 'LIKE');

$iTPC->condicao("condicaoBusca", !empty($_SESSION['nivel']));

$iTPC->trocar("linkCadastrar.PerguntaCategoria", "?p=".$_GET['p']."&a=cadastrarPerguntaCategoria");

while($tx = $lPC->listar("ASC", ListaPerguntaCategorias::TITULO)){
	  
	   $iTPC->repetir();
	   
	   $iTPC->enterRepeticao()->condicao("condicaoRemover", !empty($_SESSION['nivel']));
	   
	   $bgColor = $lPC->getParametros()%2 == 0 ? '#FFFFFF' : '#EAEAEA';
	   $iTPC->enterRepeticao()->trocar("bgColorEmpresa", $bgColor);
	   
	   $iTPC->enterRepeticao()->trocar("id.PerguntaCategoria", $tx->getId());
	   $iTPC->enterRepeticao()->trocar("titulo.PerguntaCategoria", $tx->titulo);
	   
	   $iTPC->enterRepeticao()->trocar("linkAlterar.PerguntaCategoria", "?p=".$_GET['p']."&a=alterarPerguntaCategoria&categoria=".$tx->getId());
	   
	   $iTPC->enterRepeticao()->condicao("condicaoVisualizar", $tx->tipo == 1);
	 
}

$iTPC->trocar("linkVoltar.PerguntaCategoria", "?p=".$_GET['p']."&a=FAQ");

$botoes = $iTPC->cutParte('botoes');

$includePagina = $iTPC->concluir();

?>