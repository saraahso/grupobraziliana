<?php

importar("Utils.Imagens.Image");
importar("Utils.Dados.Numero");

$tituloPagina = 'Configura&ccedil;&otilde;es > Produtos';
$con = BDConexao::__Abrir();
if(!empty($_POST['cambioreal'])){
	
	$con->executar("SELECT * FROM ".Sistema::$BDPrefixo."produtos_configuracoes");
	if($con->getTotal() > 0){
		
		$con->executar("UPDATE ".Sistema::$BDPrefixo."produtos_configuracoes SET produtosporpagina = '".$_POST['produtosPorPagina']."', listasubcategorias = '".$_POST['listaSubCategorias']."', produtosporsubcategoria = '".$_POST['produtosPorSubCategoria']."', cambioreal = '{$_POST['cambioreal']}'");
		
	}else{
		
		$con->executar("INSERT INTO ".Sistema::$BDPrefixo."produtos_configuracoes(produtosporpagina, listasubcategorias, produtosporsubcategoria, cambioreal) VALUES('".$_POST['produtosPorPagina']."','".$_POST['listasubcategorias']."','".$_POST['produtosporsubcategoria']."','{$_POST['cambioreal']}')");
	}
	
	$javaScript .= Aviso::criar("Informações salvas com sucesso!");
	
}

$iMA = new IFAdmin(new Arquivos(Sistema::$adminLayoutCaminhoDiretorio."/SistemaConfiguracoes/produtos.html"));

$con->executar("SELECT * FROM ".Sistema::$BDPrefixo."produtos_configuracoes");
$rs = $con->getRegistro();

$iMA->trocar('produtosPorPagina',		$rs['produtosporpagina']);
$iMA->trocar('listaSubCategorias',		$rs['listasubcategorias']);
$iMA->trocar('produtosPorSubCategoria',	$rs['produtosporsubcategoria']);
$iMA->trocar('cambioreal',	$rs['cambioreal']);

$iMA->trocar('linkVoltar', "?p=".$_GET['p']."&a=configuracoes");
$iMA->createJavaScript();
$javaScript .= $iMA->javaScript->concluir();

$includePagina = $iMA->concluir();

?>