<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints\DateTime;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * Commande
 *
 * @ORM\Table(name="commande")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\CommandeRepository")
 * @UniqueEntity("identifiant")
 */
class Commande
{
    /**
     * @var int
     *
     * @ORM\Column(name="id")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="UUID")
     */
    private $id;

    /**
     * @var string
     * @ORM\Column(name="identifiant", type="string", length=255, nullable=true)
     */
    private $identifiant;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date", type="datetime")
     */
    private $date;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_livraison", type="datetime")
     */
    private $dateLivraison;

    /**
     * @ORM\ManyToOne(targetEntity="User", inversedBy="commandes")
     * @ORM\JoinColumn(name="user", referencedColumnName="id")
     */
    private $user;

    /**
     * @ORM\Column(name="prix", type="float", nullable=true)
     **/
    private $prix;

    /**
     * @ORM\Column(name="adresse", type="string", length=255, nullable=true)
     **/
    private $adresse;

    /**
     * @ORM\OneToMany(targetEntity="CommandeProduit",  mappedBy="commande", cascade={"remove"})
     */
    private $commandesProduit;

    /**
     * @ORM\OneToMany(targetEntity="CommandeMenu",  mappedBy="commande", cascade={"remove"})
     */
    private $commandesMenu;

    public function __construct()
    {
        $this->date = new \DateTime();
        $this->setIdentifiant();
    }

    private function setIdentifiant()
    {
        $chaine='0123456789abcdefghijklmnopqrstuvwxyz0123456789ABCDEFGHIJKLMNOPQRSTUVWYXZ-';
        $melange = str_shuffle($chaine);
        $this->identifiant = str_shuffle(substr($melange, 0, 15));
    }

    public function getIdentifiant(){
        return $this->identifiant;
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
     * Set date
     *
     * @param \DateTime $date
     *
     * @return Commande
     */
    public function setDate($date)
    {
        $this->date = $date;

        return $this;
    }

    /**
     * Get date
     *
     * @return \DateTime
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * Set user
     *
     * @param integer $user
     *
     * @return Commande
     */
    public function setUser($user)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return int
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @return mixed
     */
    public function getCommandesProduit()
    {
        return $this->commandesProduit;
    }

    /**
     * @param mixed $commandesProduit
     */
    public function setCommandesProduit($commandesProduit)
    {
        $this->commandesProduit = $commandesProduit;
    }

    /**
     * @param mixed $commandesMenu
     */
    public function setCommandesMenu($commandesMenu)
    {
        $this->commandesMenu = $commandesMenu;
    }

    /**
     * @return mixed
     */
    public function getCommandesMenu()
    {
        return $this->commandesMenu;
    }

    /**
     * @return mixed
     */
    public function getPrix()
    {
        return $this->prix;
    }

    /**
     * @param mixed $prix
     */
    public function setPrix($prix)
    {
        $this->prix = $prix;
    }

    /**
     * @return mixed
     */
    public function getAdresse()
    {
        return $this->adresse;
    }

    /**
     * @param mixed $adresse
     */
    public function setAdresse($adresse)
    {
        $this->adresse = $adresse;
    }

    /**
     * @return mixed
     */
    public function getDateLivraison()
    {
        return $this->dateLivraison;
    }

    /**
     * @param mixed $dateLivraison
     */
    public function setDateLivraison($dateLivraison)
    {
        $this->dateLivraison = $dateLivraison;
    }
}

