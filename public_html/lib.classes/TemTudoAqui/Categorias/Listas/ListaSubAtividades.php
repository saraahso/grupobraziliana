<?php

importar("Geral.Lista");
importar("TemTudoAqui.Categorias.SubAtividade");
importar("Utils.Dados.Strings");

class ListaSubAtividades extends Lista {
	
	public function __construct(){
		
		parent::__construct('sub_atividade');
		
	}
	
	public function listar($ordem = "ASC", $campo = 'id'){
		
		$info = parent::listar($ordem, $campo);
		
		if(!empty($info)){
		
			$sa = new SubAtividade($info['id']);
			$sa->titulo = $info['titulo'];
			
			return $sa;
		
		}
		
	}
	
	public function inserir(SubAtividade &$sa, Atividade $atividade){
		
		$id = $this->getId("SA");	
		$this->con->executar("INSERT INTO ".BDPrefixo.$this->tabela."(id, atividade, titulo) VALUES('".$id."','".$atividade->getId()."','".$sa->titulo."')");
		
		$temp = new SubAtividade($id);
		$temp->titulo = $sa->titulo;
		
		$sa = $temp;
		
	}
	
	public function alterar(SubAtividade $sa){
		
		$where = "WHERE id = '".$sa->getId()."'";
		
		$this->con->alterar(BDPrefixo.$this->tabela, 'titulo', $sa->titulo, $where);
		
	}
	
	public function deletar(SubAtividade $sa){
		
		$where = "WHERE id = '".$sa->getId()."'";
		
		$this->con->deletar(BDPrefixo.$this->tabela, $where);
		
	}
	
}

?>