<?php

importar("Utils.Imagens.Image");

$tituloPagina = 'Configura&ccedil;&otilde;es > Marca d\'Agua';
$con = BDConexao::__Abrir();
if(!empty($_POST)){
	
	$con->executar("SELECT * FROM ".Sistema::$BDPrefixo."marcadagua");
	if($con->getTotal() > 0){
		
		if(!empty($_FILES['imagem']['name'])){
			$img = new Image(Arquivos::__OpenArquivoByTEMP($_FILES['imagem']));
			$img->open();
			$imagem = $img->saveImage(Sistema::$caminhoDiretorio.Sistema::$caminhoDataIdiomas);
			$con->executar("UPDATE ".Sistema::$BDPrefixo."marcadagua SET imagem = '".$imagem."'");
		}
		
		$con->executar("UPDATE ".Sistema::$BDPrefixo."marcadagua SET posicaohorizontal = '".$_POST['posh']."', posicaovertical = '".$_POST['posv']."', tipo = '".$_POST['tipo']."', texto = '".$_POST['texto']."', produtos = '".$_POST['produtos']."', galerias = '".$_POST['galerias']."'");
		
	}else{
		
		if(!empty($_FILES['imagem']['name'])){
			$img = new Image(Arquivos::__OpenArquivoByTEMP($_FILES['imagem']));
			$img->open();
			$imagem = $img->saveImage(Sistema::$caminhoDiretorio.Sistema::$caminhoDataIdiomas);
		}
		
		$con->executar("INSERT INTO ".Sistema::$BDPrefixo."marcadagua(posicaohorizontal, posicaovertical, tipo, imagem, texto, produtos, galerias) VALUES('".$_POST['posh']."','".$_POST['posv']."','".$_POST['tipo']."','".$imagem."','".$_POST['texto']."','".$_POST['produtos']."','".$_POST['galerias']."')");
	}
	
	$javaScript .= Aviso::criar("Informações salvas com sucesso!");
	
}

$iMA = new IFAdmin(new Arquivos(Sistema::$adminLayoutCaminhoDiretorio."/SistemaConfiguracoes/marcadAgua.html"));

$con->executar("SELECT * FROM ".Sistema::$BDPrefixo."marcadagua");
$rs = $con->getRegistro();

$iMA->trocar('posh', 		$rs['posicaohorizontal']);
$iMA->trocar('posv', 		$rs['posicaovertical']);
$iMA->trocar('tipo', 		$rs['tipo']);
$iMA->trocar('imagem', 		$rs['imagem'] ? '<img src="'.Sistema::$caminhoURL.Sistema::$caminhoDataIdiomas.$rs['imagem'].'" border="0" width="200"' : '');
$iMA->trocar('texto', 		$rs['texto']);
$iMA->trocar('produtos', 	$rs['produtos']);
$iMA->trocar('galerias', 	$rs['galerias']);

$iMA->trocar('linkVoltar', "?p=".$_GET['p']."&a=configuracoes");
$iMA->createJavaScript();
$javaScript .= $iMA->javaScript->concluir();

$includePagina = $iMA->concluir();

?>