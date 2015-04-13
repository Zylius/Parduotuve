<?php
/**
 * Created by PhpStorm.
 * User: Zylius
 * Date: 2015-04-13
 * Time: 20:24
 */

namespace Zylius\ParduotuveBundle\Entity;

use Doctrine\ORM\EntityRepository;

class ProductOrderRepository extends EntityRepository {

    public function getStatistics(User $user)
    {
        $parameters = [];
        $query = 'SELECT MIN(a.price) as min_price, MAX(a.price) as max_price, AVG(a.price) as avg_price, MAX(p.date) as date_max, MIN(p.date) as date_min
                  FROM ZyliusParduotuveBundle:ProductOrder p JOIN p.products a';

        if (!in_array('ROLE_SUPER_ADMIN', $user->getRoles())) {
            $query .= ' WHERE p.confirmer = :user';
            $parameters['user'] = $user;
        }

        return $this->getEntityManager()
            ->createQuery($query)
            ->setParameters($parameters)
            ->getResult();
    }
} 