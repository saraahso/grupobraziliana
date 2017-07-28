<?php

class BDConexao {
   
    public static $host;
    public static $banco;
    public static $usuario;
    public static $senha;
    public static $staticConnection;
   
   private $erros = false;
   private $conexao;
   private static $instance;
   
   private function __construct($host = '', $banco = '', $usuario = '', $senha = ''){

	  $this->conexao = mysql_connect(BDConexao::$host, BDConexao::$usuario, BDConexao::$senha);
      //$this->conexao = self::$staticConnection;
      $this->banco(self::$banco);

       /*$result = mysql_query("SHOW FULL PROCESSLIST");
       $conexoesAbertas = mysql_num_rows($result);
       if ($conexoesAbertas > 10) {
           while ($row=mysql_fetch_array($result)) {
               $process_id=$row["Id"];
               if ($row["Time"] > 200 ) {
                   $sql="KILL $process_id";
                   mysql_query($sql);
               }
           }
       }*/

	  $this->executar("SET NAMES 'utf8';");

   }

   static public function __Abrir(){

       return new BDConexao;
	  
   }
   
   public function banco($banco){
      
	  mysql_select_db($banco, $this->conexao);
	  
   }
   
   public function showErros(){
	   
	   $this->erros = true;
	   
   }
   
   public function executar($query, $gravar = true){
      
	  $query = mysql_query($query, $this->conexao);
	  
      if(mysql_errno()) {
         throw new Exception('<strong>Falha com o Banco de Dados:</strong> '.mysql_error());
      }else{

         $this->query = $query;
	  
         return $this;

      }
   }
   
   public function getId(){
		
		return mysql_insert_id($this->conexao);
		
   }
   
   public function getTotal(){
      
	  return mysql_num_rows($this->query);
	  
   }
   
   public function registrosTotal(){
      
	  return mysql_num_rows($this->query);
	  
   }
   
   public function getRegistro(){
      
	  return mysql_fetch_array($this->query);
	  
   }   
     
   public function cadastrar($tabelas, $values){
     
	  $this->executar("INSERT INTO ".$tabelas." VALUES(".$values.")");
	  //return @mysql_insert_id($this->conexao);

   }
   
   public function alterar($tabela, $campo, $valor, $where = ''){
     
	  $this->executar("UPDATE ".$tabela." SET `".$campo."` = '".$valor."' ".$where);

   }
   
   public function consultar($tabelas, $where = ''){
     
	  $this->executar("SELECT * FROM ".$tabelas." ".$where, true);

   }
   
   public function deletar($tabela, $where){
     
	  $this->executar("DELETE FROM ".$tabela." ".$where);

   }
   
   public function close() {
       mysql_close($this->conexao);
   }
   
}

?>