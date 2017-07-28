<?php

importar("Utils.Templates");
importar("Utils.Arquivos");
importar("Geral.Idiomas.Lista.ListaIdiomas");

class InterFaces extends Templates {
	
	protected		$POST;
	protected		$GET;
	protected		$FILES;
	protected		$nomeBotao;
	
	public function __construct(Arquivos $template, $admin = false){
		
		$template->open();
		parent::__construct($template);
		
		//$this->trocar('src="', 'src="'.Sistema::$layoutCaminhoURL);
		if($admin)
			$this->_trocas(Sistema::$adminLayoutCaminhoURL);
		else
			$this->_trocas(Sistema::$layoutCaminhoURL);
		
		if(!empty(Sistema::$nomeEmpresa)) $this->trocar("nomeEmpresa", Sistema::$nomeEmpresa);
		
		if(!empty(Sistema::$caminhoURL)) $this->trocar("caminhoURL", Sistema::$caminhoURL);
		if(!empty(Sistema::$caminhoDiretorio)) $this->trocar("caminhoDiretorio", Sistema::$caminhoDiretorio);
		if(!empty(Sistema::$layoutCaminhoDiretorio)) $this->trocar("layoutCaminhoDiretorio", Sistema::$layoutCaminhoDiretorio);
		if(!empty(Sistema::$layoutCaminhoURL)) $this->trocar("layoutCaminhoURL", Sistema::$layoutCaminhoURL);
		
		if(!empty(Sistema::$adminCaminhoURL)) $this->trocar("adminCaminhoURL", Sistema::$adminCaminhoURL);
		if(!empty(Sistema::$adminCaminhoDiretorio)) $this->trocar("adminCaminhoDiretorio", Sistema::$adminCaminhoDiretorio);
		if(!empty(Sistema::$adminLayoutCaminhoDiretorio)) $this->trocar("adminLayoutCaminhoDiretorio", Sistema::$adminLayoutCaminhoDiretorio);
		if(!empty(Sistema::$adminLayoutCaminhoURL)) $this->trocar("adminLayoutCaminhoURL", Sistema::$adminLayoutCaminhoURL);
		
		
		$this->GET	 		= '';
		$this->POST	 		= '';
		$this->FILE 		= '';
		$this->SESSION		= '';
		$this->nomeBotao 	= 'Cadastrar';
		
	}
	
	private function _trocas($url) {
		
		$string = $this->arquivo->arquivo;
		// imagens
		
		$reg = array();
		$tags = array(
					  
			'img'=>'src',
			'input'=>'src',
			'td'=>'background',
			'th'=>'background',
			'table'=>'background',
			'link'=>'href',
			'script'=>'src',
			'object'=>'data',
			'embed'=>'movie',
			'embed'=>'src'
		);
		
		foreach($tags as $tag => $att) {
			
			preg_match_all('@<'.$tag.'(.*?)'.$att.'="(.+?)"(.*?)>@i', $string, $reg);
			
			for($i = 0; $i < count($reg[0]); $i++) {
				
				if(!preg_match('[{*}]', $reg[2][$i])) $nova = '<'.$tag.$reg[1][$i].$att.'="'.$url.$reg[2][$i].'"'.$reg[3][$i].'>';
				else	$nova = '<'.$tag.$reg[1][$i].$att.'="'.$reg[2][$i].'"'.$reg[3][$i].'>';
				
				$string = str_replace($reg[0][$i], $nova, $string);

				
			}
			
		}
		
		
		// css
		
		preg_match_all('@url\((.*?)\)@i', $string, $reg);
		
		for($i=0; $i<count($reg[0]); $i++) {
				
			if(!preg_match('[{*}]', $reg[1][$i])) $nova = sprintf('url(%s)', $url . $reg[1][$i]);
			else $nova = sprintf('url(%s)', $reg[1][$i]);
			$string = str_replace($reg[0][$i], $nova, $string);
				
			
		}
		
		/*/ SWFObject
		
		preg_match_all("@SWFObject\('(.+?)\.swf'@i", $string, $reg);
									  
		for($i=0; $i<count($reg[0]); $i++) {
			
			$parts = explode('/', $reg[1][$i]);
			$file = array_pop($parts);
			if(!preg_match('[{*}]', $reg[1][$i])) $nova = sprintf("SWFObject('%s/%s.swf'", $url, $file);
			else $nova = sprintf("SWFObject('%s.swf'", $file);
			$string = str_replace($reg[0][$i], $nova, $string);
			
		}*/
		
		$reg = array();
		$tags = array(
					  
			'param'=>'value',
			
		);
		
		foreach($tags as $tag => $att) {
			
			preg_match_all('@<'.$tag.'(.*?)'.$att.'="(.+?)"(.*?)>@i', $string, $reg);
			
			for($i = 0; $i < count($reg[0]); $i++) {
				
				if(!preg_match('[{*}]', $reg[2][$i]) && (eregi('movie', $reg[1][$i]) || eregi('movie', $reg[3][$i]))) $nova = '<'.$tag.$reg[1][$i].$att.'="'.$url.$reg[2][$i].'"'.$reg[3][$i].'>';
				else	$nova = '<'.$tag.$reg[1][$i].$att.'="'.$reg[2][$i].'"'.$reg[3][$i].'>';
				
				$string = str_replace($reg[0][$i], $nova, $string);

				
			}
			
		}
		
		$this->arquivo->arquivo = $string;
		
	}
	
	public function setGET($vGET){
		$this->GET = $vGET;	
	}
	
	public function setPOST($vPOST){
		$this->POST = $vPOST;	
	}
	
	public function setFILE($vFILE){
		$this->FILE = $vFILE;	
	}
	
	public function setSESSION($vSESSION){
		$this->SESSION = $vSESSION;	
	}
	
	public function setNomeBotao($vNome){
		$this->nomeBotao = $vNome;	
	}
	
	public function concluir(){
		
		$this->trocar('nomeBotao', $this->nomeBotao);
		
		$cont = $this->getListaTraducoes();
		if(count($cont) > 0){
			
			$lI = new ListaIdiomas;
			
			$lI->condicoes('', $this->SESSION['lang'], ListaIdiomas::SIGLA);
			
			if($lI->getTotal() > 0)
				$i = $lI->listar();
			else
				$i = new Idioma;
			
			foreach($cont as $v){
				
				$this->trocar('traduzir->'.$v, $i->getTraducaoByConteudo($v)->traducao);
			
			}
		
		}
		
		return parent::concluir();
		
	}
	
	public function getListaTraducoes(){
		preg_match_all('@{traduzir->(.*?)}@i', $this->arquivo->arquivo, $cont);
		return $cont[1];
	}
	
	public function mostrar(){
		
		echo $this->concluir();
		
	}
	
	public function visualizar(){
			
	}
	
	public function setAlterar(){
		
		$this->nomeBotao = 'Alterar';
		$this->visualizar();
		
	}
	
}

?>