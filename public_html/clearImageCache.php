<?php

include("lib.conf/includes.php");

importar("Geral.Lista");

$l = new Lista("produtos");
$l->clearImageCache(Sistema::$caminhoDiretorio.Sistema::$caminhoDataProdutos);
$l->clearImageCache(Sistema::$caminhoDiretorio.Sistema::$caminhoDataSlides);
$l->clearImageCache(Sistema::$caminhoDiretorio.Sistema::$caminhoDataBanners);
echo 'Cache apagado com sucesso!';

?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title><?php echo Sistema::$nomeEmpresa;?></title>
<link rel="stylesheet" type="text/css" href="lib.js/jQuery/css/bootstrap/bootstrap.css">
<style type="text/css">
html, body {
	width: 100%;
	height: 100%;
}
body,td,th {
	font-size: 12px;
	color: #FFF;
}
div {
	width: 500px;
	margin: 20px 0 0 0;
}
</style>
</head>

<body>

<table width="100%" height="100%" border="0" cellspacing="0" cellpadding="0">
	<tr>
		<td align="center" valign="middle"> 
            <div class="alert alert-info">
            	Cache de imagens vazio!
            </div>
        </td>
	</tr>
</table>
</body>
</html>