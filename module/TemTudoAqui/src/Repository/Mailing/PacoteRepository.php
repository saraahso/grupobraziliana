<?php

namespace TemTudoAqui\Repository\Mailing;

use Doctrine\ORM,
		TemTudoAqui\Mailing\Pacote,
		TemTudoAqui\Mailing\Email;

class PacoteRepository extends \TemTudoAqui\Repository\EntityRepository
{
	
	public function existsEmail(Pacote $pacote, $email)
	{
		$emailRepo	= $this->getEntityManager()->getRepository('TemTudoAqui\Mailing\Email');
    $ar 				= $emailRepo->findBy([
      'pacote'  => $pacote->getId(),
      'email'   => $email
    ]);
    return count($ar) > 0 ? true : false;
	}
	
}