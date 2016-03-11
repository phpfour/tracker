<?php

namespace AppBundle\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * TimeLog Repository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class TimelogRepository extends EntityRepository
{
    public function getProjectStat()
    {
        $result = $this->createQueryBuilder('t')
            ->select('t.project, SUM(t.duration) AS total')
            ->groupBy('t.project')
            ->orderBy('total', 'DESC')
            ->getQuery()
            ->getArrayResult();

        return $result;
    }
}