<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * Allergene
 *
 * @ORM\Table(name="allergene")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\AllergeneRepository")
 * @UniqueEntity(fields={"nom"})
 */
class Allergene
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
     * @var string
     *
     * @ORM\Column(name="nom", type="string", length=255, unique=true)
     */
    private $nom;

    /**
     *
     *  @ORM\ManyToMany(targetEntity="Produit", inversedBy="allergenes", cascade={"persist"})
     *  @ORM\JoinTable(name="RelationProduitAllergene",
     *  joinColumns={@ORM\JoinColumn(name="allergene_id", referencedColumnName="id")},
     *  inverseJoinColumns={@ORM\JoinColumn(name="produit_id", referencedColumnName="id")})
     */
    private $produits;


    /**
     *
     *  @ORM\ManyToMany(targetEntity="User", inversedBy="allergenes", cascade={"persist"})
     *  @ORM\JoinTable(name="RelationUserAllergene",
     *  joinColumns={@ORM\JoinColumn(name="allergene_id", referencedColumnName="id")},
     *  inverseJoinColumns={@ORM\JoinColumn(name="user_id", referencedColumnName="id")})
     */
    private $users;


    private $nomForm;

    /**
     * @return mixed
     */
    public function getProduits()
    {
        return $this->produits;
    }

    /**
     * @param mixed $produits
     */
    public function setProduits($produits)
    {
        $this->produits = $produits;
    }

    /**
     * Add produit
     *
     * @param \AppBundle\Entity\Produit produit
     *
     * @return Allergene
     */
    public function addProduit(\AppBundle\Entity\Produit $produit)
    {
        $this->produits[] = $produit;

        return $this;
    }

    /**
     * Remove produit
     *
     * @param \AppBundle\Entity\Produit $produit
     */
    public function removeProduit(\AppBundle\Entity\Produit $produit)
    {
        $this->produits->removeElement($produit);
    }


    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set nom
     *
     * @param string $nom
     *
     * @return Allergene
     */
    public function setNom($nom)
    {
        $this->nom = $nom;

        return $this;
    }

    /**
     * Get nom
     *
     * @return string
     */
    public function getNom()
    {
        return $this->nom;
    }

    public function __toString()
    {
        return '' . $this->id;
    }

    /**
     * @return mixed
     */
    public function getUsers()
    {
        return $this->users;
    }

    /**
     * @param mixed $users
     */
    public function setUsers($users)
    {
        $this->users = $users;
    }

    /**
     * @return mixed
     */
    public function getNomForm()
    {
        if($this->nom == "aucun")
        {
            return " ";
        }
        return $this->nom;
    }

}

