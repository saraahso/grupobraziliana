<?php

importar("LojaVirtual.Produtos.Lista.ListaProdutos");

$tituloPagina = 'Produtos > Produtos';

$iLPC = new IFAdmin(new Arquivos(Sistema::$adminLayoutCaminhoDiretorio."/SistemaProdutos/listarProdutos.html"));

$iLPC->trocar("linkDeletar.Produto", "?p=".$_GET['p']."&a=".$_GET['a']."&");
$iLPC->trocar("linkBuscar.Produto", "?p=".$_GET['p']."&");

if(!empty($_GET['deletar'])){
	
	$lP = new ListaProdutos;
	$lP->condicoes('', $_GET['deletar'], ListaProdutos::ID);
	
	if($lP->getTotal() > 0){
		
		try{
			
			$p = $lP->listar();
			//$p->removido = true;
			$lP->deletar($p);
			
			$javaScript .= Aviso::criar("Produto removido com sucesso!");
		}catch(Exception $e){
			
			$javaScript .= Aviso::criar($e->getMessage());	
			
		}
	
	
	}
	
}

$lP = new ListaProdutos;
$aRP[count($aRP)+1] = array('campo' => ListaProdutos::PRODUTOPAI);
$aRP[count($aRP)+1] = array('campo' => ListaProdutos::REMOVIDO, 'valor' => ListaProdutos::VALOR_DISPONIVEL_FALSE);

$iLPC->createRepeticao("repetir->Produtos");

if(!empty($_GET['busca'])){
	$aRP[count($aRP)+1] = array('campo' => ListaProdutos::NOME, 'valor' => "%".addslashes($_GET['busca'])."%", 'operador' => 'LIKE');
	$aRP[count($aRP)+1] = array('campo' => ListaProdutos::CODIGO, 'valor' => $_GET['busca'], 'operador' => '=', 'OR' => true);
}

$lP->condicoes($aRP);

$iLPC->condicao("condicaoBusca", !empty($_SESSION['nivel']));

$iLPC->trocar("linkCadastrar.Produto", "?p=".$_GET['p']."&a=cadastrarProduto");
$iLPC->trocar('total.ListaProdutos', $lP->getTotal());

$con = BDConexao::__Abrir();

$num = 40;
$primeiro = $_GET['pag']*$num;
$total = $lP->getTotal();
$max = ceil($total/$num);
$lP->setParametros($primeiro)->setParametros($num+$primeiro, 'limite');

while($p = $lP->listar("DESC")){
	  
	  	if(!empty($_POST['desabilitar']) || !empty($_POST['destacar'])){
		
			//Desabilitar
			if($_POST['desabilitar'][$p->getId()])
				$p->disponivel = true;
			else
				$p->disponivel = false;
			//
			
			//Destaque
			if($_POST['destacar'][$p->getId()])
				$p->destaque = true;
			else
				$p->destaque = false;
		   //
			$lP->alterar($p);
		
		}
	   
	   $iLPC->repetir();
	   $iLPC->enterRepeticao()->condicao("condicao->Variacao", false);
	   $iLPC->enterRepeticao()->condicao("condicaoRemover", !empty($_SESSION['nivel']));
	   
	   $bgColor = $lP->getParametros()%2 == 0 ? '#FFFFFF' : '#EAEAEA';
	   $bgColor = $p->disponivel ? '#2da710' : '#ff0000';
	   $bgColor = $p->valorReal->num > 0 ? $bgColor : '#a8a8a8';
	   $iLPC->enterRepeticao()->trocar("bgColorEmpresa", $bgColor);
	   
	   $iLPC->enterRepeticao()->trocar("id.Produto", $p->getId());
	   $iLPC->enterRepeticao()->trocar("codigo.Produto", $p->codigo ? $p->codigo : "id: ".$p->getId());
	   $iLPC->enterRepeticao()->trocar("nome.Produto", $p->nome);
	   $iLPC->enterRepeticao()->trocar("linkVisualizar.Produto", "?p=".$_GET['p']."&a=listarProdutos&produto=".$p->getId());
	   $iLPC->enterRepeticao()->trocar("linkAlterar.Produto", "?p=".$_GET['p']."&a=alterarProduto&produto=".$p->getId()."&pag=".$_GET['pag']."&busca=".$_GET['busca']);
	   $iLPC->enterRepeticao()->trocar("disponivel.Produto", $p->disponivel ? 'checked' : '');
	   $iLPC->enterRepeticao()->trocar("destaque.Produto", $p->destaque ? 'checked' : '');
	   $iLPC->enterRepeticao()->trocar("bg.Disponivel.Produto", !$p->disponivel ? '#FF0000' : '');
	   
	   $iLPC->enterRepeticao()->condicao('condicao->Imagens', $p->getImagens()->getTotal() > 0);
	   
	   $iLPC->enterRepeticao()->condicao("condicaoVisualizar", $p->tipo == 1);
	   
	   if(empty($_GET['busca'])){
		   
		   while($p2 = $p->getInfos()->listar()){
				
				if(!empty($_POST['desabilitar']) || !empty($_POST['destacar'])){
			
					//Desabilitar
					if($_POST['desabilitar'][$p2->getId()])
						$p2->disponivel = true;
					else
						$p2->disponivel = false;
					//
					
					//Destaque
					if($_POST['destacar'][$p->getId()])
						$p2->destaque = true;
					else
						$p2->destaque = false;
				   //
					$lP->alterar($p2);
				
				}
			   
			   $iLPC->repetir();
			   $iLPC->enterRepeticao()->condicao("condicao->Variacao", true);
			   $iLPC->enterRepeticao()->condicao("condicaoRemover", !empty($_SESSION['nivel']));
			   
			   $iLPC->enterRepeticao()->trocar("bgColorEmpresa", '#DBFFB7');
			   
			   $iLPC->enterRepeticao()->trocar("id.Produto", $p2->getId());
			   $iLPC->enterRepeticao()->trocar("codigo.Produto", $p2->codigo ? $p2->codigo : "id: ".$p2->getId());
			   $iLPC->enterRepeticao()->trocar("nome.Produto", "Variação: ".$p2->nome);
			   $iLPC->enterRepeticao()->trocar("linkVisualizar.Produto", "?p=".$_GET['p']."&a=listarProdutos&produto=".$p2->getId());
			   $iLPC->enterRepeticao()->trocar("linkAlterar.Produto", "?p=".$_GET['p']."&a=alterarProduto&produto=".$p2->getId()."&pag=".$_GET['pag']."&busca=".$_GET['busca']);
			   $iLPC->enterRepeticao()->trocar("disponivel.Produto", $p2->disponivel ? 'checked' : '');
			   $iLPC->enterRepeticao()->trocar("destaque.Produto", $p2->destaque ? 'checked' : '');
			   $iLPC->enterRepeticao()->trocar("bg.Disponivel.Produto", !$p2->disponivel ? '#FF0000' : '');
			   
			   $iLPC->enterRepeticao()->condicao('condicao->Imagens', $p2->getImagens()->getTotal() > 0);
			   
			   $iLPC->enterRepeticao()->condicao("condicaoVisualizar", $p2->tipo == 1);
				   
		   }
	   
	   }
	 
}

$iLPC->createRepeticao("repetir->Paginacao");
for($i = 0; $i < $max; $i++){

	$iLPC->repetir();
	$iLPC->enterRepeticao()->trocar("numero.Paginacao", $i+1);
	$iLPC->enterRepeticao()->trocar("linkVisualizar.Paginacao", Sistema::$adminCaminhoURL."?p=SistemaProdutos&a=listarProdutos&pag=".$i."&busca=".$_GET['busca']);
	$iLPC->enterRepeticao()->condicao("condicao->atual.Paginacao", !($i == $_GET['pag']));
	
}

$botoes = $iLPC->cutParte('botoes');

$includePagina = $iLPC->concluir();

?>