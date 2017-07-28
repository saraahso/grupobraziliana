<?php

importar("Geral.Lista");
importar("LojaVirtual.Produtos.EmpresaOfertaColetiva");
importar("Utils.Dados.Strings");
importar("Utils.Dados.DataHora");

class ListaEmpresasOfertaColetiva extends Lista {
	
	const ID							= 'id';
	const TIPO							= 'tipo';
	const NOME							= 'nome';
	const EMAIL							= 'email';
	const USUARIO						= 'usuario';
	const SENHA							= 'senha';
	const EMAILSECUNDARIO				= 'emailsecundario';
	const SEXO							= 'sexo';
	const RG							= 'rg';
	const CPF							= 'cpf';
	const DATANASC						= 'datanasc';
	const RAZAOSOCIAL					= 'razaosocial';
	const CNPJ							= 'cnpj';
	const SITE							= 'site';
	const DATACADASTRO					= 'datacadastro';
	const FOTO							= 'foto';
	
	const VALOR_TIPO_PESSOA_FISICA 		= 'fisica';
	const VALOR_TIPO_PESSOA_JURIDICA 	= 'juridica';
	
	public function __construct(){
		
		parent::__construct('ofertascoletivas_empresas');
		
	}
	
	public function listar($ordem = "ASC", $campo = self::ID){
		
		$info = parent::listar($ordem, $campo);
		
		if(!empty($info)){
			
			parent::resgatarObjetos($info);
			
			$temp = new EmpresaOfertaColetiva($info[self::ID]);
			
			$temp->setURL($info[parent::URL]);
			$temp->setTexto($info[parent::TEXTO]);
			
			$temp->razaoSocial 		= $info[self::RAZAOSOCIAL];
			$temp->cnpj 			= $info[self::CNPJ];
			$temp->rg 				= $info[self::RG];
			$temp->cpf 				= $info[self::CPF];
				
			$temp->setDataNasc(new DataHora($info[self::DATANASC]));

			$temp->usuario 			= $info[self::USUARIO];
			$temp->nome 			= $info[self::NOME];
			$temp->emailPrimario	= $info[self::EMAIL];
			$temp->senha 			= $info[self::SENHA];
			$temp->emailSecundario 	= $info[self::EMAILSECUNDARIO];
			$temp->sexo				= $info[self::SEXO];
			$temp->site 			= $info[self::SITE];

			$temp->setDataCadastro(new DataHora($info[self::DATACADASTRO]));
			
			if(!empty($info[self::FOTO]))
				$temp->setFoto(new Image(new Arquivos(Sistema::$caminhoURL.Sistema::$caminhoDataPessoasPerfil.$info[self::FOTO])));
			
			return $temp;
			
		}
		
	}
	
	public function inserir(&$pes){
				
		parent::inserir($pes);
		
		if($pes->getFoto()->nome != '')
			$foto = $pes->getFoto()->saveImage(Sistema::$caminhoDiretorio.Sistema::$caminhoDataPessoasPerfil);		
		
		$this->con->executar("INSERT INTO ".Sistema::$BDPrefixo.$this->tabela."(".parent::URL.",".parent::TEXTO.",".self::TIPO.", ".self::NOME.", ".self::EMAIL.", ".self::USUARIO.", ".self::SENHA.", ".self::EMAILSECUNDARIO.", ".self::SEXO.", ".self::RG.", ".self::CPF.", ".self::DATANASC.", ".self::RAZAOSOCIAL.", ".self::CNPJ.", ".self::SITE.", ".self::DATACADASTRO.", ".self::FOTO.") VALUES('".$pes->getURL()->getId()."','".$pes->getTexto()->getId()."','".$tipo."','".$pes->nome."','".$pes->emailPrimario."','".$pes->usuario."','".$pes->senha."','".$pes->emailSecundario."','".$pes->sexo."','".$pes->rg."','".$pes->cpf."','".($tipo == self::VALOR_TIPO_PESSOA_FISICA ? $pes->getDataNasc()->mostrar("Ymd") : '')."','".$pes->razaoSocial."','".$pes->cnpj."','".$pes->site."','".$pes->getDataCadastro()->mostrar("YmdHi")."','".$foto."')");
		
		$id = $this->con->getId();
		
		$class 	= __CLASS__;
		$l 		= new $class;
		$l->condicoes('', $id, self::ID);
		
		$pes = $l->listar();
		
		parent::alterar($pes);
		
	}
	
	public function alterar($pes){
		
		parent::alterar($pes);
		
		$where = "WHERE ".self::ID." = '".$pes->getId()."'";
			
		$this->con->alterar(Sistema::$BDPrefixo.$this->tabela, self::RAZAOSOCIAL, 		$pes->razaoSocial, $where);
		$this->con->alterar(Sistema::$BDPrefixo.$this->tabela, self::CNPJ, 				$pes->cnpj, $where);
		$this->con->alterar(Sistema::$BDPrefixo.$this->tabela, self::SEXO,				$pes->sexo, $where);
		$this->con->alterar(Sistema::$BDPrefixo.$this->tabela, self::RG, 				$pes->rg, $where);
		$this->con->alterar(Sistema::$BDPrefixo.$this->tabela, self::CPF, 				$pes->cpf, $where);
		$this->con->alterar(Sistema::$BDPrefixo.$this->tabela, self::DATANASC, 			($tipo == self::VALOR_TIPO_PESSOA_FISICA ? $pes->getDataNasc()->mostrar("Ymd") : ''), $where);

		$this->con->alterar(Sistema::$BDPrefixo.$this->tabela, self::USUARIO,			$pes->usuario, $where);
		$this->con->alterar(Sistema::$BDPrefixo.$this->tabela, self::NOME, 				$pes->nome, $where);
		$this->con->alterar(Sistema::$BDPrefixo.$this->tabela, self::EMAIL, 			$pes->emailPrimario, $where);
		$this->con->alterar(Sistema::$BDPrefixo.$this->tabela, self::SENHA, 			$pes->senha, $where);
		$this->con->alterar(Sistema::$BDPrefixo.$this->tabela, self::EMAILSECUNDARIO, 	$pes->emailSecundario, $where);
		$this->con->alterar(Sistema::$BDPrefixo.$this->tabela, self::SITE, 				$pes->site, $where);
		$this->con->alterar(Sistema::$BDPrefixo.$this->tabela, self::DATACADASTRO, 		$pes->getDataCadastro()->mostrar("YmdHi"), $where);
		
		if($pes->getFoto()->nome != ''){
			$foto = $pes->getFoto()->saveImage(Sistema::$caminhoDiretorio.Sistema::$caminhoDataPessoasPerfil);
			$this->con->alterar(Sistema::$BDPrefixo.$this->tabela, self::FOTO, $foto, $where);
		}
		
	}
	
	public function deletar($pes){
		
		parent::deletar($pes);
		
		$where = "WHERE id = '".$pes->getId()."'";
		
		
		while($end = $pes->getEndereco()->listar()){
				$pes->getEndereco()->deletar($end);
				$pes->getEndereco()->setParametros(0);
		}
				
		while($tel = $pes->getTelefone()->listar()){
				$pes->getTelefone()->deletar($tel);
				$pes->getTelefone()->setParametros(0);
		}
		
		while($email = $pes->getEmail()->listar()){
				$pes->getEmail()->deletar($email);
				$pes->getEmail()->setParametros(0);
		}
		
		Arquivos::__DeleteArquivo(Sistema::$caminhoDiretorio.Sistema::$caminhoDataPessoasPerfil.$pes->getFoto()->nome.".".$pes->getFoto()->extensao);
		
		$this->con->deletar(Sistema::$BDPrefixo.$this->tabela, $where);
		
	}
	
}

?>