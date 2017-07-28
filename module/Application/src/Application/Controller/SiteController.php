<?php

namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController,
  Zend\View\Model\JsonModel,
  TemTudoAqui\Produto,
  TemTudoAqui\Categoria,
  TemTudoAqui\Marca,
  TemTudoAqui\Mailing\Email;

use Doctrine\ORM\Mapping\ClassMetadata,
  Doctrine\Common\Util\Inflector;

class SiteController extends AbstractActionController
{

  public function generalAction()
  {

    $em = $this->getServiceLocator()->get('Doctrine\ORM\EntityManager');
    $repoCatSlide = $em->getRepository('TemTudoAqui\Slide\Categoria');
    $repoCatProd  = $em->getRepository('TemTudoAqui\Categoria');
    $repoMarcas   = $em->getRepository('TemTudoAqui\Marca');
    $siteDao = $this->getServiceLocator()->get('SiteDao');
    $categoriaSlide = $repoCatSlide->find(1);

    $rs = [];
    $rs['cambio']['real'] = $siteDao->getProdutoConfig()[0]['cambioreal'];
    $rs['produtosporpagina'] = $siteDao->getProdutoConfig()[0]['produtosporpagina'];

    foreach ($categoriaSlide->getSlides() as $k => $v) {
      $rs['slides'][$k] = $v->toArray();
    }

    $categorias = $repoCatProd->getCollection(new Categoria(['categoriapai' => new Categoria(['id' => 0])]), [
      'order' => [
        'id' => 'ASC'
      ]
    ]);
    \reset($categorias);
    foreach($categorias['result'] as $k => $cat){
      $cat['categorias'] = $repoCatProd->getCollection(new Categoria(['categoriapai' => new Categoria(['id' => $cat['id']])]))['result'];
      $rs['categorias'][] = $cat;
    }
    
    $rs['marcas'] = $repoMarcas->getCollection(new Marca)['result'];
    
    return new JsonModel($rs);
  }

  public function cambioAction()
  {

    $siteDao = $this->getServiceLocator()->get('SiteDao');

    $rs = [];
    $rs['cambio']['real'] = $siteDao->getProdutoConfig()[0]['cambioreal'];

    return new JsonModel($rs);
  }

  public function homeAction()
  {
    $em = $this->getServiceLocator()->get('Doctrine\ORM\EntityManager');
    $repoCategoria  = $em->getRepository('TemTudoAqui\Categoria');
    $repoProduto    = $em->getRepository('TemTudoAqui\Produto');
    $data = [];
    $data['categorias'] = [];
    $data['banners'] = [];

    $categorias = $repoCategoria->getCollection(new Categoria(['home' => true]), [
      'order' => [
        'ordem' => 'ASC'
      ]
    ])['result'];
    foreach($categorias as $cat){
      $cat['categorias']    = $repoCategoria->getCollection(new Categoria(['categoriapai' => new Categoria(['id' => $cat['id']])]))['result'];
      $secao['categoria']   = $cat;
      $secao['produtos']    = $repoProduto->getCollectionWithCategoria($repoCategoria->find($cat['id']), true, new Produto, [
        'limit' => 5,
        'order' => [
          'id' => 'DESC'
        ]
      ])['result'];
      
      $data['categorias'][] = $secao;
    }
    
    return new JsonModel($data);
  }

  public function addNewsletterAction()
  {
    $message = '';
    $success = false;

    if($this->getRequest()->isPost()){

      try {
        $em   = $this->getServiceLocator()->get('Doctrine\ORM\EntityManager');
        $pacR = $em->getRepository('TemTudoAqui\Mailing\Pacote');
        if(!$pacR->existsEmail($em->getReference('TemTudoAqui\Mailing\Pacote', 1), $this->getRequest()->getPost()->get('email'))){
          $email = new Email;
          $email->setPacote($em->getReference('TemTudoAqui\Mailing\Pacote', 1));
          $email->setEmail($this->getRequest()->getPost()->get('email'));
          $em->persist($email);
          $em->flush();
          $message = 'E-mail cadastrado com sucesso!';
          $success = true;
        }else{
          $message = 'E-mail já cadastrado!';
        }
      }catch(\Exception $e){
        $message = $e->getMessage();
      }

    }

    return new JsonModel(['message' => $message, 'success' => $success]);
  }

  public function clearCacheAction()
  {
    $this->getServiceLocator()->get('CacheListener')->getCacheService()->flush();
    return new JsonModel(['success' => true]);
  }

  public function blackFridayAction()
  {
    try {
      $dbal = $this->getServiceLocator()->get('doctrine.connection.orm_default');
      $post = json_decode($this->getRequest()->getContent(), true);
      if ($this->getRequest()->isPost()) {
        $stmt = $dbal->query("SELECT * FROM tta_mailing_pacotes_emails WHERE pacote = 2 AND email = '{$post['email']}'");
        if ($stmt->rowCount() === 0) {
          $stmt = $dbal->query("INSERT INTO tta_mailing_pacotes_emails (pacote, email, nome, cidade, area) VALUES(2, '{$post['email']}', '{$post['nome']}', '{$post['cidade']}', '{$post['whatsapp']}')");
          $stmt->execute();
        }
      }
      return new JsonModel(['success' => true]);
    } catch (\Exception $e) {
      return new JsonModel(['success' => false, 'message' => $e->getMessage()]);
    }

  }

}
