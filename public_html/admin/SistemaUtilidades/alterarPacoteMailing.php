<?php

importar("Utilidades.Publicidades.Mailing.Lista.ListaPacoteMailings");
importar("Utils.Excel.Excel");
importar("Utils.Dados.DataHora");

$tituloPagina = 'Utilidades > Publicidades > Mailings > Pacotes > Alterar';

$iTAPM = new IFAdmin(new Arquivos(Sistema::$adminLayoutCaminhoDiretorio."/SistemaUtilidades/pacoteMailing.html"));

if(isset($_GET['exportarTXT'])){
	$lS = new ListaPacoteMailings;
	$lS->condicoes('', $_GET['pacote'], ListaPacoteMailings::ID);
	$s = $lS->listar();
	
	header('Content-type: text/plain');
	header('Content-disposition: attachment; filename="lista-'.$s->titulo.'-'.Sistema::$nomeEmpresa.'.txt";');
	
	while($e = $s->getEmails()->listar()){
		echo $e['email']." \r\n";
		
	}
	
	exit;
	
}

if(!empty($_POST)){
	
	$erro = '';	
	
    if(empty($_POST['titulo']))
	    $erro = "<b>Titulo</b> não preenchido!<br><br>";

	if(empty($erro)){
		
		try{
		
			$lS = new ListaPacoteMailings;
			$lS->condicoes('', $_GET['pacote'], ListaPacoteMailings::ID);
			$s = $lS->listar();
			
			$s->titulo 		= $_POST['titulo'];	
			
			$lS->alterar($s);
					
			$con = BDConexao::__Abrir();
			
			if(!empty($_POST['email'])){
				
				$con->executar("INSERT INTO ".Sistema::$BDPrefixo."mailing_pacotes_emails(pacote, email, nome, cidade, estado, datanasc) VALUES('".$_GET['pacote']."','".$_POST['email']."','".$_POST['nome']."','".$_POST['cidade']."','".$_POST['uf']."','".DataHora::__Create($_POST['datanasc'])->mostrar("Ymd")."')");
				
			}
			
			if(!empty($_FILES['arquivo']['name'])){
				
				if(eregi("xls", $_FILES['arquivo']['name'])){
				
					$data = new Excel;
					$data->setOutputEncoding('CPa25a');
					$data->read($_FILES['arquivo']['tmp_name']);
					
					for($i = 1; $i <= $data->sheets[0]['numRows']; $i++){
					
						$con->executar("SELECT * FROM ".Sistema::$BDPrefixo."mailing_pacotes_emails WHERE pacote = '".$_GET['pacote']."' AND email = '".$data->sheets[0]['cells'][$i][1]."'");
						if($con->getTotal() == 0)
							$con->executar("INSERT INTO ".Sistema::$BDPrefixo."mailing_pacotes_emails(pacote, email) VALUES('".$_GET['pacote']."','".$data->sheets[0]['cells'][$i][1]."')");
							
					}
				
				}elseif(eregi("txt", $_FILES['arquivo']['name'])){
					
					$f = file($_FILES['arquivo']['tmp_name']);
					for($i = 0; $i < count($f); $i++){
					 
						$con->executar("SELECT * FROM ".Sistema::$BDPrefixo."mailing_pacotes_emails WHERE pacote = '".$_GET['pacote']."' AND email = '".$f[$i]."'");
						if($con->getTotal() == 0)
							$con->executar("INSERT INTO ".Sistema::$BDPrefixo."mailing_pacotes_emails(pacote, email) VALUES('".$_GET['pacote']."','".$f[$i]."')");
					
					}
				}
				
			}
			
			$javaScript .= Aviso::criar("Pacote salvo com sucesso!");
			
		}catch(Exception $e){
			
			$javaScript .= Aviso::criar($e->getMessage());
			
		}
	
	}else{
		
		$javaScript .= Aviso::criar($erro);
		
	}
	
}

if(!empty($_GET['deletar'])){
	
	$con = BDConexao::__Abrir();
	$con->executar("DELETE FROM ".Sistema::$BDPrefixo."mailing_pacotes_emails WHERE email = '".$_GET['deletar']."'");
	$javaScript .= Aviso::criar("E-mail deletado com sucesso!");
	
}

$lS = new ListaPacoteMailings;
$s = $lS->condicoes('', $_GET['pacote'], ListaPacoteMailings::ID)->listar();

$iTAPM->condicao('condicao->alterar.Mailing', false);

$iTAPM->trocar("linkDeletar.PacoteMailing", "?p=".$_GET['p']."&a=alterarPacoteMailing&pacote=".$s->getId()."&");
$iTAPM->trocar("linkVoltar", "?p=".$_GET['p']."&a=listarPacoteMailings");
$iTAPM->trocar("linkExportarTXT", "?p=".$_GET['p']."&a=alterarPacoteMailing&pacote=".$s->getId()."&exportarTXT");

$iTAPM->trocar("titulo", 			$s->titulo);

$iTAPM->createRepeticao("repetir->Emails");
while($e = $s->getEmails()->listar()){
	
	$iTAPM->repetir();
	
	if($s->getEmails()->getParametros()%2 != 0)
		$iTAPM->enterRepeticao()->trocar('bgcolor', '#e5e5e5');
	
	$iTAPM->enterRepeticao()->trocar("email.Email", $e['email']);
	$iTAPM->enterRepeticao()->trocar("nome.Email", $e['nome']);
	
}

$iTAPM->createJavaScript();
$javaScript .= $iTAPM->javaScript->concluir();

$includePagina = $iTAPM->concluir();

?>