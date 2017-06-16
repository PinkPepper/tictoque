<?php

namespace AppBundle\Controller;

use AppBundle\Entity\PointRelais;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

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
     * @Route("/set", name="point_relais_set_index")
     */
    public function setPointRelaisIndexAction()
    {
        return $this->render('frontoffice/default/setPointsRelais.html.twig');
    }

    /**
     * @Route("/resultst/set/{adresse}", name="point_relais_results_set")
     */
    public function resultSetAction($adresse)
    {
        $geocoder = $this->container->get('app.geocoder_service');
        $pointsRelais = $geocoder->getPointsRelais($adresse);

        return $this->render('frontoffice/default/setPointRelais_results.html.twig', array(
            'pointsRelais'=>$pointsRelais
        ));
    }
}
