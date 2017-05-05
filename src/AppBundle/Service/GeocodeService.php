<?php

namespace AppBundle\Service;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;

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

        $goodPoints = array();

        foreach ($pointsFromBase as $point)
        {
            //TODO calcul de difference 30 km
            if($this->calculDifférence($coord, array($point->getLatitude(), $point->getLongitude())) <=30)
            {
                array_push($goodPoints, $point);
            }
        }

        return $goodPoints;
    }

    public function adresseToCoord($adresse)
    {
        $command = 'curl http://api-adresse.data.gouv.fr/search/?q='.$adresse;
        $process = new Process($command);
        $process->run();

        /*if (!$process->isSuccessful()) {
            throw new ProcessFailedException($process);
        }*/

        $resultats = json_decode($process->getOutput());
        //var_dump($resultats);
        return $resultats->features[0]->geometry->coordinates;
    }

    private function calculDifférence($point1, $point2)
    {
        $point1_lat = $point1[0];
        $point1_long = $point1[1];

        $point2_lat = $point2[0];
        $point2_long = $point2[1];

        $degrees = rad2deg(acos((sin(deg2rad($point1_lat))*sin(deg2rad($point2_lat))) + (cos(deg2rad($point1_lat))*cos(deg2rad($point2_lat))*cos(deg2rad($point1_long-$point2_long)))));
        $distance = $degrees * 111.13384; // 1 degré = 111,13384 km, sur base du diamètre moyen de la Terre (12735 km)

        return $distance;
    }

    private function getPointsRelaisFromBase()
    {
        $em = $this->container->get("doctrine");
        return $em->getRepository('AppBundle:PointRelais')->findAll();
    }
}