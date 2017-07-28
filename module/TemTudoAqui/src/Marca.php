<?php

namespace TemTudoAqui;

use Doctrine\ORM\Mapping as ORM,
  Zend\Stdlib\Hydrator;

/**
 * Marca
 *
 * @ORM\Table(name="tta_produtos_marcas")
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks
 * @ORM\Entity(repositoryClass="TemTudoAqui\Repository\MarcaRepository")
 */
class Marca extends AbstractEntity
{

  /**
   * @var integer
   *
   * @ORM\Column(name="id", type="integer", nullable=false)
   * @ORM\Id
   * @ORM\GeneratedValue(strategy="IDENTITY")
   */
  private $id;

  /**
   * @var \TemTudoAqui\URL
   *
   * @ORM\ManyToOne(targetEntity="URL")
   * @ORM\JoinColumn(name="url", referencedColumnName="id")
   */
  private $url;

  /**
   * @var string
   *
   * @ORM\Column(name="nome", length=50, nullable=false)
   */
  private $nome;

  /**
   * @var string
   *
   * @ORM\Column(name="enderecoURL", length=255, nullable=false)
   */
  private $enderecourl;

  /**
   * @var string
   *
   * @ORM\Column(name="descricao", nullable=false)
   */
  private $descricao;

  /**
   * @var string
   *
   * @ORM\Column(name="imagem", length=255)
   */
  private $imagem;

  /**
   * @var boolean
   *
   * @ORM\Column(name="disponivel", type="boolean", nullable=false)
   */
  private $disponivel;

  /**
   * @var \Doctrine\Common\Collections\ArrayCollection
   *
   * @ORM\OneToMany(targetEntity="Produto", mappedBy="marca")
   */
  protected $produtos;

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
   * @return \TemTudoAqui\URL
   */
  public function getUrl()
  {
    return $this->url;
  }

  /**
   * @param \TemTudoAqui\URL $url
   */
  public function setUrl(URL $url)
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
  public function getEnderecourl()
  {
    return $this->enderecourl;
  }

  /**
   * @param string $enderecoURL
   */
  public function setEnderecourl($enderecoURL)
  {
    $this->enderecourl = $enderecoURL;
    return $this;
  }

  /**
   * @return string
   */
  public function getDescricao()
  {
    return $this->descricao;
  }

  /**
   * @param string $descricao
   */
  public function setDescricao($descricao)
  {
    $this->descricao = $descricao;
    return $this;
  }

  /**
   * @return string
   */
  public function getImagem()
  {
    return $this->imagem;
  }

  /**
   * @param string $imagem
   */
  public function setImagem($imagem)
  {
    $this->imagem = $imagem;
    return $this;
  }

  /**
   * @return boolean
   */
  public function getDisponivel()
  {
    return $this->disponivel;
  }

  /**
   * @param boolean $disponivel
   */
  public function setDisponivel($disponivel)
  {
    $this->disponivel = $disponivel;
    return $this;
  }

  /**
   * @return \Doctrine\Common\Collections\ArrayCollection
   */
  public function getProdutos()
  {
    return $this->produtos;
  }

  public function toArray()
  {
    $data = parent::toArray();
    if ($this->url instanceof AbstractEntity)
      $data['url'] = $this->url->getId() > 0 ? $this->url->toArray() : null;

    return $data;
  }

}