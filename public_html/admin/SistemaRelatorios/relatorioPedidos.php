<?php

importar("LojaVirtual.Pedidos.Lista.ListaPedidos");
importar("Utils.Dados.Arrays");

if(!empty($_POST)){
		
	$iGR = new IFAdmin(new Arquivos(Sistema::$adminLayoutCaminhoDiretorio."/SistemaRelatorios/relatorioPedido.html"));
	
	$dTI = new DataHora($_POST['dataInicial']);
	$dTF = new DataHora($_POST['dataFinal']);
	
	$lP = new ListaPedidos;
	if($_POST['statusPedido'] != 's')
		$aR[count($aR)+1] = array('campo' => ListaPedidos::STATUS, 'valor' => $_POST['statusPedido']);
		
	if(!empty($_POST['dataInicial']) && !empty($_POST['dataFinal'])){
		$aR[count($aR)+1] = array('campo' => ListaPedidos::DATA, 'valor' => $dTI->mostrar("Ymd")."0000", 'operador' => '>=');
		$aR[count($aR)+1] = array('campo' => ListaPedidos::DATA, 'valor' => $dTF->mostrar("Ymd")."2359", 'operador' => '<=');
	}
	
	if($_SESSION['nivel'] == 3){
		$aR[count($aR)+1] = array('campo' => ListaPedidos::VENDEDOR, 'valor' => $_SESSION['idUsuario']);		
	}
	
	$lP->condicoes($aR);
	
	
	function repeatTemplateByArray($rs){
		
		global $iGR;
		
		while($p = $rs->listar("ASC", ListaPedidos::DATA)){
			
			$iGR->repetir('repetir->Pedidos');
			$iGR->enterRepeticao()->trocar('id.Pedido', $p->getId());
			$iGR->enterRepeticao()->trocar('tipoPagamento.Pedido', $p->getTipoPagamento());
			$iGR->enterRepeticao()->trocar('status.Pedido', $p->getStatus());
			
			$iGR->enterRepeticao()->createRepeticao('repetir->Itens.Pedido');
			while($pI = $p->getItem()->listar()){
				$iGR->enterRepeticao()->repetir();
				$iGR->enterRepeticao()->enterRepeticao()->trocar('nome.Item.Pedido', $pI);
				$iGR->enterRepeticao()->enterRepeticao()->trocar('quantidade.Item.Pedido', $pI->quantidade);
				$iGR->enterRepeticao()->enterRepeticao()->trocar('valor.Item.Pedido', $pI->valor->moeda());
			}
			
			$iGR->enterRepeticao()->trocar('valor.Pedido', $p->getValor()->moeda());
			$iGR->enterRepeticao()->trocar('valor.Endereco.Pedido', $p->getEndereco()->getValor()->moeda());
			$iGR->enterRepeticao()->trocar('tipo.Endereco.Pedido', PedidoEnderecoEntrega::GetNameType($p->getEndereco()->tipo));
			$iGR->enterRepeticao()->trocar('total', Numero::__CreateNumero($p->getValor()->formatar()+$p->getEndereco()->getValor()->formatar())->moeda());
			
			$iGR->enterRepeticao()->trocar('observacoes.Pedido', $p->observacoes);
			
			$iGR->enterRepeticao()->trocar('logradouro.Endereco.Pedido', $p->getEndereco()->logradouro);
			$iGR->enterRepeticao()->trocar('numero.Endereco.Pedido', $p->getEndereco()->numero);
			$iGR->enterRepeticao()->trocar('complemento.Endereco.Pedido', $p->getEndereco()->complemento);
			$iGR->enterRepeticao()->trocar('bairro.Endereco.Pedido', $p->getEndereco()->bairro);
			$iGR->enterRepeticao()->trocar('cidade.Endereco.Pedido', $p->getEndereco()->cidade);
			$iGR->enterRepeticao()->trocar('estado.Endereco.Pedido', $p->getEndereco()->estado);
			$iGR->enterRepeticao()->trocar('cep.Endereco.Pedido', $p->getEndereco()->getCep());
			
		}
		
	}
	
	$iGR->createRepeticao('repetir->Pedidos');
	repeatTemplateByArray($lP);
	
	$pronto = $iGR->concluir();
	echo $pronto;exit;	
	
}

$tituloPagina = 'Relatórios > Pedidos';

$iRel = new IFAdmin(new Arquivos(Sistema::$adminLayoutCaminhoDiretorio."/SistemaRelatorios/pedidos.html"));

$dT = new DataHora;
$iRel->trocar('data', $dT->mostrar());

$iRel->createJavaScript();
$javaScript .= $iRel->javaScript->concluir();

$includePagina = $iRel->concluir();

?>