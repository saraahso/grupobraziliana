<?php
exit;
include('includes.php');

importar('Utils.Imagens.Image');
importar('Utils.MD5');

$m = new MD5;
$caminho = $m->descriptografar($_GET['caminho']);

$arq = new Arquivos($caminho);
$arq->open();

header('Expires: 0'); 
header('Pragma: no-cache');

if(strtoupper($arq->extensao) == "JPEG" || strtoupper($arq->extensao) == "JPG" || strtoupper($arq->extensao) == "PNG" || strtoupper($arq->extensao) == "GIF" || strtoupper($arq->extensao) == "BMP"){

	header("Content-type: image/".$arq->extensao);
	
	$he = number_format($_GET['h']);
	if($he < $_GET['h']) $he++;
	
	if(file_exists(dirname($arq->url)."/".$he."/".basename($arq->url))){
		$img = new Image(new Arquivos(dirname($arq->url)."/".$he."/".$arq->nome.".".$arq->extensao));
	}else{
		$img = new Image($arq);
		
		if(($_GET['w'] < $img->largura || $_GET['h'] < $img->altura) && (!empty($_GET['w']) && !empty($_GET['h']))){
			$img->redimensionar($_GET['w'], $_GET['h']);
		}
		
	}
	
	/*/marca d'agua
	
	$logoAlo = new Imagem(new Arquivos(caminhoDiretorio."lib.img/LOGOALO.gif"));
	$logoAloW = (15*$_GET['w'])/100;
	$logoAloH = (15*$_GET['h'])/100;
	$logoAlo->redimensionar($logoAloW, $logoAloH);
	
	$img->ajuntarImagem($logoAlo, $img->largura-($logoAlo->largura+1), 1, 50);
	
	/*/
	
	$img->getImage();
	
}else{
	
	header("Content-Type: application/".$arq->extensao);
	header('Content-disposition: attachment; filename="'.$arq->getNome().'";');
	$len = filesize($arq->url);
	header("Content-Length: ".$len.";\n");
	
	echo $arq->arquivo;
	
}


?>