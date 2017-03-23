<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * CommandeProduit
 *
 * @ORM\Table(name="commande_produit")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\CommandeProduitRepository")
 */
class CommandeProduit
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
     * @ORM\ManyToOne(targetEntity="Commande", inversedBy="commandesProduit")
     * @ORM\JoinColumn(name="commande", referencedColumnName="id")
     */
    private $commande;

    /**
     * @ORM\ManyToOne(targetEntity="Produit", inversedBy="commande")
     * @ORM\JoinColumn(name="produits", referencedColumnName="id")
     */
    private $produits;

    /**
     * @var string
     *
     * @ORM\Column(name="quantiteCommandee", type="string", length=255)
     */
    private $quantiteCommandee;

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
     * Set quantiteCommandee
     *
     * @param string $quantiteCommandee
     *
     * @return CommandeProduit
     */
    public function setQuantiteCommandee($quantiteCommandee)
    {
        $this->quantiteCommandee = $quantiteCommandee;

        return $this;
    }

    /**
     * Get quantiteCommandee
     *
     * @return string
     */
    public function getQuantiteCommandee()
    {
        return $this->quantiteCommandee;
    }

    /**
     * @return mixed
     */
    public function getCommande()
    {
        return $this->commande;
    }

    /**
     * @param mixed $commande
     */
    public function setCommande($commande)
    {
        $this->commande = $commande;
    }

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
}

