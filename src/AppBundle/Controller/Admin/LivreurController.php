<?php

namespace AppBundle\Controller\Admin;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use AppBundle\Entity\User;
use AppBundle\Form\UserType;
use Symfony\Component\HttpFoundation\Response;

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
        $produit = [];

        $em = $this->getDoctrine()->getManager();

        $usr = $this->getUser();

        $pointRelais = $em->getRepository('AppBundle:PointRelais')->findBy(
            array('user' => $usr)
        );

        for($i=0;$i<sizeof($pointRelais);$i++){
            $tmp = $em->getRepository('AppBundle:Livraison')->findBy(
                array('pointRelais' => $pointRelais[$i]->getId())
            );
            array_push($produit,$tmp);
        }


        return $this->render('backoffice/admin/livreur/index.html.twig', array(
            'relais' => $pointRelais,
            'produits' => $produit,
            'dateNow' => new \DateTime()
        ));

    }

    /**
     *
     * @Route("/confirmationLivreur", name="admin_livreur_validation")
     * @Method("POST")
     */
    public function livraisonAction(Request $request)
    {
        $id = $request->get('id');

        $em = $this->getDoctrine()->getManager();

        $livraison = $em->getRepository('AppBundle:Livraison')->find($id);

        $livraison

        $response = new Response();
        $response->setStatusCode(200);

        return $response;

    }
}
