<?php

importar("Utilidades.FAQ.Lista.ListaPerguntas");

$tituloPagina = 'Utilidades > FAQ > Perguntas';

$iTLP = new IFAdmin(new Arquivos(Sistema::$adminLayoutCaminhoDiretorio."/SistemaUtilidades/listarPerguntas.html"));

$iTLP->trocar("linkDeletar.Pergunta", "?p=".$_GET['p']."&a=".$_GET['a']."&");
$iTLP->trocar("linkBuscar.Pergunta", "?p=".$_GET['p']."&");

if(!empty($_GET['deletar'])){
	
	$lT = new ListaPerguntas;
	$lT->condicoes('', $_GET['deletar'], ListaPerguntas::ID);
	
	if($lT->getTotal() > 0){
		
		try{
			$lT->deletar($lT->listar());
			$javaScript .= Aviso::criar("Pergunta removida com sucesso!");
		}catch(Exception $e){
			
			$javaScript .= Aviso::criar($e->getMessage());	
			
		}
	
	
	}
	
}

$lT = new ListaPerguntas;
$iTLP->createRepeticao("repetir->Perguntas");

if(!empty($_GET['busca']))
     $lT->condicoes('', "%".$_GET['busca']."%", 'empresa', 'LIKE');

$iTLP->condicao("condicaoBusca", !empty($_SESSION['nivel']));

$iTLP->trocar("linkCadastrar.Pergunta", "?p=".$_GET['p']."&a=cadastrarPergunta");

$a[1] = array('campo' => Lista::URL, 'valor' => '', 'operador' => '<>');

$lT->condicoes($a);

while($tx = $lT->listar("ASC", ListaPerguntas::TITULO)){
	  
	   $iTLP->repetir();
	   
	   $iTLP->enterRepeticao()->condicao("condicaoRemover", !empty($_SESSION['nivel']));
	   
	   $bgColor = $lT->getParametros()%2 == 0 ? '#FFFFFF' : '#EAEAEA';
	   $iTLP->enterRepeticao()->trocar("bgColorEmpresa", $bgColor);
	   
	   $iTLP->enterRepeticao()->trocar("id.Pergunta", $tx->getId());
	   $iTLP->enterRepeticao()->trocar("titulo.Pergunta", $tx->titulo);
	   $iTLP->enterRepeticao()->trocar("linkVisualizar.Pergunta", "?p=".$_GET['p']."&a=listarPerguntas&pergunta=".$tx->getId());
	   $iTLP->enterRepeticao()->trocar("linkAlterar.Pergunta", "?p=".$_GET['p']."&a=alterarPergunta&pergunta=".$tx->getId());
	   
	   $iTLP->enterRepeticao()->condicao("condicaoVisualizar", $tx->tipo == 1);
	 
}

$iTLP->trocar("linkVoltar.Pergunta", "?p=".$_GET['p']."&a=FAQ");

$botoes = $iTLP->cutParte('botoes');

$includePagina = $iTLP->concluir();

?>