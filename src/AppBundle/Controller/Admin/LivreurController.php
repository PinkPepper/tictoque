<?php

namespace AppBundle\Controller\Admin;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use AppBundle\Entity\User;
use AppBundle\Form\UserType;

/**
 * Livreur controller.
 *
 * @Route("/admin/livreur")
 */
class LivreurController extends Controller
{
    /**
     *
     * @Route("/", name="admin_livreur_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        return $this->render('backoffice/admin/livreur/index.html.twig');
    }
}
