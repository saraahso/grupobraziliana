<?php

importar("Utilidades.Publicidades.Mailing.Lista.ListaPacoteMailings");

$tituloPagina = 'Utilidades > Publicidades > Mailings > Pacotes';

$iTPM = new IFAdmin(new Arquivos(Sistema::$adminLayoutCaminhoDiretorio."/SistemaUtilidades/listarPacoteMailings.html"));

$iTPM->trocar("linkDeletar.PacoteMailing", "?p=".$_GET['p']."&a=".$_GET['a']."&");
$iTPM->trocar("linkBuscar.PacoteMailing", "?p=".$_GET['p']."&");

if(!empty($_GET['deletar'])){
	
	$lPC = new ListaPacoteMailings;
	$lPC->condicoes('', $_GET['deletar'], ListaPacoteMailings::ID);
	
	if($lPC->getTotal() > 0){
		
		try{
			$lPC->deletar($lPC->listar());
			$javaScript .= Aviso::criar("Pacote removido com sucesso!");
		}catch(Exception $e){
			
			$javaScript .= Aviso::criar($e->getMessage());	
			
		}
	
	
	}
	
}

$lPC = new ListaPacoteMailings;

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

$iTPM->createRepeticao("repetir->PacoteMailings");

if(!empty($_GET['busca']))
     $lPC->condicoes('', "%".$_GET['busca']."%", 'empresa', 'LIKE');

$iTPM->condicao("condicaoBusca", !empty($_SESSION['nivel']));

$iTPM->trocar("linkCadastrar.PacoteMailing", "?p=".$_GET['p']."&a=cadastrarPacoteMailing");

while($tx = $lPC->listar("ASC", ListaPacoteMailings::TITULO)){
	  
	   $iTPM->repetir();
	   
	   $iTPM->enterRepeticao()->condicao("condicaoRemover", !empty($_SESSION['nivel']));
	   
	   $bgColor = $lPC->getParametros()%2 == 0 ? '#FFFFFF' : '#EAEAEA';
	   $iTPM->enterRepeticao()->trocar("bgColorEmpresa", $bgColor);
	   
	   $iTPM->enterRepeticao()->trocar("id.PacoteMailing", $tx->getId());
	   $iTPM->enterRepeticao()->trocar("titulo.PacoteMailing", $tx->titulo);
	   
	   $iTPM->enterRepeticao()->trocar("linkAlterar.PacoteMailing", "?p=".$_GET['p']."&a=alterarPacoteMailing&pacote=".$tx->getId());
	   
	   $iTPM->enterRepeticao()->condicao("condicaoVisualizar", $tx->tipo == 1);
	 
}

$iTPM->trocar("linkVoltar.PacoteMailing", "?p=".$_GET['p']."&a=mailings");

$botoes = $iTPM->cutParte('botoes');

$includePagina = $iTPM->concluir();

?>