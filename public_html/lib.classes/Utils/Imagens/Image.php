<?php

importar('Utils.Imagens.NewImage');
importar('Utils.Arquivos');
importar('Utils.MD5');

class Image extends NewImage {
	  
	  public function __construct($imagem = ''){
		  
		  if(!empty($imagem)){
		  
		  	$this->extensao = $imagem->extensao;
			$this->nome = $imagem->nome;
			$this->url = $imagem->url;
			$this->imagem = $imagem;

		  }else
		  	parent::__construct(1, 1);
		  
	 }
	 
	 public function open(){
		 
		 if(empty($this->imagem->arquivo) && $this->imagem instanceof Arquivos){
		 	$this->imagem->open();
		 	$this->imagem = imagecreatefromstring($this->imagem->arquivo);
		 }		 

         //imageantialias($this->imagem, true);
		 $this->alfa();
		 $this->original = $this->imagem;
		 $this->largura = @imagesx($this->imagem);
		 $this->altura = @imagesy($this->imagem);
		 
	 }
	 
	 public function getImage($ext = '', $qualidade = 100){
		  
		  if(empty($ext)) $ext = $this->extensao;
		  if(empty($ext)) $ext = 'jpg';
		 
		  if(strtoupper($ext) == 'JPG' || strtoupper($ext) == 'JPEG'){
			  
			  imagejpeg($this->imagem);
			  
		  }elseif(strtoupper($ext) == 'GIF'){
			  
			  @imagesavealpha($this->imagem, TRUE);
			  @imagegif($this->imagem);
			  
		  }elseif(strtoupper($ext) == 'PNG'){
			  
			  imagesavealpha($this->imagem, TRUE);
			  imagepng($this->imagem);
			  
		  }elseif(strtoupper($ext) == 'BMP'){
			  
			  imagewbmp($this->imagem);
			  
		  }
		  
		  @imagedestroy($this->imagem);
		  
	 }
	 
	 public static function __PathImage($caminho, $w = '', $h = ''){
		  
		  $div1 = explode(".", $caminho);
		  $new = $div1[0];
		  for($i = 1; $i < count($div1)-1; $i++)
		  	$new .= ".".$div1[$i];
		  
		  $return = Sistema::$caminhoURL.str_replace($_SERVER['DOCUMENT_ROOT'], "../", str_replace(Sistema::$caminhoDiretorio, "", $new)).'size-'.$w.'-'.$h.".".$div1[count($div1)-1];
		  
		  if(preg_match("!fotos!", $return))
		  	$return = str_replace('w3', 'www', $return);
		  
		  return $return;
		  
	 }
	 
	 public function pathImage($w = '', $h = ''){
		  
		  return self::__PathImage($this->url, $w, $h);
		  
	 }
	 
	 public static function __ImageHTML($caminho = '', $w = '', $h = '', $alt){
		  
		  return '<img src="'.self::__PathImage($caminho, $w, $h).'" border="0" alt="'.$alt.'" />';
		  
	 }
	 
	 public function showHTML($w = '', $h = '', $alt = ''){
		  
		  if(empty($caminho)) $caminho = $this->url;
		  if(empty($w)) $w = $this->largura;
		  if(empty($h)) $h = $this->altura;
		  
		  return self::__ImageHTML($caminho, $w, $h, $alt);
		  
	 }
	 	   
}

?>