<?php

namespace TemTudoAqui;

use Doctrine\ORM\Mapping as ORM,
    Zend\Stdlib\Hydrator;

/**
 * URL
 *
 * @ORM\Table(name="tta_vendedores")
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks
 * @ORM\Entity(repositoryClass="TemTudoAqui\Repository\EntityRepository")
 */
class Vendedor extends AbstractEntity {

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="url", length=255, nullable=false)
     */
    private $url;

    /**
     * @var string
     *
     * @ORM\Column(name="nome", length=255, nullable=false)
     */
    private $nome;

    /**
     * @var string
     *
     * @ORM\Column(name="email", length=255, nullable=false)
     */
    private $email;

    /**
     * @var string
     *
     * @ORM\Column(name="voip", length=255, nullable=false)
     */
    private $voip;

    /**
     * @var string
     *
     * @ORM\Column(name="skype", length=255, nullable=false)
     */
    private $skype;

    /**
     * @var string
     *
     * @ORM\Column(name="telefone", length=255, nullable=false)
     */
    private $telefone;

    /**
     * @var string
     *
     * @ORM\Column(name="ramal", length=255, nullable=false)
     */
    private $ramal;

    /**
     * @var int
     *
     * @ORM\Column(name="ordem", type="integer", nullable=false)
     */
    private $ordem;

    /**
     * @var \TemTudoAqui\Imagem
     *
     * @ORM\ManyToOne(targetEntity="Imagem")
     * @ORM\JoinColumn(name="imagem", referencedColumnName="id")
     */
    private $imagem;

    /**
     * @var string
     *
     * @ORM\Column(name="msn", nullable=false)
     */
    private $msn;

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * @param string $url
     */
    public function setUrl($url)
    {
        $this->url = $url;
        return $this;
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
        return $this;
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
        return $this;
    }

    /**
     * @return string
     */
    public function getVoip()
    {
        return $this->voip;
    }

    /**
     * @param string $voip
     */
    public function setVoip($voip)
    {
        $this->voip = $voip;
        return $this;
    }

    /**
     * @return string
     */
    public function getSkype()
    {
        return $this->skype;
    }

    /**
     * @param string $skype
     */
    public function setSkype($skype)
    {
        $this->skype = $skype;
        return $this;
    }

    /**
     * @return string
     */
    public function getTelefone()
    {
        return $this->telefone;
    }

    /**
     * @param string $telefone
     */
    public function setTelefone($telefone)
    {
        $this->telefone = $telefone;
        return $this;
    }

    /**
     * @return string
     */
    public function getRamal()
    {
        return $this->ramal;
    }

    /**
     * @param string $ramal
     */
    public function setRamal($ramal)
    {
        $this->ramal = $ramal;
        return $this;
    }

    /**
     * @return int
     */
    public function getOrdem()
    {
        return $this->ordem;
    }

    /**
     * @param int $ordem
     */
    public function setOrdem($ordem)
    {
        $this->ordem = $ordem;
        return $this;
    }

    /**
     * @return int
     */
    public function getImagem()
    {
        return $this->imagem;
    }

    /**
     * @param int $imagem
     */
    public function setImagem($imagem)
    {
        $this->imagem = $imagem;
        return $this;
    }

    /**
     * @return string
     */
    public function getMsn()
    {
        return $this->msn;
    }

    /**
     * @param string $msn
     */
    public function setMsn($msn)
    {
        $this->msn = $msn;
        return $this;
    }

}