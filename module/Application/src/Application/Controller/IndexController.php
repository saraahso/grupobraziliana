<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2015 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\View\Model\JsonModel;
use Zend\Mail;

class IndexController extends AbstractActionController
{
  public function indexAction()
  {
    return new ViewModel();
  }

  public function contatoAction()
  {
    $this->getRequest()->isPost();
    if ($this->getRequest()->isPost()) {
      try {
        $mail = new Mail\Message();
        $mail->setBody($this->getRequest()->getPost()->get('message'));
        $mail->setFrom($this->getRequest()->getPost()->get('from'), $this->getRequest()->getPost()->get('nome'));
        $mail->addTo('marktronic@outlook.com');
        $mail->setSubject('Mensagem pelo site');

        $transport = new Mail\Transport\Sendmail();
        $transport->send($mail);
        $message = 'Mensagem enviada com sucesso';
        $sucess = true;
      } catch (\Exception $e) {
        $message = 'Problema encontrado, tente mais tarde';
        $sucess = false;
      }
      return new JsonModel(['message' => $message, 'success' => $sucess]);
    } else
      return new ViewModel();
  }
}
