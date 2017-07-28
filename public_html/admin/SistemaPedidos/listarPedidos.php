<?php

importar("LojaVirtual.Pedidos.Lista.ListaPedidos");

$tituloPagina = 'Pedidos > Pedidos';

$iTLPE = new IFAdmin(new Arquivos(Sistema::$adminLayoutCaminhoDiretorio."/SistemaPedidos/listarPedidos.html"));

$iTLPE->trocar("linkDeletar.Pedido", "?p=".$_GET['p']."&a=".$_GET['a']."&");
$iTLPE->trocar("linkBuscar.Pedido", "?p=".$_GET['p']."&");

if(!empty($_GET['deletar'])){
	
	$lP = new ListaPedidos;
	$lP->condicoes('', $_GET['deletar'], ListaPedidos::ID);
	
	if($lP->getTotal() > 0){
		
		try{
			$ped = $lP->listar();
			$ped->setStatus(PedidoStatus::CANCELADO);
			
			if($ped->estoque == 1){
			
				$ped->estoque = 0;
				
				$lPR = new ListaProdutos;
				
				while($pI = $ped->getItem()->listar()){
					
					$lPR->condicoes('', $pI->getId(), ListaProdutos::ID);
					if($lPR->getTotal() > 0){
						$pR = $lPR->listar();
						$pR->estoque = $pR->estoque+$pI->quantidade;
						$lPR->alterar($pR);
					}
					
				}
				
			}
			
			$lP->alterar($ped);
			$ped->sendEmail('Status de Pedido alterado');
			$javaScript .= Aviso::criar("Pedido cancelado com sucesso!");
		}catch(Exception $e){
			
			$javaScript .= Aviso::criar($e->getMessage());	
			
		}
	
	
	}
	
}

$lP = new ListaPedidos;
if(!empty($_GET['status']) || $_GET['status'] == '0')
	$status = $_GET['status'];
else
	$status = PedidoStatus::ENTREGA;

$aP[1] = array('campo' => ListaPedidos::STATUS, 'valor' => $status);
$lP->condicoes($aP);
$iTLPE->createRepeticao("repetir->Pedidos");

if(!empty($_GET['busca'])){
    $iTLPE->trocar('busca', $_GET['busca']);
	$lP->condicoes('', "", '', '', "SELECT pe.* FROM ".Sistema::$BDPrefixo."pedidos pe, ".Sistema::$BDPrefixo."pedido_itens pei, ".Sistema::$BDPrefixo."produtos p, ".Sistema::$BDPrefixo."pessoas c, ".Sistema::$BDPrefixo."enderecos e WHERE pe.status = '".$status."' AND ((p.codigo = '".$_GET['busca']."' AND pei.id = p.id AND pe.id = pei.idpedido) OR ((c.nome LIKE '%".$_GET['busca']."%' OR c.email LIKE '%".$_GET['busca']."%') AND pe.sessao = c.id) OR ((e.estado LIKE '%".$_GET['busca']."%' OR e.cidade LIKE '%".$_GET['busca']."%') AND pe.sessao = e.ligacao))".($_SESSION['nivel'] == 3 ? " AND pe.vendedor = '".$_SESSION['idUsuario']."'" : "")." GROUP BY pe.id");
}elseif($_SESSION['nivel'] == 3){
	$lP->condicoes(array(1 => array("campo" => ListaPessoas::VENDEDOR, "valor" => $_SESSION['idUsuario'])));
}

$iTLPE->condicao("condicaoBusca", !empty($_SESSION['nivel']));
$iTLPE->trocar('statusPedido', $status);

$iTLPE->trocar("linkCadastrar.Pedido", "?p=".$_GET['p']."&a=cadastrarPedido");


while($p = $lP->listar("DESC", ListaPedidos::DATA)){
	  
	   $iTLPE->repetir();
	   
	   $iTLPE->enterRepeticao()->condicao("condicaoRemover", !empty($_SESSION['nivel']));
	   
	   $bgColor = $lP->getParametros()%2 == 0 ? '#FFFFFF' : '#EAEAEA';
	   $iTLPE->enterRepeticao()->trocar("bgColorEmpresa", $bgColor);
	   
	   $iTLPE->enterRepeticao()->trocar("id.Pedido", $p->getId());
	   $iTLPE->enterRepeticao()->trocar("data.Pedido", $p->getData()->mostrar("H:i  d/m/Y"));
	   $iTLPE->enterRepeticao()->trocar("valor.Pedido", Numero::__CreateNumero($p->getValor()->num+$p->getEndereco()->getValor()->num)->moeda());
	   $iTLPE->enterRepeticao()->trocar("linkVisualizar.Pedido", "?p=".$_GET['p']."&a=listarPedidos&pedido=".$p->getId());
	   $iTLPE->enterRepeticao()->trocar("linkAlterar.Pedido", "?p=".$_GET['p']."&a=alterarPedido&pedido=".$p->getId());
	   
	   $iTLPE->enterRepeticao()->condicao("condicao->CancelarPedido", $p->getStatus()->getStatus() != PedidoStatus::CANCELADO);
	 
}

$botoes = $iTLPE->cutParte('botoes');

$javaScript .= $iTLPE->createJavaScript()->concluir();
$includePagina = $iTLPE->concluir();

?>