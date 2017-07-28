<?php

include("../../../../lib.conf/includes.php");

importar("Utils.Imagens.Image");

if (!empty($_FILES['upload']['name'])) {
	
	$img = new Image(Arquivos::__OpenArquivoByTEMP($_FILES['upload']));
	$img->open();
	$nome = $img->saveImage(Sistema::$caminhoDiretorio.Sistema::$caminhoDataTextos);
	
	echo "<script type='text/javascript'>window.parent.CKEDITOR.tools.callFunction(".$_GET['CKEditorFuncNum'].", '".Sistema::$caminhoURL.Sistema::$caminhoDataTextos.$nome."', '');</script>";
	exit;
}
?>

<html>
<head>
<style>
  html, body, table, button, div, input, select, fieldset {
	font-family: MS Shell Dlg;
	font-size: 8pt;
	background-color: #FFFFFF;
};
</style>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1"></head>
<body topmargin="6" scroll=no>
<form name="form1" method="post" action="" enctype="multipart/form-data">
<LEGEND id=lgdLayout></LEGEND>
<table  border="0" cellspacing="3" cellpadding="3">
  <tr>
    <td colspan="2"><B>Procure a imagem:</B></td>
  </tr>
  <tr>
    <td colspan="2">Selecione a imagem no seu computado,
	em seguida clique no bot&atilde;o up-load.</td>
  </tr>
  <tr>
    <td colspan="2"><input name="form_imagem" type="file" id="form_imagem" size="45"></td>
  </tr>
  <tr>
    <td><input name="acao" type="hidden" id="acao" value="enviar">
      <input name="form_url" type="hidden" id="form_url" value="<? echo $url; ?>">
      <input name="retorno" type="hidden" id="retorno" value="<? echo $_GET['retorno']; ?>">
      <input type="submit" name="Submit" value="enviar"></td>
    <td align="right"></td>
  </tr>
</table>
</form>
</body>
</html>
