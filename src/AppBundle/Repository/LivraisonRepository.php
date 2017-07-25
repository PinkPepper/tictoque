<?php

namespace AppBundle\Repository;

/**
 * LivraisonRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class LivraisonRepository extends \Doctrine\ORM\EntityRepository
{
    public function findByProduitAndPointRelaisAndDate($produit, $pointRelais, $date)
    {
        $date = new \DateTime($date);

        $res = $this->createQueryBuilder('e')
            ->where('e.date = :date')
            ->andWhere('e.produit = :produit')
            ->andWhere('e.pointRelais = :pointRelais')
            ->setParameters(array('date'=>$date, 'produit'=>$produit, 'pointRelais'=>$pointRelais))
            ->getQuery();

        return $res->getResult();

    }
}
