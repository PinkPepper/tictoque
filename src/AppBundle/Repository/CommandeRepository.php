<?php

namespace AppBundle\Repository;

/**
 * CommandeRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class CommandeRepository extends \Doctrine\ORM\EntityRepository
{
    public function findForMonth(){
        $date = new \DateTime('now');
        $year = $date->format('Y');
        $month = $date->format('m');
        $month1 = (int)$month+1;
        if(sizeof($month1)<10){
            $month1 = "0".$month1;
        }

        $dateOne = $year."-".$month1."-01 00:00:00";
        $dateTwo = $year."-".$month."-01 00:00:00";

        $dateFrom  = new \DateTime($dateTwo);
        $dateTo =  new \DateTime($dateOne);

        //dump($dateFrom); // 2017-07-01 00:00:00.000000
        //dump($dateTo); //2017-08-01 00:00:00.000000


       $res = $this->createQueryBuilder('e')
            ->where('e.date >= :dateFrom')
            ->andWhere('e.date < :dateTo')
            ->orderBy('e.date','ASC')
            ->setParameter('dateFrom',$dateFrom)
            ->setParameter('dateTo',$dateTo)
            ->getQuery();

        return $res->getResult();
    }

    public function findByDay(){
        $date = new \DateTime('now');
        $year = $date->format('Y');
        $month = $date->format('m');
        $day = $date->format('d');

        $date = $dateOne = $year."-".$month."-".$day." 00:00:00";
        $date1 = $dateOne = $year."-".$month."-".$day." 23:59:59";

        $dateFrom = new \DateTime($date);
        $dateTo =  new \DateTime($date1);

        $res = $this->createQueryBuilder('e')
            ->where('e.date >= :dateFrom')
            ->andWhere('e.date < :dateTo')
            ->orderBy('e.date','DESC')
            ->setParameter('dateFrom',$dateFrom)
            ->setParameter('dateTo',$dateTo)
            ->getQuery();

        return $res->getResult();
    }

    public function findByYear($year){
        $date = new \DateTime('now');

        $tabResult = [];


        for($i=1;$i<13;$i++){
            if($i>9){
                $date = $year."-".$i."-01 00:00:00";
                $date1 = $year."-".$i."-30 23:59:59";
            }else{
                $date = $year."-0".$i."-01 00:00:00";
                $date1 = $year."-0".$i."-30 23:59:59";
            }

            $dateFrom = new \DateTime($date);
            $dateTo =  new \DateTime($date1);

            $res = $this->createQueryBuilder('e')
                ->where('e.date >= :dateFrom')
                ->andWhere('e.date < :dateTo')
                ->orderBy('e.date','DESC')
                ->setParameter('dateFrom',$dateFrom)
                ->setParameter('dateTo',$dateTo)
                ->getQuery();

            array_push($tabResult,$res->getResult());
        }

        return $tabResult;
    }

    public function statsMonth(){
        $date = new \DateTime('now');
        $year = $date->format('Y');
        $month = $date->format('m');
        $month1 = (int)$month+1;
        if(sizeof($month1)<10){
            $month1 = "0".$month1;
        }

        $month2 = $date->format('m');
        $month12 = (int)$month2-1;
        if(sizeof($month12)<10){
            $month12 = "0".$month12;
        }

        $dateOne = $year."-".$month1."-01 00:00:00"; //2017-08-01 00:00:00.000000
        $dateTwo = $year."-".$month."-01 00:00:00"; // 2017-07-01 00:00:00.000000

        $dateOne2 = $year."-".$month12."-01 00:00:00"; //2017-07-01 00:00:00.000000
        $dateTwo2 = $year."-".$month2."-01 00:00:00"; // 2017-06-01 00:00:00.000000

        $dateFrom  = new \DateTime($dateTwo);
        $dateTo =  new \DateTime($dateOne);

        $dateFrom2  = new \DateTime($dateTwo2);
        $dateTo2 =  new \DateTime($dateOne2);


        $res = $this->createQueryBuilder('e')
            ->where('e.date >= :dateFrom')
            ->andWhere('e.date < :dateTo')
            ->orderBy('e.date','DESC')
            ->setParameter('dateFrom',$dateFrom)
            ->setParameter('dateTo',$dateTo)
            ->getQuery();

        $res1 = $this->createQueryBuilder('e')
            ->where('e.date >= :dateFrom')
            ->andWhere('e.date < :dateTo')
            ->orderBy('e.date','DESC')
            ->setParameter('dateFrom',$dateTo2)
            ->setParameter('dateTo',$dateFrom2)
            ->getQuery();

        $res = $res->getResult();
        $res1 = $res1->getResult();

        return array($res,$res1);
    }

}
