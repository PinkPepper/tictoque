<?php

namespace AppBundle\Controller;

use AppBundle\Entity\PointRelais;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Pointrelais controller.
 *
 * @Route("/pointrelais")
 */
class PointRelaisController extends Controller
{
    /**
     * @Route("/", name="point_relais_index")
     */
    public function indexAction(Request $request)
    {
        return $this->render('frontoffice/default/pointsRelais.html.twig');
    }


    /**
     * @Route("/results/{adresse}", name="point_relais_results")
     */
    public function resultAction($adresse)
    {
        $geocoder = $this->container->get('app.geocoder_service');
        $pointsRelais = $geocoder->getPointsRelais($adresse);

        return $this->render('frontoffice/default/results.html.twig', array(
            'pointsRelais'=>$pointsRelais
        ));
    }


    /**
     * @Route("/set/index", name="point_relais_set_index")
     */
    public function setPointRelaisIndexAction()
    {
        return $this->render('frontoffice/default/setPointRelais.html.twig');
    }

    /**
     * @Route("/results/set/{adresse}", name="point_relais_results_set")
     */
    public function resultSetAction($adresse)
    {
        $geocoder = $this->container->get('app.geocoder_service');
        $pointsRelais = $geocoder->getPointsRelais($adresse);

        return $this->render('frontoffice/default/setPointRelais_results.html.twig', array(
            'pointsRelais'=>$pointsRelais
        ));
    }

    /**
     * @Route("/set", name="point_relais_set")
     * @Method("POST")
     */
    public function setPointRelaisAction(Request $request)
    {
        $id = $request->get('pointRelais', null);
        $response = new Response();

        if($id !== null)
        {
            $session = $request->getSession();
            $session->set("pointRelais", $id);
        }
        else{
            $response->setStatusCode(502);
        }

        return $response;
    }
}
