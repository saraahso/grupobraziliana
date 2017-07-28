<?php

include('includes.php');
ini_set('display_errors', true);
//error_log(E_ALL);
//error_reporting(E_ALL);
importar('Utils.Imagens.Image');

$caminho = $_SERVER['REQUEST_URI'];
//echo $caminho; exit;
$div1 = explode(".", $caminho);
$new = $div1[0];
for($i = 1; $i < count($div1)-1; $i++)
	$new .= ".".$div1[$i];
	
$nome 	= basename($caminho);
$nome	= str_replace(".".$div1[count($div1)-1], "", $nome);

$div2 = explode('-', $new);

$width = $div2[count($div2)-2];
$height = $div2[count($div2)-1];

$caminho = str_replace("size-".$width."-".$height, "", $caminho);

$arq = new Arquivos(str_replace("", '', Sistema::$caminhoDiretorio).urldecode($caminho));

header('Expires: 0'); 
header('Pragma: no-cache');

if(strtoupper($arq->extensao) == "JPEG" || strtoupper($arq->extensao) == "JPG" || strtoupper($arq->extensao) == "PNG" || strtoupper($arq->extensao) == "GIF" || strtoupper($arq->extensao) == "BMP"){

	header("Content-type: image/".$arq->extensao);
	
	$img = new Image($arq);
	$img->open();

	if((!empty($width) && !empty($height))){
		$img->redimensionar($width, $height);
		$img->saveImage(dirname(substr(str_replace("", '', Sistema::$caminhoDiretorio), 0, -1).urldecode($caminho)), urldecode($nome));
	}
	
	$img->getImage();
	
}else{
	
	header("Content-Type: application/".$arq->extensao);
	header('Content-disposition: attachment; filename="'.$arq->getNome().'";');
	$len = filesize($arq->url);
	header("Content-Length: ".$len.";\n");
	
	echo $arq->arquivo;
	
}


?>
