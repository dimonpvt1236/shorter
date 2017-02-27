<?php
/**
 * Created by PhpStorm.
 * User: dimon
 * Date: 26.02.17
 * Time: 10:46
 */

namespace ShorterBundle\Entity\Repository;


use Doctrine\ORM\EntityRepository;

/**
 * Class ShortUrlRepository
 * @package ShorterBundle\Entity\Repository
 */
class ShortUrlRepository extends EntityRepository
{

    public function increaseCountById($id) {
        return $this
            ->createQueryBuilder('u')
            ->update($this->getEntityName(), 'u')
            ->set('u.count', 'u.count + 1')
            ->where('u.id = :id')->setParameter('id', $id)
            ->getQuery()
            ->execute();
    }
}