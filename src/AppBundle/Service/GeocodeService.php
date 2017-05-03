<?php

namespace AppBundle\Service;

use Symfony\Component\DependencyInjection\ContainerInterface;

class GeocodeService{

    private $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function getPointsRelais($adresse)
    {
        $coord = $this->adresseToCoord($adresse);
        $pointsFromBase = $this->getPointsRelaisFromBase();

        foreach ($pointsFromBase as $point)
        {
            //calcul de difference 30 km
        }
    }

    private function adresseToCoord($adresse)
    {
        $long = null;
        $latt = null;

        //appel à google api
        //script python
        // string exec ( string $command [, array &$output [, int &$return_var ]] )
        return array($long, $latt);
    }

    private function getPointsRelaisFromBase()
    {
        $em = $this->container->get("doctrine");
        return $em->getRepository('AppBundle:PointRelais')->findAll();
    }
}