<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints\DateTime;

/**
 * Commande
 *
 * @ORM\Table(name="commande")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\CommandeRepository")
 */
class Commande
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
     * @var \DateTime
     *
     * @ORM\Column(name="date", type="datetime")
     */
    private $date;

    /**
     * @ORM\ManyToOne(targetEntity="User", inversedBy="commandes")
     * @ORM\JoinColumn(name="user", referencedColumnName="id")
     */
    private $user;

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
}

