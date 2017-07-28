<?php

importar("Utilidades.Tickets.Lista.ListaTickets");

$tituloPagina = 'Utilidades > Tickets > Alterar';

$iAT = new IFAdmin(new Arquivos(Sistema::$adminLayoutCaminhoDiretorio."/SistemaUtilidades/ticket.html"));

$iAT->condicao('condicao->alterar.Ticket', false);

if(!empty($_POST)){
	
	$erro = '';	

	if(empty($erro)){
		
		$lT				= new ListaTickets;
		$lT->condicoes('', $_GET['ticket'], ListaTickets::ID);
		
		if($lT->getTotal() > 0){

			$ti 			= $lT->listar();
			
			$ti->setNivel($_POST['nivel']);
			$ti->setStatus($_POST['status']);
			
			$lT->alterar($ti);
			
			if(!empty($_POST['texto'])){
			
				$tP = new TicketPost;
				
				$tP->texto		= $_POST['texto'];
				$tP->nome		= Sistema::$nomeEmpresa;
				$tP->setDataHora(new DataHora);
				
				if(!empty($_FILES['arquivo']['name']))
					$tP->setArquivo(Arquivos::__OpenArquivoByTEMP($_FILES['arquivo']));
				
				$ti->addPostagem($tP);
			
			}
			
			$_POST = '';
					
			$javaScript .= Aviso::criar("Ticket salvo com sucesso!");
		
		}
	
	}else{
		
		$javaScript .= Aviso::criar($erro);
		
	}
	
}

$lT = new ListaTickets;
$t = $lT->condicoes('', $_GET['ticket'], ListaTickets::ID)->listar();

$iAT->createRepeticao("repetir->Clientes");
$lP = new ListaPessoas;
while($p = $lP->listar()){
	
	$iAT->repetir();
	
	$iAT->enterRepeticao()->trocar("id.Cliente", 	$p->getId());
	$iAT->enterRepeticao()->trocar("nome.Cliente", 	$p->nome);
	
}

$iAT->trocar("titulo", 		$t->titulo);
$iAT->trocar("cliente", 	$t->getCliente()->getId());
$iAT->trocar("nivel", 		$t->getNivel());
$iAT->trocar("status", 		$t->getStatus());
$iAT->trocar("satisfacao",	Ticket::__GetSatisfacao($t->getSatisfacao()));

$iAT->trocar("linkVoltar", "?p=".$_GET['p']."&a=listarTickets");

$iAT->createRepeticao("repetir->TicketPosts");
while($tP = $t->getPostagens()->listar("DESC", ListaTicketPosts::DATAHORA)){
	
	$iAT->repetir();
	
	$iAT->enterRepeticao()->trocar("texto.TicketPost", $tP->texto);
	$iAT->enterRepeticao()->trocar("data.TicketPost", $tP->getDataHora()->mostrar("H:i d/m/Y"));
	$iAT->enterRepeticao()->trocar("url.Arquivo.TicketPost", Sistema::$caminhoURL.Sistema::$caminhoDataTickets.$tP->getArquivo()->nome.".".$tP->getArquivo()->extensao);
	
}

$iAT->createJavaScript();
$javaScript .= $iAT->javaScript->concluir();

$includePagina = $iAT->concluir();

?>