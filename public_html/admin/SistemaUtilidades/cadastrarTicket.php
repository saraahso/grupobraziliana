<?php

importar("Utilidades.Tickets.Lista.ListaTickets");

$tituloPagina = 'Utilidades > Tickets > Cadastrar';

$iCT = new IFAdmin(new Arquivos(Sistema::$adminLayoutCaminhoDiretorio."/SistemaUtilidades/ticket.html"));

if(!empty($_POST)){
	
	$erro = '';

    if(empty($_POST['titulo']))
	    $erro = "<b>Titulo</b> não preenchido!<br><br>";

	if(empty($erro)){
	
	    $ti 			= new Ticket;
		
		$ti->titulo 	= $_POST['titulo'];		
		$ti->setNivel($_POST['nivel']);
		$ti->setStatus($_POST['status']);
		
		$lP = new ListaPessoas;
		$lP->condicoes('', $_POST['cliente'], ListaPessoas::ID);
		if($lP->getTotal() > 0)
			$ti->setCliente($lP->listar());
		
		$lT				= new ListaTickets;
		$lT->inserir($ti);
		
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
	
	}else{
		
		$javaScript .= Aviso::criar($erro);
		
	}
	
}

$iCT->condicao('condicao->alterar.Ticket', true);
$iCT->trocar("linkVoltar", "?p=".$_GET['p']."&a=listarTickets");

$iCT->createRepeticao("repetir->Clientes");
$lP = new ListaPessoas;
while($p = $lP->listar()){
	
	$iCT->repetir();
	
	$iCT->enterRepeticao()->trocar("id.Cliente", 	$p->getId());
	$iCT->enterRepeticao()->trocar("nome.Cliente", 	$p->nome);
	
}

$iCT->trocar("titulo", 	$_POST['titulo']);
$iCT->trocar("cliente", $_POST['cliente']);
$iCT->trocar("nivel", 	$_POST['nivel']);
$iCT->trocar("status", 	$_POST['status']);

$iCT->createRepeticao("repetir->TicketPosts");

$iCT->createJavaScript();
$javaScript .= $iCT->javaScript->concluir();

$includePagina = $iCT->concluir();

?>