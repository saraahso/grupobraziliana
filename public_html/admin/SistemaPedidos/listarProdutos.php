<?php

importar("LojaVirtual.Produtos.Lista.ListaProdutos");
importar("LojaVirtual.Pedidos.Lista.ListaPedidos");

$tituloPagina = 'Pedidos > Adicionar Produtos';

$iLPC = new IFAdmin(new Arquivos(Sistema::$adminLayoutCaminhoDiretorio."/SistemaPedidos/listarProdutos.html"));

$iLPC->trocar("linkDeletar.Produto", "?p=".$_GET['p']."&a=".$_GET['a']."&");
$iLPC->trocar("linkBuscar.Produto", "?p=".$_GET['p']."&");

$iLPC->trocar('id.Pedido', $_GET['pedido']);

$lP = new ListaProdutos;

if(!empty($_POST)){
	
	$lPE = new ListaPedidos;
	$lPE->condicoes('', $_GET['pedido'], ListaPedidos::ID);
	if($lPE->getTotal() > 0){
		
		$ped = $lPE->listar();
		
		if($_POST['selecionado']){
			foreach($_POST['selecionado'] as $v){
				
				$lP->condicoes('', $v, ListaProdutos::ID);
				if($lP->getTotal() > 0){
					$ped->addItem(PedidoItem::__ProdutoToPedidoItem($lP->listar()));
				}
				
			}
		}
		
		try{
			$ped->calcularFrete();
			$lPE->alterar($ped);
		}catch(Exception $e){}
		
		header("Location: ?p=SistemaPedidos&a=alterarPedido&pedido=".$_GET['pedido']);
				
	}
	
}

$lP->resetCondicoes();
$aRP[count($aRP)+1] = array('campo' => ListaProdutos::PRODUTOPAI);
$aRP[count($aRP)+1] = array('campo' => ListaProdutos::REMOVIDO, 'valor' => ListaProdutos::VALOR_DISPONIVEL_FALSE);

$iLPC->createRepeticao("repetir->Produtos");

if(!empty($_GET['busca'])){
	$aRP[count($aRP)+1] = array('campo' => ListaProdutos::NOME, 'valor' => "%".$_GET['busca']."%", 'operador' => 'LIKE');
	$aRP[count($aRP)+1] = array('campo' => ListaProdutos::CODIGO, 'valor' => $_GET['busca'], 'operador' => '=', 'OR' => true);
}

$lP->condicoes($aRP);

$iLPC->condicao("condicaoBusca", !empty($_SESSION['nivel']));

$iLPC->trocar('total.ListaProdutos', $lP->getTotal());

$con = BDConexao::__Abrir();

$num = 40;
$primeiro = $_GET['pag']*$num;
$total = $lP->getTotal();
$max = ceil($total/$num);
$lP->setParametros($primeiro)->setParametros($num+$primeiro, 'limite');

while($p = $lP->listar("ASC", ListaProdutos::NOME)){
	   
	   $iLPC->repetir();
	   
	   $iLPC->enterRepeticao()->condicao("condicaoRemover", !empty($_SESSION['nivel']));
	   
	   $bgColor = $lP->getParametros()%2 == 0 ? '#FFFFFF' : '#EAEAEA';
	   $iLPC->enterRepeticao()->trocar("bgColorEmpresa", $bgColor);
	   
	   $iLPC->enterRepeticao()->trocar("id.Produto", $p->getId());
	   $iLPC->enterRepeticao()->trocar("codigo.Produto", $p->codigo ? $p->codigo : "id: ".$p->getId());
	   $iLPC->enterRepeticao()->trocar("nome.Produto", $p->nome.($p->getCor()->getId() ? "<br>Cor: ".$p->getCor()->nome : '').($p->getTamanho()->getId() ? "<br>Tamanho: ".$p->getTamanho()->nome : '').($p->getPedra()->getId() ? "<br>Pedra: ".$p->getPedra()->nome : ''));
	   $iLPC->enterRepeticao()->trocar("linkVisualizar.Produto", "?p=".$_GET['p']."&a=listarProdutos&produto=".$p->getId());
	   $iLPC->enterRepeticao()->trocar("linkAlterar.Produto", "?p=".$_GET['p']."&a=alterarProduto&produto=".$p->getId()."&pag=".$_GET['pag']."&busca=".$_GET['busca']);
	   $iLPC->enterRepeticao()->trocar("disponivel.Produto", $p->disponivel ? 'checked' : '');
	   $iLPC->enterRepeticao()->trocar("destaque.Produto", $p->destaque ? 'checked' : '');
	   $iLPC->enterRepeticao()->trocar("bg.Disponivel.Produto", !$p->disponivel ? '#FF0000' : '');
	   
	   $iLPC->enterRepeticao()->condicao('condicao->Imagens', $p->getImagens()->getTotal() > 0);
	   
	   $iLPC->enterRepeticao()->condicao("condicaoVisualizar", $p->tipo == 1);
	 
	   while($p2 = $p->getInfos()->listar()){
		   
		   $iLPC->repetir();
		   
		   $iLPC->enterRepeticao()->condicao("condicaoRemover", !empty($_SESSION['nivel']));
		   
		   $iLPC->enterRepeticao()->trocar("bgColorEmpresa", '#DBFFB7');
		   
		   $iLPC->enterRepeticao()->trocar("id.Produto", $p2->getId());
		   $iLPC->enterRepeticao()->trocar("codigo.Produto", $p2->codigo ? $p2->codigo : "id: ".$p2->getId());
		   $iLPC->enterRepeticao()->trocar("nome.Produto", "Variação: ".$p2->nome.($p2->getCor()->getId() ? "<br>Cor: ".$p2->getCor()->nome : '').($p2->getTamanho()->getId() ? "<br>Tamanho: ".$p2->getTamanho()->nome : '').($p2->getPedra()->getId() ? "<br>Pedra: ".$p2->getPedra()->nome : ''));
		   $iLPC->enterRepeticao()->trocar("linkVisualizar.Produto", "?p=".$_GET['p']."&a=listarProdutos&produto=".$p2->getId());
		   $iLPC->enterRepeticao()->trocar("linkAlterar.Produto", "?p=".$_GET['p']."&a=alterarProduto&produto=".$p2->getId()."&pag=".$_GET['pag']."&busca=".$_GET['busca']);
		   $iLPC->enterRepeticao()->trocar("disponivel.Produto", $p2->disponivel ? 'checked' : '');
		   $iLPC->enterRepeticao()->trocar("destaque.Produto", $p2->destaque ? 'checked' : '');
		   $iLPC->enterRepeticao()->trocar("bg.Disponivel.Produto", !$p2->disponivel ? '#FF0000' : '');
		   
		   $iLPC->enterRepeticao()->condicao('condicao->Imagens', $p2->getImagens()->getTotal() > 0);
		   
		   $iLPC->enterRepeticao()->condicao("condicaoVisualizar", $p2->tipo == 1);
			   
	   }
	 
}

$iLPC->createRepeticao("repetir->Paginacao");
for($i = 0; $i < $max; $i++){

	$iLPC->repetir();
	$iLPC->enterRepeticao()->trocar("numero.Paginacao", $i+1);
	$iLPC->enterRepeticao()->trocar("linkVisualizar.Paginacao", Sistema::$adminCaminhoURL."?p=SistemaPedidos&a=listarProdutos&pedido=".$_GET['pedido']."&pag=".$i."&busca=".$_GET['busca']);
	$iLPC->enterRepeticao()->condicao("condicao->atual.Paginacao", !($i == $_GET['pag']));
	
}

$iLPC->trocar('linkVoltar', "?p=SistemaPedidos&a=alterarPedido&pedido=".$_GET['pedido']);

$includePagina = $iLPC->concluir();

?>