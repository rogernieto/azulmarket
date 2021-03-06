<?php

namespace RANH\UserBundle\Entity;

use Doctrine\ORM\EntityRepository;

/**
 * SubCategoryRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class SubCategoryRepository extends EntityRepository
{
    public function findByCategoryId($categoria_id)
    {

        $em = $this->getEntityManager();

        $dql = "select s.id, s.name
        from RANHUserBundle:SubCategory s
        join s.category c
        where s.categoryId = :cate";

        $query = $em->createQuery($dql);
        $query->setParameter('cate', $categoria_id);

        $subcategorias = $query->getResult();

        return $subcategorias;

    }


}
