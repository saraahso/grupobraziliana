<?php

namespace Application\Dao;

use Doctrine\ORM\EntityManager;

class SiteDao
{

    private $em;

    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }

    public function getProdutoConfig()
    {
        $dbal   = $this->em->getConnection();
        $query  = $dbal->createQueryBuilder()
                    ->from('tta_produtos_configuracoes', 'pc')
                    ->select('pc.*');
        return $query->execute()->fetchAll();
    }

}