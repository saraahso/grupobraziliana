<?php

importar("Geral.Idiomas.Lista.ListaIdiomas");

$tituloPagina = 'Configurções > Idiomas > Cadastrar Idioma';

$iCI = new IFAdmin(new Arquivos(Sistema::$adminLayoutCaminhoDiretorio."/SistemaConfiguracoes/idioma.html"));

$iCI->trocar('linkVoltar', "?p=".$_GET['p']."&a=listarIdiomas");

if(!empty($_POST)){
	
	$erro = '';
	
	if(empty($_POST['nome']))
	    $erro = "<b>Nome</b> não preenchido!<br><br>";
	if(empty($_POST['sigla']))
	    $erro = "<b>Sigla</b> não preenchido!<br><br>";

	if(empty($erro)){
	
	    $idioma = new Idioma;
		
		$idioma->nome 	= $_POST['nome'];
		$idioma->sigla 	= $_POST['sigla'];
		
		if(!empty($_FILES['imagem']['name']))
			$idioma->setImagem(new Image(Arquivos::__OpenArquivoByTEMP($_FILES['imagem'])));
		
		$lI = new ListaIdiomas;
		$lI->inserir($idioma);
	    
		$pasta = dir(Sistema::$layoutCaminhoDiretorio);
		while($a = $pasta->read()){
			if(preg_match("!\.html!", $a)){
				
				$i = new InterFaces(new Arquivos(Sistema::$layoutCaminhoDiretorio.$a));
				$traducoes = $i->getListaTraducoes();
				
				foreach($traducoes as $v){
					if(empty($idioma->getTraducaoByConteudo($v, '', false)->traducao)){
						$t = new Traducao;
						$t->conteudo = $v;
						$t->traducao = $v;
						$idioma->addTraducao($t);
					}
				}
				
			}
		}
		
		$_POST = '';
		
	    $javaScript .= Aviso::criar("Idioma salvo com sucesso!");
	
	}else{
		
		$javaScript .= Aviso::criar($erro);
		
	}
	
}

$iCI->trocar('nome', 	$_POST['nome']);
$iCI->trocar('sigla', 	$_POST['sigla']);

$iCI->createJavaScript();
$javaScript .= $iCI->javaScript->concluir();

$includePagina = $iCI->concluir();

?>