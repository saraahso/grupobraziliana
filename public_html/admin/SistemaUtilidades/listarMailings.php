<?php

importar("Utilidades.Publicidades.Mailing.Lista.ListaMailings");

$tituloPagina = 'Utilidades > Publicidades > Mailings';

$iTLP = new IFAdmin(new Arquivos(Sistema::$adminLayoutCaminhoDiretorio."/SistemaUtilidades/listarMailings.html"));

$iTLP->trocar("linkDeletar.Mailing", "?p=".$_GET['p']."&a=".$_GET['a']."&");
$iTLP->trocar("linkBuscar.Mailing", "?p=".$_GET['p']."&");

if(!empty($_GET['deletar'])){
	
	$lT = new ListaMailings;
	$lT->condicoes('', $_GET['deletar'], ListaMailings::ID);
	
	if($lT->getTotal() > 0){
		
		try{
			$lT->deletar($lT->listar());
			$javaScript .= Aviso::criar("Mailing removido com sucesso!");
		}catch(Exception $e){
			
			$javaScript .= Aviso::criar($e->getMessage());	
			
		}
	
	
	}
	
}

$lT = new ListaMailings;
$iTLP->createRepeticao("repetir->Mailings");

if(!empty($_GET['busca']))
     $lT->condicoes('', "%".$_GET['busca']."%", 'empresa', 'LIKE');

$iTLP->condicao("condicaoBusca", !empty($_SESSION['nivel']));

$iTLP->trocar("linkCriar.Mailing", "?p=".$_GET['p']."&a=criarMailing");

$lT->condicoes($a);

while($s = $lT->listar("ASC", ListaMailings::DATA)){
	  
	   $iTLP->repetir();
	   
	   $iTLP->enterRepeticao()->condicao("condicaoRemover", !empty($_SESSION['nivel']));
	   
	   $bgColor = $lT->getParametros()%2 == 0 ? '#FFFFFF' : '#EAEAEA';
	   $iTLP->enterRepeticao()->trocar("bgColorEmpresa", $bgColor);
	  
	   $iTLP->enterRepeticao()->trocar("id.Mailing", $s->getId());
	   $iTLP->enterRepeticao()->trocar("titulo.Mailing", $s->getTexto()->titulo);
	   
	   $iTLP->enterRepeticao()->trocar("status.Mailing", $s->getStatus() == 1 ? 'Parado' : 'Em Processo');
	   
	   $iTLP->enterRepeticao()->trocar("linkVisualizar.Mailing", "?p=".$_GET['p']."&a=listarMailings&mailing=".$s->getId());
	   $iTLP->enterRepeticao()->trocar("linkEnviar.Mailing", "?p=".$_GET['p']."&a=enviarMailing&mailing=".$s->getId());
	   
	   $iTLP->enterRepeticao()->condicao("condicaoVisualizar", $s->tipo == 1);
	 
}

$iTLP->trocar("linkVoltar.Mailing", "?p=".$_GET['p']."&a=mailings");

$botoes = $iTLP->cutParte('botoes');

$includePagina = $iTLP->concluir();

?>