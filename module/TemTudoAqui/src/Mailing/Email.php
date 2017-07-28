<?php

namespace TemTudoAqui\Mailing;

use Doctrine\ORM\Mapping as ORM,
    Zend\Stdlib\Hydrator;

/**
 * URL
 *
 * @ORM\Table(name="tta_mailing_pacotes_emails")
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks
 * @ORM\Entity(repositoryClass="TemTudoAqui\Repository\EntityRepository")
 */
class Email extends \TemTudoAqui\AbstractEntity {

    /**
     * @var integer
     *
     * @ORM\ManyToOne(targetEntity="Pacote", inversedBy="emails")
     * @ORM\JoinColumn(name="pacote", referencedColumnName="id")
     * @ORM\Id
     */
    private $pacote;

    /**
     * @var string
     *
     * @ORM\Column(name="email", length=150, nullable=false)
     * @ORM\Id
     */
    private $email;

    /**
     * @var string
     *
     * @ORM\Column(name="nome", length=150, nullable=false)
     */
    private $nome;

    /**
     * @var string
     *
     * @ORM\Column(name="cidade", length=150, nullable=false)
     */
    private $cidade;

    /**
     * @var string
     *
     * @ORM\Column(name="estado", length=150, nullable=false)
     */
    private $estado;

    /**
     * @var string
     *
     * @ORM\Column(name="area", length=150, nullable=false)
     */
    private $area;

    /**
     * @var string
     *
     * @ORM\Column(name="datanasc", length=15, nullable=false)
     */
    private $datanasc;

    /**
     * @return int
     */
    public function getPacote()
    {
        return $this->pacote;
    }

    /**
     * @param int $pacote
     */
    public function setPacote($pacote)
    {
        $this->pacote = $pacote;
    }

    /**
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param string $email
     */
    public function setEmail($email)
    {
        $this->email = $email;
    }

    /**
     * @return string
     */
    public function getNome()
    {
        return $this->nome;
    }

    /**
     * @param string $nome
     */
    public function setNome($nome)
    {
        $this->nome = $nome;
    }

    /**
     * @return string
     */
    public function getCidade()
    {
        return $this->cidade;
    }

    /**
     * @param string $cidade
     */
    public function setCidade($cidade)
    {
        $this->cidade = $cidade;
    }

    /**
     * @return string
     */
    public function getEstado()
    {
        return $this->estado;
    }

    /**
     * @param string $estado
     */
    public function setEstado($estado)
    {
        $this->estado = $estado;
    }

    /**
     * @return string
     */
    public function getArea()
    {
        return $this->area;
    }

    /**
     * @param string $area
     */
    public function setArea($area)
    {
        $this->area = $area;
    }

    /**
     * @return string
     */
    public function getDatanasc()
    {
        return $this->datanasc;
    }

    /**
     * @param string $datanasc
     */
    public function setDatanasc($datanasc)
    {
        $this->datanasc = $datanasc;
    }

}