<?php

importar("Geral.Lista.ListaImagens");
importar("LojaVirtual.Pedidos.Lista.ListaPedidos");
importar("TemTudoAqui.Usuarios.Lista.ListaPessoas");

$tituloPagina = 'Pedidos > Pedidos > Alterar';

$con = BDConexao::__Abrir();
$con->executar("SELECT * FROM ".Sistema::$BDPrefixo."pagamentos");
$rsP = $con->getRegistro();

$con->executar("SELECT * FROM ".Sistema::$BDPrefixo."frete");
$rsF = $con->getRegistro();

$iTAPE = new IFAdmin(new Arquivos(Sistema::$adminLayoutCaminhoDiretorio."/SistemaPedidos/pedido.html"));

$lI = new ListaIdiomas;

if(!empty($_POST)){
	
	$erro = '';	

	if(empty($erro)){
		
		$lP = new ListaPedidos;
		$lP->condicoes('', $_GET['pedido'], ListaPedidos::ID);
		$p = $lP->listar();
		
		if(empty($_GET['tipo'])){
			
			$p->setStatus($_POST['statusPedido']);
			$status = $_POST['statusPedido'] ? $_POST['statusPedido'] : 0;
			if($status == PedidoStatus::ESPERA && $p->estoque == 0){
							
				$p->estoque = 1;
				
				$lPR = new ListaProdutos;
				
				while($pI = $p->getItem()->listar()){
					
					$lPR->condicoes('', $pI->getId(), ListaProdutos::ID);
					if($lPR->getTotal() > 0){
						$pR = $lPR->listar();
						$pR->estoque = $pR->estoque-$pI->quantidade;	
						$lPR->alterar($pR);
					}
					
				}
				
			}
		
			if($status == PedidoStatus::ENTREGA && $p->estoque == 0){
				
				$p->estoque = 1;
				
				$lPR = new ListaProdutos;
				
				while($pI = $p->getItem()->listar()){
					
					$lPR->condicoes('', $pI->getId(), ListaProdutos::ID);
					if($lPR->getTotal() > 0){
						$pR = $lPR->listar();
						$pR->estoque = $pR->estoque-$pI->quantidade;	
						$lPR->alterar($pR);
					}
					
				}
				
			}
			
			if($p->estoque == 1 && $status == PedidoStatus::CANCELADO){
				
				$p->estoque = 0;
				
				$lPR = new ListaProdutos;
				
				while($pI = $p->getItem()->listar()){
					
					$lPR->condicoes('', $pI->getId(), ListaProdutos::ID);
					if($lPR->getTotal() > 0){
						$pR = $lPR->listar();
						$pR->estoque = $pR->estoque+$pI->quantidade;
						$lPR->alterar($pR);
					}
					
				}
				
			}
			
			if($_POST['selecionado']){
				foreach($_POST['selecionado'] as $v){
					
					$lPI = new ListaPedidoItens;
					$arPI[1] = array('campo' => ListaPedidoItens::ID, 'valor' => $v);
					$arPI[2] = array('campo' => ListaPedidoItens::IDSESSAO, 'valor' => $p->getId());
					$lPI->condicoes($arPI);
					
					if($lPI->getTotal() > 0)
						$lPI->deletar($lPI->listar(), $p);
					
				}
				
				try{
					$p->calcularFrete();
				}catch(Exception $e){}
				
				$p->calcular();
				
			}
			
			while($pI = $p->getItem()->listar()){
				$pI->quantidade = $_POST['quantidade'][$pI->getId()];
				$pI->valorVenda = $_POST['valor'][$pI->getId()];
				$p->addItem($pI);
			}
			
			if(!empty($_POST['CEP'])){
				
				$end = $p->getEndereco();
				
				$end->setCep($_POST['CEP']);
				$lE = new ListaEstados;
				$lE->condicoes('', strtoupper($_POST['estado']), ListaEstados::UF);
				if($lE->getTotal() > 0)
					$end->setEstado($lE->listar());
				else{
					$end->getEstado()->uf = strtoupper($_POST['estado']);
					$end->getEstado()->setPais(new Pais(1));					
				}
				
				$lC = new ListaCidades;
				$lC->condicoes('', $_POST['cidade'], ListaCidades::NOME);
				if($lC->getTotal() > 0)
					$end->setCidade($lC->listar());
				else{
					$end->getCidade()->nome = $_POST['cidade'];
					$end->getCidade()->setEstado($end->getEstado());
				}
				$end->logradouro = $_POST['Logradouro'];
				$end->numero = $_POST['Numero'];
				$end->complemento = $_POST['Complemento'];
				$end->bairro = $_POST['Bairro'];
				$end->loadCep();
				$p->setEndereco($end);
				
				try{
					$p->getEndereco()->setValor(0);
					$p->calcularFrete();
				}catch(Exception $e){}
			
			}
			
			$lP->alterar($p);
			
			$javaScript .= Aviso::criar("Pedido salvo com sucesso!");			
		
		}elseif($_GET['tipo'] == 'frete'){
			
			try{
			
				$p->getEndereco()->tipo = $_POST['tipoFrete'];
				if(empty($_POST['frete'])){
					$p->getEndereco()->setValor(0);
					$p->calcularFrete();
				}else
					$p->getEndereco()->setValor($_POST['frete']);
				
				$lP->alterar($p);
			
			}catch(Exception $e){}
			
			
			$javaScript .= Aviso::criar("Pedido salvo com sucesso!", "document.location.href = '?p=SistemaPedidos&a=alterarPedido&pedido=".$p->getId()."';");
			
		}elseif($_GET['tipo'] == 'enviarcobranca'){
			
			if($p->getStatus()->getStatus() != PedidoStatus::COBRANCA)
				$javaScript .= Aviso::criar("Status do pedido deve estar em <strong>".PedidoStatus::__NomeStatus(PedidoStatus::COBRANCA)."</strong> para enviar o e-mail de cobrança!", "document.location.href = '?p=SistemaPedidos&a=alterarPedido&pedido=".$p->getId()."';");
			else{
				$p->sendEmail('Aguardando pagamento de Pedido');
				$javaScript .= Aviso::criar("Cobrança enviada com sucesso!", "document.location.href = '?p=SistemaPedidos&a=alterarPedido&pedido=".$p->getId()."';");
			}		
			
		}   
	
	}else{
		
		$javaScript .= Aviso::criar($erro);
		
	}
	
}

$lP = new ListaPedidos;
$lP->condicoes('', $_GET['pedido'], ListaPedidos::ID);

$m = new MD5;

if($lP->getTotal() > 0){
	
	$p = $lP->listar();
	
	//
	$iTAPE->trocar('id.Pedido', $p->getId());
	$iTAPE->trocar('tipoPagamento.Pedido', $p->getTipoPagamento());
	$iTAPE->trocar('status.Pedido', $p->getStatus()->getStatus());	
	$iTAPE->trocar("data.Pedido", $p->getData()->mostrar("H:i  d/m/Y"));
	$iTAPE->trocar('moeda', 'U$');
	
	$iTAPE->condicao("condicao->AdicionarProduto", $_SESSION['nivel'] < 3);
	
	$iTAPE->createRepeticao("repetir->Itens.Pedido");
	while($i = $p->getItem()->listar()){
			
			$iTAPE->repetir();
			
			$iTAPE->enterRepeticao()->trocar('id.Item.Pedido', 		$i->getId());
			$iTAPE->enterRepeticao()->trocar('codigo.Item.Pedido', 		$i->codigo);
			$iTAPE->enterRepeticao()->trocar('nome.Item.Pedido', 	$i);
			if($i->unidade > 0){
				$iTAPE->enterRepeticao()->trocar('unidade.Item.Pedido', 	$i->unidade);
				$iTAPE->enterRepeticao()->trocar('tipoUnidade.Item.Pedido', $i->tipoUnidade);
			}
			
			$iTAPE->enterRepeticao()->trocar('descricao.Item.Pedido', 	$i->descricao);
			$iTAPE->enterRepeticao()->trocar('valor.Item.Pedido', 		$i->valor->moeda());
			$iTAPE->enterRepeticao()->trocar('quantidade.Item.Pedido', 	$i->quantidade);
			
			if($i->getImagens()->getTotal() > 0){
				if($img = $i->getImagens()->listar()){
					$iTAPE->enterRepeticao()->trocar('imagem.Item.Pedido', 	$img->getImage()->showHTML(120, 120));
				}
			}elseif($i->getProdutoPai() != ''){
				
				$lPR = new ListaProdutos;
				$lPR->condicoes('', $i->getProdutoPai(), ListaProdutos::ID);
				if($lPR->getTotal() > 0){
					$pr = $lPR->listar();
					if($img = $pr->getImagens()->listar()){
						$iTAPE->enterRepeticao()->trocar('imagem.Item.Pedido', 	$img->getImage()->showHTML(120, 120));
					}
				}
				
			}
			
			$iTAPE->enterRepeticao()->trocar('linkAlterar.Item.Pedido', "?p=SistemaProdutos&a=alterarProduto&produto=".$i->getId());
			$iTAPE->enterRepeticao()->trocar('link.Remove.Item.Pedido',	Sistema::$caminhoURL."v1/exec.php?p=removerProdutoPedido&item=".$i->getId());
			
	}
	
	$iTAPE->trocar('total.Pedido', 	$p->getValor()->moeda());
	$iTAPE->condicao('condicao->ExisteFrete', $p->hasFrete() && $p->getItem()->getTotal() > 0);
	$iTAPE->condicao('condicao->ExistePrazo', $p->getEndereco()->prazo > 0 && $p->getItem()->getTotal() > 0);
	$iTAPE->condicao('condicao->ExisteFreteCorreios', $rs['ativocorreio']);		
	$iTAPE->trocar('frete', 		$p->getEndereco()->getValor()->moeda());
	$iTAPE->trocar('tipo.Frete',	$p->getEndereco()->tipo);
	$iTAPE->trocar('total', 		Numero::__CreateNumero($p->getValor()->formatar()+$p->getEndereco()->getValor()->formatar())->moeda());
	
	$iTAPE->trocar('observacoes.Pedido', 			nl2br($p->observacoes));
	$iTAPE->trocar('logradouro.Endereco.Pedido', 	$p->getEndereco()->logradouro);
	$iTAPE->trocar('numero.Endereco.Pedido', 		$p->getEndereco()->numero);
	$iTAPE->trocar('complemento.Endereco.Pedido', 	$p->getEndereco()->complemento);
	$iTAPE->trocar('bairro.Endereco.Pedido', 		$p->getEndereco()->bairro);
	$iTAPE->trocar('cidade.Endereco.Pedido', 		$p->getEndereco()->getCidade()->nome);
	$iTAPE->trocar('estado.Endereco.Pedido', 		$p->getEndereco()->getEstado()->uf);
	$iTAPE->trocar('cep.Endereco.Pedido', 			$p->getEndereco()->getCep());

	//
	
	$pes = $p->getCliente();
		
	$iTAPE->trocar('nome.Cliente.Pedido', 				$pes->nome);
	$iTAPE->trocar('email.Cliente.Pedido', 				$pes->getEmail()->listar()->email);
	if($pes->getDataNasc())
		$iTAPE->trocar('dataNasc.Cliente.Pedido', 		$pes->getDataNasc()->mostrar());
	
	$end = $pes->getEndereco()->listar();
	$iTAPE->trocar('endereco.Cliente.Pedido', 			$end->logradouro);
	$iTAPE->trocar('numero.Cliente.Pedido', 			$end->numero);
	if($end)
		$iTAPE->trocar('cep.Cliente.Pedido', 			$end->getCep());
	$iTAPE->trocar('bairro.Cliente.Pedido', 			$end->bairro);
	$iTAPE->trocar('cidade.Cliente.Pedido', 			$end->getCidade()->nome);
	$iTAPE->trocar('estado.Cliente.Pedido', 			$end->getEstado()->uf);
	
	$tel = $pes->getTelefone()->listar();
	$iTAPE->trocar('telefone.Telefone.Cliente.Pedido', 	$tel->ddd."-".$tel->telefone);
	
	$cel = $pes->getTelefone()->listar();
	if($cel)
		$iTAPE->trocar('celular.Telefone.Cliente.Pedido', 	$cel->ddd."-".$cel->telefone);

	$iTAPE->trocar('rg.Cliente.Pedido', 				$pes->rg);	
	$iTAPE->trocar('cpf.Cliente.Pedido', 				$pes->cpf);	
	$iTAPE->trocar('cnpj.Cliente.Pedido', 				$pes->cnpj);	
	
}

$iTAPE->condicao('condicao->alterar.Pedido', false);

$iTAPE->trocar("linkVoltar", "?p=".$_GET['p']."&a=listarPedidos");
$iTAPE->trocar("linkImprimir", "?p=".$_GET['p']."&a=imprimirPedido&pedido=".$p->getId());


$iTAPE->createJavaScript();
$javaScript .= $iTAPE->javaScript->concluir();

$includePagina = $iTAPE->concluir();

?>