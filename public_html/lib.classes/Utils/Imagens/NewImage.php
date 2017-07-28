<?php

class NewImage {
	  
	  protected $imagem;
	  protected $original;
	  
	  public 	$nome;
	  public	$extensao;
	  public	$url;
	  public 	$altura;
	  public 	$largura;
	  public 	$x = 0;
	  public 	$y = 0;
	  
	  public function __construct($l, $a){
		  
		  $this->imagem = imagecreatetruecolor($l, $a);
		  $this->original = $this->imagem;
		  $this->largura = $l;
		  $this->altura = $a;
		  $this->nome = '';
		  
		  $background = @imagecolorallocatealpha($this->imagem, 255, 255, 255, 127); 		  
          @imagefill($this->imagem, 0, 0, $background);
		  
	  }
	  
	  protected function alfa(&$temp = ''){
	  	  
		  if(empty($temp))
		  	$temp = $this->imagem;
		  
	  	  @imagealphablending($temp, true);
		  @imagesavealpha($temp, TRUE); 
		  //$background = @imagecolorallocatealpha($temp, 255, 255, 255, 127); 		  
          //@imagefill($temp, 0, 0, $background);
	  	
	  }
	  
	  public function redimensionar($l, $a = '', $perspectiva = true){
		  
		  $cL = $this->largura;
		  $cA = $this->altura;
		  
		  if(!empty($l) && $cL > $l){
			  
			  $d = $cL/$l;
			  $cL /= $d;
			  
			  if($perspectiva) $cA /= $d;
			 
		  }
		  
		  if(!empty($a) && $cA > $a){
			  
			  $d = $cA/$a;
			  $cA /= $d;
			  
			  if($perspectiva) $cL /= $d;
			 
		  }
		  
		  $temp = imagecreatetruecolor($cL, $cA);
		  $background = @imagecolorallocatealpha($temp, 255, 255, 255, 127); 		  
          @imagefill($temp, 0, 0, $background);
		  //imagecopymerge($temp, $this->imagem, 0, 0, 0, 0, $this->largura, $this->altura, 100);
		  imagecopyresampled($temp, $this->imagem, 0, 0, 0, 0, $cL, $cA, $this->largura, $this->altura);
		  //imagecopyresized($temp, $this->imagem, 0, 0, 0, 0, $cL, $cA, $this->largura, $this->altura);
		  
		  $this->largura = $cL;
		  $this->altura = $cA;
		  $this->imagem = $temp;
		  
		  return $this;
		  		  
	 }
	 
	 public function imagecopymerge_alpha($dst_im, $src_im, $dst_x, $dst_y, $src_x, $src_y, $src_w, $src_h, $pct){ 
		if(!isset($pct)){ 
			return false; 
		} 
		$pct /= 100; 
		// Get image width and height 
		$w = imagesx( $src_im ); 
		$h = imagesy( $src_im ); 
		// Turn alpha blending off 
		imagealphablending( $src_im, false ); 
		// Find the most opaque pixel in the image (the one with the smallest alpha value) 
		$minalpha = 127; 
		for( $x = 0; $x < $w; $x++ ) 
		for( $y = 0; $y < $h; $y++ ){ 
			$alpha = ( imagecolorat( $src_im, $x, $y ) >> 24 ) & 0xFF; 
			if( $alpha < $minalpha ){ 
				$minalpha = $alpha; 
			} 
		} 
		//loop through image pixels and modify alpha for each 
		for( $x = 0; $x < $w; $x++ ){ 
			for( $y = 0; $y < $h; $y++ ){ 
				//get current alpha value (represents the TANSPARENCY!) 
				$colorxy = imagecolorat( $src_im, $x, $y ); 
				$alpha = ( $colorxy >> 24 ) & 0xFF; 
				//calculate new alpha 
				if( $minalpha !== 127 ){ 
					$alpha = 127 + 127 * $pct * ( $alpha - 127 ) / ( 127 - $minalpha ); 
				} else { 
					$alpha += 127 * $pct; 
				} 
				//get the color index with new alpha 
				$alphacolorxy = imagecolorallocatealpha( $src_im, ( $colorxy >> 16 ) & 0xFF, ( $colorxy >> 8 ) & 0xFF, $colorxy & 0xFF, $alpha ); 
				//set pixel with the new color + opacity 
				if( !imagesetpixel( $src_im, $x, $y, $alphacolorxy ) ){ 
					return false; 
				} 
			} 
		} 
		// The image copy 
		imagecopy($dst_im, $src_im, $dst_x, $dst_y, $src_x, $src_y, $src_w, $src_h); 
	}
	 
	 public function combineImage(&$imagem, $posX = 0, $posY = 0, $x = 0, $y = 0, $alfa = 100){
		  
		  $img = imagecreatetruecolor($imagem->largura, $imagem->altura);
          @imagealphablending($img, false);
          @imagesavealpha($img, TRUE); 
		  $background = @imagecolorallocatealpha($img, 0, 0, 0, 50); 
          @imagefill($img, 0, 0, $background); 
		  $this->imagecopymerge_alpha($img, $imagem->imagem, 0, 0, 0, 0, $imagem->largura, $imagem->altura, $alfa);
		  imagesavealpha($img, true); 
		 
          @imagealphablending($this->imagem, true);
          @imagesavealpha($this->imagem, TRUE); 
		  
		  imagecopyresampled($this->imagem, $img, $x, $y, $posX, $posY, $imagem->largura, $imagem->altura, $imagem->largura, $imagem->altura);
		  
		  return $this;
		  
	 }
	 
	 public function writeText($text, $x = 0, $y = 0, $tam = 5, $alfa = 100){
		 
		 $cor = imagecolorallocate($this->imagem, 255, 255, 255);
		 imagestring($this->imagem, $tam, $x, $y, $text, $cor);
		 
		 return $this;
	 }
	 
	 public function cutImage($w, $h, $x = 0, $y = 0){
	
		$img = new NewImage($w, $h);
		$img->combineImage($this, $x, $y);
		
		return $img;
	
	}
	 
	 public function showImage(){
		  
		  return $this->imagem;
		  
	 }
	 
	 public function resetImage(){
		  
		  $this->imagem = $this->original;
		  
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
	 
	 public function saveImage($caminho, $nome = '', $ext = '', $qualidade = 100){

		  if(empty($nome)) 	$nome = $this->nome;
		  if(empty($ext)) 	$ext = $this->extensao;
		  if(empty($ext)) 	$ext = 'jpg';
		 
		  $nome = eregi_replace(' ', '', $nome);
		  $nome = ereg_replace("á", 'a', $nome);
		  $nome = ereg_replace('â', 'a', $nome);
		  $nome = ereg_replace('ã', 'a', $nome);
		  $nome = ereg_replace('é', 'e', $nome);
		  $nome = ereg_replace('ê', 'e', $nome);
		  $nome = ereg_replace('ó', 'o', $nome);
		  $nome = ereg_replace('õ', 'o', $nome);
		  $nome = ereg_replace('ô', 'o', $nome);
		  $nome = ereg_replace('í', 'i', $nome);
		  $nome = ereg_replace('ú', 'u', $nome);
		  $nome = ereg_replace('ü', 'u', $nome);
		  $nome = ereg_replace('ç', 'c', $nome);

		  if(strtoupper($ext) == 'JPG' || strtoupper($ext) == 'JPEG'){
				imageinterlace($this->imagem, 1);
			  imagejpeg($this->imagem, $caminho.'/'.$nome.'.'.$ext, 100);
			  
		  }elseif(strtoupper($ext) == 'GIF'){
			  
			  @imagesavealpha($this->imagem, TRUE);
			  @imagegif($this->imagem, $caminho.'/'.$nome.'.'.$ext);
			  
		  }elseif(strtoupper($ext) == 'PNG'){
			  
			  @imagesavealpha($this->imagem, TRUE);
			  @imagepng($this->imagem, $caminho.'/'.$nome.'.'.$ext);
			  
		  }elseif(strtoupper($ext) == 'BMP'){
			  
			  @imagewbmp($this->imagem, $caminho.'/'.$nome.'.'.$ext);
			  
		  }
		  
		  $this->url = $caminho.'/'.$nome.'.'.$ext;
		  
		  return $nome.'.'.$ext;
		 
	 }
	  
}

?>