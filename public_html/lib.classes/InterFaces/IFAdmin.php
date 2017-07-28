<?php

importar('Geral.InterFaces');

class IFAdmin extends InterFaces {
	
	public function __construct(Arquivos $template){
		
		parent::__construct($template, true);
		
	}
	
}

?>