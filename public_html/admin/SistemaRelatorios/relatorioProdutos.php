<?php

importar("LojaVirtual.Produtos.ProdutoBusca");
importar("Utils.Dados.Arrays");

if(!empty($_POST)){
	
	if(count($_POST['categoriasSelecionadas']) > 0){
		
		$cond = array();
		foreach($_POST['categoriasSelecionadas'] as $v){
			if($v == 'all'){
				unset($cond);
				$cond = false;
				break;
			}else
				$cond[count($cond)+1] = array('campo' => ListaProdutoCategorias::ID, 'valor' => $v, 'OR' => true);
		}
		
		function navigateToCategory(ProdutoCategoria $pC){
			
			$array['navegador'] = $pC->getNavegador();
			
			if($pC->getSubCategorias()->getTotal() > 0){
				
				$array['filhos'] = new ArrayObject;
				while($sPC = $pC->getSubCategorias()->listar('ASC', ListaProdutoCategorias::NOME))
					$array['filhos']->append(navigateToCategory($sPC));
				
			}else{
				
				$array['produtos'] = new ArrayObject;
				while($p = $pC->getProdutos()->listar("ASC", ListaProdutos::NOME))
					$array['produtos']->append($p);
					
			}
			
			return $array;
			
		}
		
		if($cond){
			
			$rsT = new ArrayObject;
			$lPC = new ListaProdutoCategorias;
			$lPC->condicoes($cond);
			
			while($pC = $lPC->listar("ASC", ListaProdutoCategorias::NOME))
				$rsT->append(navigateToCategory($pC));
			
		}else{
			
			$rsT = new ArrayObject;
			$pCP = new ProdutoCategoria;
			while($pC = $pCP->getSubCategorias()->listar("ASC", ListaProdutoCategorias::NOME))
				$rsT->append(navigateToCategory($pC));
				
		}
		
		$rs = $rsT;
		//$rs = Arrays::ArrayOrderBy($rsT, 'nome', SORT_ASC);
		//unset($rsT);
		
		$iGR = new IFAdmin(new Arquivos(Sistema::$adminLayoutCaminhoDiretorio."/SistemaRelatorios/relatorioProduto.html"));
		
		function repeatTemplateByArray($rs){
			
			global $iGR;
			
			foreach($rs as $v){
				
				$iGR->repetir('repetir->ProdutoCategorias');
				$iGR->enterRepeticao('repetir->ProdutoCategorias')->trocar('navegador.ProdutoCategoria', $v['navegador']);
				$iGR->enterRepeticao('repetir->ProdutoCategorias')->createRepeticao('repetir->Produtos');
				if(count($v['filhos']) > 0){
					repeatTemplateByArray($v['filhos']);
				}elseif(count($v['produtos']) > 0){
					
					foreach($v['produtos'] as $p){
						
						$iGR->enterRepeticao('repetir->ProdutoCategorias')->repetir('repetir->Produtos');
						
						$iGR->enterRepeticao('repetir->ProdutoCategorias')->enterRepeticao('repetir->Produtos')->trocar('codigo.Produto', $p->codigo);
						$iGR->enterRepeticao('repetir->ProdutoCategorias')->enterRepeticao('repetir->Produtos')->trocar('nome.Produto', 	$p);
						$iGR->enterRepeticao('repetir->ProdutoCategorias')->enterRepeticao('repetir->Produtos')->trocar('estoque.Produto', 	$p->estoque);
						$iGR->enterRepeticao('repetir->ProdutoCategorias')->enterRepeticao('repetir->Produtos')->trocar('valor.Produto', 	$p->valorReal->moeda());
						
						$iGR->enterRepeticao('repetir->ProdutoCategorias')->enterRepeticao('repetir->Produtos')->createRepeticao('repetir->ProdutoInfos.Produto');
						while($pI = $p->getInfos()->listar()){
							
							$iGR->enterRepeticao('repetir->ProdutoCategorias')->enterRepeticao('repetir->Produtos')->repetir('repetir->ProdutoInfos.Produto');
							
							$iGR->enterRepeticao('repetir->ProdutoCategorias')->enterRepeticao('repetir->Produtos')->enterRepeticao('repetir->ProdutoInfos.Produto')->trocar('info.ProdutoInfo.Produto', $pI);
							$iGR->enterRepeticao('repetir->ProdutoCategorias')->enterRepeticao('repetir->Produtos')->enterRepeticao('repetir->ProdutoInfos.Produto')->trocar('estoque.ProdutoInfo.Produto', $pI->estoque);
							$iGR->enterRepeticao('repetir->ProdutoCategorias')->enterRepeticao('repetir->Produtos')->enterRepeticao('repetir->ProdutoInfos.Produto')->trocar('valor.ProdutoInfo.Produto', $pI->valor->moeda());
							
						}
						
					}
					
				}
				
			}
			
		}
		
		$iGR->createRepeticao('repetir->ProdutoCategorias');
		repeatTemplateByArray($rs);
		
		$pronto = $iGR->concluir();
		echo $pronto;exit;
		//require_once($_SERVER['DOCUMENT_ROOT']."/dompdf/dompdf_config.inc.php");
 
		//$dompdf = new DOMPDF();
		//$dompdf->load_html($pronto);
		//$dompdf->set_paper('A4', 'portrait'); //landscape
		//$dompdf->render();
		//$dompdf->stream("relatorio-produtos.pdf");
		
	}
	
}

$tituloPagina = 'Relatórios > Produtos';

$iRel = new IFAdmin(new Arquivos(Sistema::$adminLayoutCaminhoDiretorio."/SistemaRelatorios/produtos.html"));

$iRel->createJavaScript();
$javaScript .= $iRel->javaScript->concluir();

$includePagina = $iRel->concluir();

?>