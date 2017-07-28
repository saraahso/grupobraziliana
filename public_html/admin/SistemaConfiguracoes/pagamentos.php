<?php

importar("Utils.Imagens.Image");

$tituloPagina = 'Configura&ccedil;&otilde;es > Pagamentos';
$con = BDConexao::__Abrir();
if(!empty($_POST)){
	
	$con->executar("SELECT * FROM ".Sistema::$BDPrefixo."pagamentos");
	if($con->getTotal() > 0){
		
		$con->executar("UPDATE ".Sistema::$BDPrefixo."pagamentos SET tiposite = '".$_POST['tipoSite']."', tipopedido = '".$_POST['tipoPedido']."', tipopedidoprodutostodosite = '".$_POST['tipoPedidoProdutosTodoSite']."', ativodesconto = '".$_POST['ativoDesconto']."', codigodesconto = '".$_POST['codigoDesconto']."', porcentagemdesconto = '".$_POST['porcentagemDesconto']."', ativopagseguro = '".$_POST['ativoPagSeguro']."', emailpagseguro = '".$_POST['emailPagSeguro']."', tokenpagseguro = '".$_POST['tokenPagSeguro']."', urlretornopagseguro = '".$_POST['urlRetornoPagSeguro']."', fretepagseguro = '".$_POST['ativoFretePagSeguro']."', ativodeposito = '".$_POST['ativoDeposito']."', textodeposito = '".$_POST['textoDeposito']."'");
		
	}else{
		
		$con->executar("INSERT INTO ".Sistema::$BDPrefixo."pagamentos(tiposite, tipopedido, tipopedidoprodutostodosite, ativodesconto, codigodesconto, porcentagemdesconto, ativopagseguro, emailpagseguro, tokenpagseguro, urlretornopagseguro, fretepagseguro, ativodeposito, textodeposito) VALUES('".$_POST['tipoSite']."','".$_POST['tipoPedido']."','".$_POST['tipoPedidoProdutosTodoSite']."','".$_POST['ativoDesconto']."','".$_POST['codigoDesconto']."','".$_POST['porcetagemDesconto']."','".$_POST['ativoPagSeguro']."','".$_POST['emailPagSeguro']."','".$_POST['tokenPagSeguro']."','".$_POST['urlRetornoPagSeguro']."','".$_POST['ativoFretePagSeguro']."','".$_POST['ativoDeposito']."','".$_POST['textoDeposito']."')");
	}
	
	$javaScript .= Aviso::criar("Informações salvas com sucesso!");
	
}

$iMA = new IFAdmin(new Arquivos(Sistema::$adminLayoutCaminhoDiretorio."/SistemaConfiguracoes/pagamentos.html"));

$con->executar("SELECT * FROM ".Sistema::$BDPrefixo."pagamentos");
$rs = $con->getRegistro();

$iMA->trocar('tipoSite',					$rs['tiposite']);
$iMA->trocar('tipoPedido',					$rs['tipopedido']);
$iMA->trocar('tipoPedidoProdutosTodoSite',	$rs['tipopedidoprodutostodosite']);
$iMA->trocar('ativoDesconto',				$rs['ativodesconto']);
$iMA->trocar('codigoDesconto',				$rs['codigodesconto']);
$iMA->trocar('porcentagemDesconto',			$rs['porcentagemdesconto']);
$iMA->trocar('ativoPagSeguro',				$rs['ativopagseguro']);
$iMA->trocar('emailPagSeguro',				$rs['emailpagseguro']);
$iMA->trocar('tokenPagSeguro',				$rs['tokenpagseguro']);
$iMA->trocar('urlRetornoPagSeguro',			$rs['urlretornopagseguro']);
$iMA->trocar('ativoFretePagSeguro',			$rs['fretepagseguro']);
$iMA->trocar('ativoDeposito', 				$rs['ativodeposito']);
$iMA->trocar('textoDeposito',				$rs['textodeposito']);

$iMA->trocar('linkVoltar', "?p=".$_GET['p']."&a=configuracoes");
$iMA->createJavaScript();
$javaScript .= $iMA->javaScript->concluir();

$includePagina = $iMA->concluir();

?>