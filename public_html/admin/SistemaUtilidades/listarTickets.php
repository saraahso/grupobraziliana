<?php

importar("Utilidades.Tickets.Lista.ListaTickets");

$tituloPagina = 'Utilidades > Tickets';

$iTLT = new IFAdmin(new Arquivos(Sistema::$adminLayoutCaminhoDiretorio."/SistemaUtilidades/listarTickets.html"));

$iTLT->trocar("linkDeletar.Ticket", "?p=".$_GET['p']."&a=".$_GET['a']."&");
$iTLT->trocar("linkBuscar.Ticket", "?p=".$_GET['p']."&");

if(!empty($_GET['deletar'])){
	
	$lT = new ListaTickets;
	$lT->condicoes('', $_GET['deletar'], ListaTickets::ID);
	
	if($lT->getTotal() > 0){
		$lT->deletar($lT->listar());
		$javaScript .= Aviso::criar("Ticket deletado com sucesso!");
	}
	
}

$lT = new ListaTickets;
$iTLT->createRepeticao("repetir->Tickets");

if(!empty($_GET['busca']))
     $lT->condicoes('', "%".$_GET['busca']."%", 'empresa', 'LIKE');

$iTLT->condicao("condicaoBusca", !empty($_SESSION['nivel']));

$iTLT->trocar("linkCadastrar.Ticket", "?p=".$_GET['p']."&a=cadastrarTicket");

$aP = array(1 => array('campo' => ListaTickets::STATUS, 'valor' => Ticket::STATUS_ABERTO));
$lT->condicoes($aP);
while($t = $lT->listar("DESC", ListaTickets::DATAHORA_ALTERACAO)){
	  
	   $iTLT->repetir();
	   
	   $iTLT->enterRepeticao()->condicao("condicaoRemover", !empty($_SESSION['nivel']));
	   
	   $bgColor = $lT->getParametros()%2 == 0 ? '#FFFFFF' : '#EAEAEA';
	   $iTLT->enterRepeticao()->trocar("bgColorEmpresa", $bgColor);
	   
	   $iTLT->enterRepeticao()->trocar("id.Ticket", $t->getId());
	   $iTLT->enterRepeticao()->trocar("cliente.Ticket", $t->getCliente()->nome);
	   $iTLT->enterRepeticao()->trocar("titulo.Ticket", $t->titulo);
	   $iTLT->enterRepeticao()->trocar("data.Ticket", $t->getPostagens()->listar("DESC", ListaTicketPosts::DATAHORA)->getDataHora()->mostrar("H:i d/m/Y"));
	   $iTLT->enterRepeticao()->trocar("status.Ticket", Ticket::__GetStatus($t->getStatus()));
	   
	   $iTLT->enterRepeticao()->trocar("linkVisualizar.Ticket", "?p=".$_GET['p']."&a=listarTickets&ticket=".$t->getId());
	   $iTLT->enterRepeticao()->trocar("linkAlterar.Ticket", "?p=".$_GET['p']."&a=alterarTicket&ticket=".$t->getId());
	   
	   $iTLT->enterRepeticao()->condicao("condicaoVisualizar", $t->tipo == 1);
	 
}

$botoes = $iTLT->cutParte('botoes');

$includePagina = $iTLT->concluir();

?>