<?php

namespace App\Repository;

use App\Entity\UrlShortened;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method UrlShortened|null find($id, $lockMode = null, $lockVersion = null)
 * @method UrlShortened|null findOneBy(array $criteria, array $orderBy = null)
 * @method UrlShortened[]    findAll()
 * @method UrlShortened[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UrlShortenedRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, UrlShortened::class);
    }

    /**
     * @param string $sortBy
     * @param string $sortDir
     * @param string $interval
     * @return []
     */
    public function ranking(string $sortBy, string $sortDir, string $interval): array
    {
        // Get visits from last interval
        $lastInterval = (new \DateTime($interval))->format('Y-m-d H:i:s');

        // fix sort by strings
        switch ($sortBy) {
            case 'num_visits':
                $sortBy = 'COUNT(v2.id)';
                break;
            default:
                $sortBy = 'COUNT(v.id)';
                break;
        }

        // fix order dirs
        $sortDir = strtoupper($sortDir);
        if (!in_array($sortDir, ['ASC', 'DESC'])) {
            $sortDir = 'DESC';
        }

        return $this->createQueryBuilder('u')
            ->select('u, COUNT(distinct v.id) as total_visits, COUNT(distinct v2.id) as num_visits_last_interval ')
            ->leftJoin('App\Entity\Visit', 'v', 'WITH', 'u.id = v.url')
            ->leftJoin('App\Entity\Visit', 'v2', 'WITH', 'u.id = v2.url AND v2.visitedAt >= :last_interval')
            ->setParameter('last_interval', $lastInterval)
            ->groupBy('u.id')
            ->orderBy($sortBy, $sortDir)
            ->getQuery()
            ->getResult();
    }
}
