<?php

//Passagem de dados para a conexao ao Banco de Dados

importar('Utils.BD.BDConexao');

BDConexao::$host                = Sistema::$BDHost;
BDConexao::$banco               = Sistema::$BDBanco;
BDConexao::$usuario             = Sistema::$BDUsuario;
BDConexao::$senha               = Sistema::$BDSenha;
//BDConexao::$staticConnection    = mysql_pconnect(BDConexao::$host, BDConexao::$usuario, BDConexao::$senha);