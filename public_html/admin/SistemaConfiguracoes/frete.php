<?php

importar("Utils.Imagens.Image");
importar("Utils.Dados.Numero");

$tituloPagina = 'Configura&ccedil;&otilde;es > Frete';
$con = BDConexao::__Abrir();
if(!empty($_POST)){
	
	$con->executar("SELECT * FROM ".Sistema::$BDPrefixo."frete");
	if($con->getTotal() > 0){
		
		$con->executar("UPDATE ".Sistema::$BDPrefixo."frete SET ceporigem = '".$_POST['cepOrigem']."', ativocorreio = '".$_POST['ativoCorreio']."', logincorreio = '".$_POST['loginCorreio']."', senhacorreio = '".$_POST['senhaCorreio']."', fretegratis = '".$_POST['freteGratis']."', apartirvalorfretegratis = '".Numero::__CreateNumero($_POST['apartirValorFreteGratis'])->formatar()."'");
		
	}else{
		
		$con->executar("INSERT INTO ".Sistema::$BDPrefixo."frete(ceporigem, ativocorreio, logincorreio, senhacorreio, fretegratis, apartirvalorfretegratis) VALUES('".$_POST['cepOrigem']."','".$_POST['ativoCorreio']."','".$_POST['loginCorreio']."','".$_POST['senhaCorreio']."','".$_POST['freteGratis']."','".Numero::__CreateNumero($_POST['apartirValorFreteGratis'])->formatar()."')");
	}
	
	$javaScript .= Aviso::criar("Informações salvas com sucesso!");
	
}

$iMA = new IFAdmin(new Arquivos(Sistema::$adminLayoutCaminhoDiretorio."/SistemaConfiguracoes/frete.html"));

$con->executar("SELECT * FROM ".Sistema::$BDPrefixo."frete");
$rs = $con->getRegistro();

$iMA->trocar('ativoCorreio',			$rs['ativocorreio']);
$iMA->trocar('loginCorreio',			$rs['logincorreio']);
$iMA->trocar('senhaCorreio',			$rs['senhacorreio']);
$iMA->trocar('freteGratis', 			$rs['fretegratis']);
$iMA->trocar('cepOrigem', 				$rs['ceporigem']);
$iMA->trocar('apartirValorFreteGratis',	Numero::__CreateNumero($rs['apartirvalorfretegratis'])->moeda());

$iMA->trocar('linkVoltar', "?p=".$_GET['p']."&a=configuracoes");
$iMA->createJavaScript();
$javaScript .= $iMA->javaScript->concluir();

$includePagina = $iMA->concluir();

?>