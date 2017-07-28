<?php

importar("Geral.Imagem");

class ListaProdutoImagens {
	
	public	$imagens;
	private	$pos = 0;
	private	$limite = 999;
	private $dir;
	
	public function __construct($dir, $cod){
		
		for($i = 1; $i <= 30; $i++){
			
			if(file_exists($dir."/".$cod."_".$i.".jpg"))
				$this->imagens[] = $dir."/".$cod."_".$i.".jpg";
			
			if(file_exists($dir."/".$cod."_".$i.".jpeg"))
				$this->imagens[] = $dir."/".$cod."_".$i.".jpeg";
				
			if(file_exists($dir."/".$cod."_".$i.".png"))
				$this->imagens[] = $dir."/".$cod."_".$i.".png";
			
		}
		
		@ksort($this->imagens);
		
	}
	
	public function getTotal(){
		return count($this->imagens);
	}
	
	public function setParametros($num, $tipo = 'pos'){
		$this->$tipo = (int)$num;
	}
	
	public function listar($order = 'ASC', $campo = ''){
		
		if($this->pos < $this->limite && $this->pos < $this->getTotal() && $this->getTotal() > 0){
			$imagem = new Imagem;
			$imagem->setImage(new Image(new Arquivos($this->imagens[$this->pos])));
			$this->pos++;
		}
		
		return $imagem;
		
	}
	
	public function inserir(Imagem $img){
		
		if($this->getTotal() > 0){
			end($this->imagens);
			$name = current($this->imagens);
			$num = explode($img->getIdSessao()."_", $name);
			$num2 = explode(".jpg", $num[1]);
		}else
			$num2[0] = 0;
		
		$numero = ((int)$num2[0])+1;
		
		$img->getImage()->open();
		$img->getImage()->saveImage($this->dir, $img->getIdSessao()."_".$numero, "jpg");		
		
	}
	
	public function deletar($nome){
		@unlink($this->dir."/".$nome);
	}
	
}

?>