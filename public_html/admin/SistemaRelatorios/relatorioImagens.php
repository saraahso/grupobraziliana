<?php

importar("LojaVirtual.Pedidos.Lista.ListaPedidos");
importar("Utils.Dados.Arrays");

if(!empty($_POST['dataInicial']) || !empty($_POST['dataFinal'])){
		
	$iGR = new IFAdmin(new Arquivos(Sistema::$adminLayoutCaminhoDiretorio."/SistemaRelatorios/relatorioImagem.html"));
	
	$dTI = new DataHora($_POST['dataInicial']);
	$dTF = new DataHora($_POST['dataFinal']);
	
	$con 	= BDConexao::__Abrir();
	$sqlI	= "SELECT COUNT(i2.id) FROM tta_imagens i2 WHERE i2.idsessao = p.id AND i2.sessao = 'produtos'";
	$sql	= "SELECT p.id, p.nome, (sqlI) as imagens FROM tta_imagens i INNER JOIN tta_produtos p ON p.id = i.idsessao WHERE i.sessao = 'produtos'";
	$sqlT	= "SELECT COUNT(i.id) as total FROM tta_imagens i WHERE i.sessao = 'produtos'";
	
	if(!empty($_POST['dataInicial'])){
		$sqlI .= " AND i2.datacadastro >= '".$dTI->mostrar("Ymd")."'";
		$sql 	.= " AND i.datacadastro >= '".$dTI->mostrar("Ymd")."'";
		$sqlT .= " AND i.datacadastro >= '".$dTI->mostrar("Ymd")."'";
	}
	if(!empty($_POST['dataFinal'])){
		$sqlI .= " AND i2.datacadastro <= '".$dTF->mostrar("Ymd")."'";
		$sql 	.= " AND i.datacadastro <= '".$dTF->mostrar("Ymd")."'";
		$sqlT .= " AND i.datacadastro <= '".$dTF->mostrar("Ymd")."'";
	}
	
	$sql .=  " GROUP BY p.id";
	$con->executar($sqlT);
	$totalI = $con->getRegistro()['total'];
	$sql = str_replace("sqlI", $sqlI, $sql);
	$con->executar($sql);
	
	function repeatTemplateByArray($con){
		
		global $iGR;
		
		while($p = $con->getRegistro()){
			
			$iGR->repetir('repetir->Produtos');
			$iGR->enterRepeticao()->trocar('codigo.Produto', $p['id']);
			$iGR->enterRepeticao()->trocar('nome.Produto', $p['nome']);
			$iGR->enterRepeticao()->trocar('imagens.Produto', $p['imagens']);			
			
		}
		
	}
	
	$iGR->createRepeticao('repetir->Produtos');
	repeatTemplateByArray($con);
	
	$iGR->trocar('total.Produto', $con->getTotal());
	$iGR->trocar('total.Imagens', $totalI);
	
	$pronto = $iGR->concluir();
	echo $pronto;exit;	
	
}

$tituloPagina = 'Relatórios > Imagens';

$iRel = new IFAdmin(new Arquivos(Sistema::$adminLayoutCaminhoDiretorio."/SistemaRelatorios/imagens.html"));

$dT = new DataHora;
$iRel->trocar('data', $dT->mostrar());

$iRel->createJavaScript();
$javaScript .= $iRel->javaScript->concluir();

$includePagina = $iRel->concluir();

?>