<?php

importar("LojaVirtual.Pedidos.Lista.ListaPedidos");
importar("TemTudoAqui.Usuarios.Lista.ListaPessoas");

$tituloPagina = 'Pedidos > Pedidos > Alterar';

$iTAPE = new IFAdmin(new Arquivos(Sistema::$adminLayoutCaminhoDiretorio."/SistemaPedidos/imprimirPedido.html"));

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
	$iTAPE->trocar('moeda', 'R$');
	
	$iTAPE->createRepeticao("repetir->Itens.Pedido");
	while($i = $p->getItem()->listar()){
			
			$iTAPE->repetir();
			
			$iTAPE->enterRepeticao()->trocar('id.Item.Pedido', 		$i->getId());
			$iTAPE->enterRepeticao()->trocar('nome.Item.Pedido', 	$i);
			if($i->unidade > 0){
				$iTAPE->enterRepeticao()->trocar('unidade.Item.Pedido', 	$i->unidade);
				$iTAPE->enterRepeticao()->trocar('tipoUnidade.Item.Pedido', $i->tipoUnidade);
			}
			
			$iTAPE->enterRepeticao()->trocar('descricao.Item.Pedido', 	$i->descricao);
			$iTAPE->enterRepeticao()->trocar('valor.Item.Pedido', 		$i->valor->moeda());
			$iTAPE->enterRepeticao()->trocar('quantidade.Item.Pedido', 	$i->quantidade);
			$iTAPE->enterRepeticao()->trocar('subTotal.Item.Pedido', 	$i->getSubTotal()->moeda());
			
			if($img = $i->getImagens()->listar()){
				$iTAPE->enterRepeticao()->trocar('imagem.Item.Pedido', 	$img->getImage()->showHTML(120, 120));
				$iTAPE->enterRepeticao()->trocar('link.Imagem.Produto', Sistema::$caminhoURL.'lib.conf/abrirArquivo.php?caminho='.$m->criptografar(Sistema::$caminhoURL.Sistema::$caminhoDataProdutos.$img->getImage()->nome.'.'.$img->getImage()->extensao)."&w=600&h=480");
			}
			
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
	$iTAPE->trocar('cidade.Endereco.Pedido', 		$p->getEndereco()->cidade);
	$iTAPE->trocar('estado.Endereco.Pedido', 		$p->getEndereco()->estado);
	$iTAPE->trocar('cep.Endereco.Pedido', 			$p->getEndereco()->getCep());

	//
	
	$pes = $p->getCliente();
		
	$iTAPE->trocar('nome.Cliente.Pedido', 				$pes->nome);
	$iTAPE->trocar('email.Cliente.Pedido', 				$pes->getEmail()->listar()->email);
	$iTAPE->trocar('dataNasc.Cliente.Pedido', 			$pes->getDataNasc()->mostrar());
	
	$end = $pes->getEndereco()->listar();
	$iTAPE->trocar('endereco.Cliente.Pedido', 			$end->logradouro);
	$iTAPE->trocar('numero.Cliente.Pedido', 			$end->numero);
	$iTAPE->trocar('cep.Cliente.Pedido', 				$end->getCep());
	$iTAPE->trocar('bairro.Cliente.Pedido', 			$end->bairro);
	$iTAPE->trocar('cidade.Cliente.Pedido', 			$end->cidade);
	$iTAPE->trocar('estado.Cliente.Pedido', 			$end->estado);
	
	$tel = $pes->getTelefone()->listar();
	$iTAPE->trocar('telefone.Telefone.Cliente.Pedido', 	$tel->ddd."-".$tel->telefone);
	
	$cel = $pes->getTelefone()->listar();
	if($cel)
		$iTAPE->trocar('celular.Telefone.Cliente.Pedido', 	$cel->ddd."-".$cel->telefone);

	$iTAPE->trocar('rg.Cliente.Pedido', 				$pes->rg);	
	$iTAPE->trocar('cpf.Cliente.Pedido', 				$pes->cpf);	
	$iTAPE->trocar('cnpj.Cliente.Pedido', 				$pes->cnpj);	
	
}

$iTAPE->mostrar();

exit;

?>