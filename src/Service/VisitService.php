<?php

namespace App\Service;

use App\Entity\UrlShortened;
use App\Repository\UrlShortenedRepository;
use App\Repository\VisitRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Security;

/**
 * Class VisitService
 * @package App\Service
 */
class VisitService
{
    private const DEFAULT_RANKING_SORT_BY = 'total_visits';
    private const DEFAULT_RANKING_SORT_DIR = 'DESC';

    /**
     * @var EntityManagerInterface
     */
    private $em;

    /**
     * @var VisitRepository
     */
    private $visitRepository;

    /**
     * VisitService constructor.
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->em = $entityManager;
        $this->visitRepository = $entityManager->getRepository('App:Visit');
    }


}