<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * CommandeMenu
 *
 * @ORM\Table(name="commande_menu")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\CommandeMenuRepository")
 */
class CommandeMenu
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
     * @ORM\ManyToOne(targetEntity="Commande", inversedBy="commandesMenu")
     * @ORM\JoinColumn(name="commande", referencedColumnName="id")
     */
    private $commande;

    /**
     * @ORM\ManyToOne(targetEntity="Menu", inversedBy="commande")
     * @ORM\JoinColumn(name="menus", referencedColumnName="id")
     */
    private $menus;

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
    public function getMenus()
    {
        return $this->menus;
    }

    /**
     * @param mixed $menus
     */
    public function setMenus($menus)
    {
        $this->menus = $menus;
    }
}

