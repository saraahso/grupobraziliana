<?php

namespace TemTudoAqui\Service;

use Doctrine\ORM\EntityManager,
    Zend\Stdlib\Hydrator;

class URL extends AbstractService
{

    protected $transport;
    protected $view;

    public function __construct(EntityManager $em)
    {
        parent::__construct($em);

        $this->entity = 'TemTudoAqui\URL';
    }

    public function insert(array $data)
    {
        $entity = parent::insert($data);
        if($entity){
            return $entity;
        }
    }

    public function update(array $data)
    {
        $entity = $this->em->getReference($this->entity, $data['id']);

        (new Hydrator\ClassMethods())->hydrate($data, $entity);

        $this->em->persist($entity);
        $this->em->flush();
        return $entity;
    }

}