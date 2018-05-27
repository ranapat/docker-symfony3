<?php

namespace NeoBundle\Repository;

use Doctrine\DBAL\Query\QueryBuilder;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query\ResultSetMapping;

use NeoBundle\Entity\Neo;

/**
 * Neo Repository
 */
class NeoRepository extends EntityRepository
{
    /**
     * @return mixed
     *
     * @throws NoResultException
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function findAllHazardous()
    {
        $queryBuilder = $this->createQueryBuilder('n');

        $queryBuilder->where('n.isHazardous = true');

        return $queryBuilder->getQuery()->getResult();
    }

    /**
     * @param bool $isHazardous
     * @return mixed
     */
    public function findFastest(bool $isHazardous = false)
    {
        return $this->createQueryBuilder('n')
            ->where('n.isHazardous=:isHazardous')
            ->setParameter('isHazardous', $isHazardous)
            ->orderBy('n.speed', 'desc')
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult();
    }

    /**
     * @param bool $isHazardous
     * @return mixed
     */
    public function findYearWithMostRecords(bool $isHazardous = false)
    {
        $sql = "select year(approach_at) year from neo where is_hazardous=:isHazardous group by year(approach_at) order by count(id) desc limit 1";
        $params = [
            'isHazardous' => $isHazardous
        ];

        $em = $this->getEntityManager();
        $stmt = $em->getConnection()->prepare($sql);
        $stmt->execute($params);

        $result = $stmt->fetchAll();

        return count($result) > 0 ? $result[0]['year'] : null;
    }

    /**
     * @param bool $isHazardous
     * @return mixed
     */
    public function findMonthWithMostRecords(bool $isHazardous = false)
    {
        $sql = "select monthname(approach_at) month from neo where is_hazardous=:isHazardous group by monthname(approach_at) order by count(id) desc limit 1";
        $params = [
            'isHazardous' => $isHazardous
        ];

        $em = $this->getEntityManager();
        $stmt = $em->getConnection()->prepare($sql);
        $stmt->execute($params);

        $result = $stmt->fetchAll();

        return count($result) > 0 ? $result[0]['month'] : null;
    }

    /**
     * @param bool $isHazardous
     * @return bool
     */
    public function referenceExists(int $reference)
    {
        return $this->createQueryBuilder('n')
            ->select('count(n.id)')
            ->where('n.reference=:reference')
            ->setParameter('reference', $reference)
            ->getQuery()
            ->getSingleScalarResult() == 0;
    }
}