<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * CommandeMenu
 *
 * @ORM\Table(name="produit_homepage")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\CommandeMenuRepository")
 */
class ProduitHomePage
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column(name="produit1_id", type="integer")
     * @Assert\NotBlank()
     */
    private $produit1;

    /**
     * @ORM\Column(name="produit2_id", type="integer")
     * @Assert\NotBlank()
     */
    private $produit2;

    /**
     * @ORM\Column(name="produit3_id", type="integer")
     * @Assert\NotBlank()
     */
    private $produit3;

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
    }

    /**
     * @return mixed
     */
    public function getProduit1()
    {
        return $this->produit1;
    }

    /**
     * @param mixed $produit1
     */
    public function setProduit1($produit1)
    {
        $this->produit1 = $produit1;
    }

    /**
     * @return mixed
     */
    public function getProduit2()
    {
        return $this->produit2;
    }

    /**
     * @param mixed $produit2
     */
    public function setProduit2($produit2)
    {
        $this->produit2 = $produit2;
    }

    /**
     * @return mixed
     */
    public function getProduit3()
    {
        return $this->produit3;
    }

    /**
     * @param mixed $produit3
     */
    public function setProduit3($produit3)
    {
        $this->produit3 = $produit3;
    }
}

