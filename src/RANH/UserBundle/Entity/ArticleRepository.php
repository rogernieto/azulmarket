<?php

namespace RANH\UserBundle\Entity;

use Doctrine\ORM\EntityRepository;

/**
 * ArticleRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class ArticleRepository extends EntityRepository
{

    public function subasta($subcategoria_id)
    {
        $em = $this->getEntityManager();

        $dql = "SELECT a.id, a.title, a.initialDate, a.finalDate, art.name , s.status
          FROM RANHUserBundle:Ad a 
          JOIN RANHUserBundle:Article art
          WHERE a.articleId = art.id
          JOIN RANHUserBundle:State s
          WHERE art.stateId = s.id
          JOIN RANHUserBundle:SubCategory sub 
          WHERE art.subcategoryId = sub.id
          AND   art.subcategoryId = :subcate ";
        $query = $em->createQuery($dql);
        $query->setParameter('subcate', $subcategoria_id);
        $subcateg= $query->getResult();

        return $subcateg;
    }

    public function findByUserId($idU)
    {
        $em = $this->getEntityManager();

        $dql = "SELECT art 
          FROM RANHUserBundle:Article art 
          JOIN RANHUserBundle:Ad ad
          WHERE ad.userId = :idU 
          AND art.id = ad.articleId";
        $query = $em->createQuery($dql);
        $query->setParameter('idU', $idU);
        $art= $query->getResult();

        return $art;
    }

}
