<?php

namespace AppBundle\Service;

use Symfony\Component\DependencyInjection\ContainerInterface;

class GeocodeService{

    private $container;
    
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function GetPointsRelais($adresse)
    {
        $coord = $this->adresseToCoord($adresse);
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
}