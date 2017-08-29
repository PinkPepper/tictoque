<?php

namespace AppBundle\Entity;

use AppBundle\Entity\Traits\TraitUploadImage;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Produit
 *
 * @ORM\Table(name="produit")
 * @ORM\HasLifecycleCallbacks()
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ProduitRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class Produit
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
     * @ORM\Column(name="nom", type="string", length=255)
     * @Assert\NotBlank()
     */
    private $nom;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="text")
     * @Assert\NotBlank()
     */
    private $description;

    /**
     * @var string
     * @ORM\Column(name="type", type="string", length=255, nullable=true)
     */
    private $type;


    /**
     * @var int
     *
     * @ORM\Column(name="prix", type="float")
     * @Assert\NotBlank()
     * @Assert\GreaterThan(2.99)
     */
    private $prix;

    /**
     * @var int
     *
     * @ORM\Column(name="prix_gastronomique", type="float")
     */
    private $prixGastronomique;

    /**
     * @var int
     *
     * @ORM\Column(name="quantite", type="integer")
     */
    private $quantite;

    /**
     * @var string
     *
     * @ORM\Column(name="image", type="string", length=255, nullable=true)
     */
    private $image;

    /**
     * @ORM\ManyToMany(targetEntity="AppBundle\Entity\Categorie", inversedBy="produits", cascade={"persist"})
     */
    private $categories;
    private $cat;

    /**
     * @ORM\ManyToMany(targetEntity="AppBundle\Entity\Allergene", inversedBy="produits", cascade={"persist"})
     */
    private $allergenes;
    private $all;

    /**
     * @var int
     **@ORM\ManyToMany(targetEntity="AppBundle\Entity\PointRelais", inversedBy="produits", cascade={"persist"})
     */
    private $pointRelais;
    private $pr;

    /**
     * @ORM\OneToMany(targetEntity="CommandeProduit",  mappedBy="produits", cascade={"remove"})
     */
    private $commande;

    /**
     * @ORM\Column(name="notation", type="float", nullable=true)
     */
    private $notation;

    /**
     * @ORM\Column(name="nbVotants", type="integer", nullable=true)
     */
    private $nbVotants;

    /**
     * @ORM\Column(name="avis", type="text", nullable=true)
     */
    private $avis;

    /**
     * @var string
     */
    private  $avisForm;




    /**
     * @var int
     * @ORM\OneToMany(targetEntity="Livraison", mappedBy="produit", cascade={"persist"})
     */
    private $livraison;


    use TraitUploadImage;
    public function getUploadDir()
    {
        return 'produits';
    }

    /**
     * Add categorie
     *
     * @param \AppBundle\Entity\Categorie $categorie
     *
     * @return Produit
     */
    public function addCategorie(\AppBundle\Entity\Categorie $categorie)
    {
        $this->categories[] = $categorie;

        return $this;
    }

    /**
     * Remove categorie
     *
     * @param \AppBundle\Entity\Categorie $categorie
     */
    public function removeCategorie(\AppBundle\Entity\Categorie $categorie)
    {
        $this->categories->removeElement($categorie);
    }


    /**
     * Add allergene
     *
     * @param \AppBundle\Entity\Categorie $categorie
     *
     * @return Produit
     */
    public function addAllergene(\AppBundle\Entity\Allergene $allergene)
    {
        $this->allergenes[] = $allergene;

        return $this;
    }

    /**
     * Remove allergene
     *
     * @param \AppBundle\Entity\Categorie $categorie
     */
    public function removeAllergene(\AppBundle\Entity\Allergene $allergene)
    {
        $this->allergenes->removeElement($allergene);
    }


    /**
     * @param \AppBundle\Entity\PointRelais $pointRelais
     *
     * @return Produit
     */
    public function addPointRelais(\AppBundle\Entity\PointRelais $pointRelais)
    {
        $this->pointRelais[] = $pointRelais;

        return $this;
    }

    /**
     * @param \AppBundle\Entity\PointRelais $pointRelais
     */
    public function removePointRelais(\AppBundle\Entity\PointRelais $pointRelais)
    {
        $this->pointRelais->removeElement($pointRelais);
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
     * @return Produit
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

    /**
     * Set description
     *
     * @param string $description
     *
     * @return Produit
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set allergenes
     *
     * @param string $allergenes
     *
     * @return Produit
     */
    public function setAllergenes($allergenes)
    {
        $this->allergenes = $allergenes;

        return $this;
    }

    /**
     * Get allergenes
     *
     * @return mixed
     */
    public function getAllergenes()
    {
        return $this->allergenes;
    }

    /**
     * Set datePeremption
     *
     * @param \DateTime $datePeremption
     *
     * @return Produit
     */
    public function setDatePeremption($datePeremption)
    {
        $this->datePeremption = $datePeremption;

        return $this;
    }

    /**
     * Get datePeremption
     *
     * @return \DateTime
     */
    public function getDatePeremption()
    {
        return $this->datePeremption;
    }

    /**
     * Set prix
     *
     * @param integer $prix
     *
     * @return Produit
     */
    public function setPrix($prix)
    {
        $this->prix = $prix;

        return $this;
    }

    /**
     * Get prix
     *
     * @return int
     */
    public function getPrix()
    {
        return $this->prix;
    }

    /**
     * Set quantite
     *
     * @param integer $quantite
     *
     * @return Produit
     */
    public function setQuantite($quantite)
    {
        $this->quantite = $quantite;

        return $this;
    }

    /**
     * Get quantite
     *
     * @return int
     */
    public function getQuantite()
    {
        return $this->quantite;
    }

    /**
     * Set image
     *
     * @param string $image
     *
     * @return Produit
     */
    public function setImage($image)
    {
        $this->image = $image;

        return $this;
    }

    /**
     * Get image
     *
     * @return string
     */
    public function getImage()
    {
        return $this->image;
    }

    /**
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param string $type
     */
    public function setType($type)
    {
        $this->type = $type;
    }

    /**
     * @return mixed
     */
    public function getCategories()
    {
        return $this->categories;
    }

    /**
     * @param mixed $categories
     */
    public function setCategories($categories)
    {
        $this->categories = $categories;
    }

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->categories = new \Doctrine\Common\Collections\ArrayCollection();
        $this->allergenes = new \Doctrine\Common\Collections\ArrayCollection();
        $this->pointRelais = new \Doctrine\Common\Collections\ArrayCollection();
        $this->prixGastronomique = 0;
        $this->quantite = 0;
    }

    /**
     * @return mixed
     */
    public function getCat()
    {
        return $this->cat;
    }

    /**
     * @param mixed $cat
     */
    public function setCat($cat)
    {
        $this->cat = $cat;
    }

    /**
     * @return mixed
     */
    public function getAll()
    {
        return $this->all;
    }

    /**
     * @param mixed $all
     */
    public function setAll($all)
    {
        $this->all = $all;
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
     * @param mixed $menu_boisson
     */
    public function setMenuBoisson($menu_boisson)
    {
        $this->menu_boisson = $menu_boisson;
    }

    public function __toString()
    {
       return "" . $this->id; //todo essayer avec $this->nom
    }

    /**
     * @return mixed
     */
    public function getAvis()
    {
        return $this->avis;
    }

    /**
     * @param mixed $avis
     */
    public function setAvis($avis)
    {
        $this->avis = $avis;
    }

    /**
     * @return mixed
     */
    public function getNotation()
    {
        return $this->notation;
    }

    /**
     * @param mixed $notation
     */
    public function setNotation($notation)
    {
        $this->notation = $notation;
    }

    /**
     * @return mixed
     */
    public function getNbVotants()
    {
        return $this->nbVotants;
    }

    /**
     * @param mixed $nbVotants
     */
    public function setNbVotants($nbVotants)
    {
        $this->nbVotants = $nbVotants;
    }

    /**
     * @return mixed
     */
    public function getAvisForm()
    {
        return $this->avisForm;
    }

    /**
     * @param mixed $avisForm
     */
    public function setAvisForm($avisForm)
    {
        $this->avisForm = $avisForm;
    }

    /**
     * @return int
     */
    public function getPointRelais()
    {
        return $this->pointRelais;
    }

    /**
     * @param int $pointRelais
     */
    public function setPointRelais($pointRelais)
    {
        $this->pointRelais = $pointRelais;
    }

    /**
     * @return int
     */
    public function getLivraison()
    {
        return $this->livraison;
    }

    /**
     * @param int $livraison
     */
    public function setLivraison($livraison)
    {
        $this->livraison = $livraison;
    }

    /**
     * @return mixed
     */
    public function getPr()
    {
        return $this->pr;
    }

    /**
     * @param mixed $pr
     */
    public function setPr($pr)
    {
        $this->pr = $pr;
    }

    /**
     * @return int
     */
    public function getPrixGastronomique()
    {
        return $this->prixGastronomique;
    }

    /**
     * @param int $prixGastronomique
     */
    public function setPrixGastronomique($prixGastronomique)
    {
        $this->prixGastronomique = $prixGastronomique;
    }


}

