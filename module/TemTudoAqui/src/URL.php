<?php

namespace TemTudoAqui;

use Doctrine\ORM\Mapping as ORM,
    Zend\Stdlib\Hydrator;

/**
 * URL
 *
 * @ORM\Table(name="tta_urls")
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks
 * @ORM\Entity(repositoryClass="TemTudoAqui\Repository\EntityRepository")
 */
class URL extends AbstractEntity {

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
     * @ORM\Column(name="tabela", length=50, nullable=false)
     */
    private $tabela;

    /**
     * @var string
     *
     * @ORM\Column(name="campo", length=50, nullable=false)
     */
    private $campo;

    /**
     * @var string
     *
     * @ORM\Column(name="valor", length=50, nullable=false)
     */
    private $valor;

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
    public function getTabela()
    {
        return $this->tabela;
    }

    /**
     * @param string $tabela
     */
    public function setTabela($tabela)
    {
        $this->tabela = $tabela;
        return $this;
    }

    /**
     * @return string
     */
    public function getCampo()
    {
        return $this->campo;
    }

    /**
     * @param string $campo
     */
    public function setCampo($campo)
    {
        $this->campo = $campo;
        return $this;
    }

    /**
     * @return string
     */
    public function getValor()
    {
        return $this->valor;
    }

    /**
     * @param string $valor
     */
    public function setValor($valor)
    {
        $this->valor = $valor;
        return $this;
    }

}