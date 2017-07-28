<?php

importar("Utils.Arquivos");
importar("Utils.WebService.NuSoap.nusoap");
importar("Geral.Pais");
importar("Geral.Estado");
importar("Geral.Cidade");

class Endereco{
	
	private $id;
	private $cep;

	public 	$logradouro;
	public	$numero;
	public 	$complemento;
	public 	$bairro;
	public 	$cidade;
	public 	$estado;
	public 	$pais;
	
	public static $urlCep = "http://www.temtudoaqui.info/webservice/locate/getEnderecoByCEP/";
	
	public function __construct($id = ''){
		$this->id = $id;
		$this->pais = new Pais;
		$this->estado = new Estado;
		$this->cidade = new Cidade;
	}
	
	public function setPais(Pais $obj){
		$this->pais = $obj;
	}
	
	public function getPais(){
		return $this->pais;
	}
	
	public function setEstado(Estado $obj){
		$this->estado = $obj;
	}
	
	public function getEstado(){
		return $this->estado;
	}
	
	public function setCidade(Cidade $obj){
		$this->cidade = $obj;
	}
	
	public function getCidade(){
		return $this->cidade;
	}
	
	public function getId(){
		return $this->id;
	}
	
	public function setCep($vCep){
		$this->cep = $vCep;
	}
	
	public function loadCep(){
		
		if(!empty($this->cep)){
				
			$arq = self::_GetEnderecoByCep($this->cep);
			if((string)$arq["status"] == "false")
				$arq = Arquivos::__OpenArquivo(self::$urlCep.$this->cep, true);
			else
				$arq = JSON::_Encode($arq);
			
			if(!empty($arq)){
				
				$json = JSON::_Decode($arq);
				
				if(!empty($json->root->logradouro))
					$this->logradouro = $json->root->logradouro;
					
				if(!empty($json->root->bairro))
					$this->bairro = $json->root->bairro;
				
				if(!empty($json->root->uf)){
					$lE = new ListaEstados;
					$lE->condicoes('', strtoupper($json->root->uf), ListaEstados::UF);
					if($lE->getTotal() > 0)
						$this->estado = $lE->listar();
					else{
						$this->estado = new Estado;
						$this->estado->uf = $json->root->uf;
						$this->estado->nome = $json->root->estado;
						$this->estado->setPais($this->pais);
					}
				}
				
				if(!empty($json->root->idcidade)){
					$this->cidade = new Cidade($json->root->idcidade);
					$this->cidade->nome = $json->root->cidade;
					$this->cidade->setEstado($this->estado);
					$this->cidade->setPais($this->pais);
				}
				
			}
			
		}
	
	}
	
	public function getCep(){
		return $this->cep;
	}
	
	private static function _GetEnderecoByCep($cep){
		
		try{
			
			BDConexao::$usuario = 'tta_usuario';
			BDConexao::$senha = '2133618';
			
			$con = BDConexao::__Abrir();
			$con->executar("SET NAMES 'utf8';");
			
			$con->executar("SELECT CONCAT(l.LOG_TIPO_LOGRADOURO, ' ', l.LOG_NO) as logradouro, (SELECT c.LOC_NO FROM tta_correios.log_localidade c WHERE c.LOC_NU_SEQUENCIAL = l.LOC_NU_SEQUENCIAL) as cidade, l.LOC_NU_SEQUENCIAL as idcidade, l.UFE_SG as uf, (SELECT e.UFE_NO FROM tta_correios.log_faixa_uf e WHERE e.UFE_SG = l.UFE_SG) as estado, (SELECT b.BAI_NO FROM tta_correios.log_bairro b WHERE b.BAI_NU_SEQUENCIAL = l.BAI_NU_SEQUENCIAL_INI) as bairro FROM tta_correios.log_logradouro l WHERE l.CEP = '".str_replace("-", '', $cep)."'");
	
			if($con->getTotal() == 0){
				$con->executar("SELECT '' as logradouro, c.LOC_NO as cidade, c.LOC_NU_SEQUENCIAL as idcidade, c.UFE_SG as uf, (SELECT e.UFE_NO FROM tta_correios.log_faixa_uf e WHERE e.UFE_SG = c.UFE_SG) as estado, '' as bairro FROM tta_correios.log_localidade c WHERE c.CEP = '".str_replace("-", '', $cep)."'");
			}
			
			if($con->getTotal() > 0){
				$rs['status'] = 'true';
				$rs['size'] = $con->getTotal();
				while($tmp = $con->getRegistro()) $rs['root'][] = $tmp;
				
				BDConexao::$usuario = Sistema::$BDUsuario;
				BDConexao::$senha = Sistema::$BDSenha;
				
				$con = BDConexao::__Abrir();
				$con->executar("SET NAMES 'utf8';");
				$con->close();
			}else{
				$rs['status'] = 'false';
				$rs['size'] = 0;
				$rs['msg'] = mysql_error();
				
				BDConexao::$usuario = Sistema::$BDUsuario;
				BDConexao::$senha = Sistema::$BDSenha;
				
				$con = BDConexao::__Abrir();
				$con->executar("SET NAMES 'utf8';");
				$con->close();
			}
		
		}catch(Exception $e){
			$rs['status'] = 'false';
			$rs['size'] = 0;
			$rs['msg'] = $e->getMessage();
			
			BDConexao::$usuario = Sistema::$BDUsuario;
			BDConexao::$senha = Sistema::$BDSenha;		
		}
		
		$con = BDConexao::__Abrir();
		$con->executar("SET NAMES 'utf8';");
		$con->close();	
		
		return $rs;
		
	}
	
	
}

?>