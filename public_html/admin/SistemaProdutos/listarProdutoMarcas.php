<?php

importar("LojaVirtual.Produtos.Lista.ListaProdutoMarcas");
importar("Utils.Dados.JSON");

$tituloPagina = 'Produtos > Marcas';

$iLPM = new IFAdmin(new Arquivos(Sistema::$adminLayoutCaminhoDiretorio . "/SistemaProdutos/listarProdutoMarcas.html"));

$iLPM->trocar("linkDeletar.ProdutoMarca", "?p=" . $_GET['p'] . "&a=" . $_GET['a'] . "&");
$iLPM->trocar("linkBuscar.ProdutoMarca", "?p=" . $_GET['p'] . "&");

if (!empty($_GET['deletar'])) {

  $lPM = new ListaProdutoMarcas;
  $lPM->condicoes('', $_GET['deletar'], ListaProdutoMarcas::ID);

  if ($lPM->getTotal() > 0) {

    try {
      $lPM->deletar($lPM->listar());
      $javaScript .= Aviso::criar("Marca removida com sucesso!");
    } catch (Exception $e) {

      $javaScript .= Aviso::criar($e->getMessage());

    }


  }

}

$lPM = new ListaProdutoMarcas;
$iLPM->createRepeticao("repetir->ProdutoMarcas");

if (!empty($_GET['busca']))
  $lPM->condicoes('', "%" . $_GET['busca'] . "%", 'empresa', 'LIKE');

$iLPM->condicao("condicaoBusca", !empty($_SESSION['nivel']));

if (isset($_GET['json'])) {

  $cond = array();

  while ($pM = $lPM->listar()) {
    $rs['id'] = $pM->getId();
    $rs['nome'] = $pM->nome;
    $cond[] = $rs;
  }

  echo JSON::_Encode($cond);
  exit;

}

$iLPM->trocar("linkCadastrar.ProdutoMarca", "?p=" . $_GET['p'] . "&a=cadastrarProdutoMarca");

while ($pM = $lPM->listar("ASC", ListaProdutoMarcas::NOME)) {


  if (!empty($_POST['desabilitar'])) {

    //Desabilitar
    if ($_POST['desabilitar'][$pM->getId()])
      $pM->disponivel = true;
    else
      $pM->disponivel = false;
    //

    $lPM->alterar($pM);

  }

  $iLPM->repetir();

  $iLPM->enterRepeticao()->condicao("condicaoRemover", !empty($_SESSION['nivel']));

  $bgColor = $lPM->getParametros() % 2 == 0 ? '#FFFFFF' : '#EAEAEA';
  $iLPM->enterRepeticao()->trocar("bgColorEmpresa", $bgColor);

  $iLPM->enterRepeticao()->trocar("id.ProdutoMarca", $pM->getId());
  $iLPM->enterRepeticao()->trocar("nome.ProdutoMarca", $pM->nome);
  $iLPM->enterRepeticao()->trocar("linkAlterar.ProdutoMarca", "?p=" . $_GET['p'] . "&a=alterarProdutoMarca&marca=" . $pM->getId());
  $iLPM->enterRepeticao()->trocar("disponivel.ProdutoMarca", $pM->disponivel ? 'checked' : '');
  $iLPM->enterRepeticao()->trocar("bg.Disponivel.Marca", !$pM->disponivel ? '#FF0000' : '');

  $iLPM->enterRepeticao()->condicao("condicaoVisualizar", $pM->tipo == 1);

}

$iLPM->trocar("linkVoltar", "?p=" . $_GET['p'] . "&a=produtos");

$botoes = $iLPM->cutParte('botoes');

$includePagina = $iLPM->concluir();

?>