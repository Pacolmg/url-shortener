<?php

namespace App\Service;

use App\Entity\UrlShortened;
use App\Repository\UrlShortenedRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Security;

/**
 * Class UrlShortenedService
 * @package App\Service
 */
class UrlShortenedService
{
    private const DEFAULT_RANKING_SORT_BY = 'total_visits';
    private const DEFAULT_RANKING_SORT_DIR = 'DESC';
    private const RANKING_LAST_INTERVAL = '1 day ago';

    /**
     * @var EntityManagerInterface
     */
    private $em;

    /**
     * @var UrlShortenedRepository
     */
    private $urlShortenedRepository;
    /**
     * @var UrlShortenerService
     */
    private $urlShortenerService;
    /**
     * @var Security
     */
    private $security;

    /**
     * UrlShortenedService constructor.
     * @param EntityManagerInterface $entityManager
     * @param UrlShortenerService $urlShortenerService
     * @param Security $security
     */
    public function __construct(
        EntityManagerInterface $entityManager,
        UrlShortenerService $urlShortenerService,
        Security $security
    ) {
        $this->em = $entityManager;
        $this->urlShortenedRepository = $entityManager->getRepository('App:UrlShortened');
        $this->urlShortenerService = $urlShortenerService;
        $this->security = $security;
    }


    /**
     * @return UrlShortened[]
     */
    public function obtainAll(): array
    {
        return $this->urlShortenedRepository->findAll();
    }

    /**
     * @param UrlShortened $urlShortened
     * @return UrlShortened
     */
    public function create(UrlShortened $urlShortened): UrlShortened
    {
        $urlShortened->setOwner($this->security->getUser());
        $urlShortened->setUrlShortened(
          $this->urlShortenerService->shortenUrl(
              $urlShortened->getShortMethod(),
              $urlShortened->getOriginalUrl()
          )
        );

        $this->em->persist($urlShortened);
        $this->em->flush();

        return $urlShortened;
    }

    /**
     * @param int $id
     * @return UrlShortened|null
     */
    public function obtain(int $id): ?UrlShortened
    {
        return $this->urlShortenedRepository->find($id);
    }

    /**
     * @param UrlShortened $urlShortened
     * @return UrlShortened
     */
    public function update(UrlShortened $urlShortened): UrlShortened
    {
        $this->em->flush();
        return $urlShortened;
    }

    /**
     * @param UrlShortened $urlShortened
     */
    public function delete(UrlShortened $urlShortened): void
    {
        $this->em->remove($urlShortened);
        $this->em->flush();
    }


    /**
     * @param string|null $sortBy
     * @param string|null $sortDir
     * @return void[]
     */
    public function getRanking(string $sortBy = null, string $sortDir = null)
    {
        return array_map(
            function($visitRanking) {
              /** @var $urlShortened UrlShortened */
              $urlShortened = $visitRanking[0];
              return $urlShortened->setLastIntervalText(self::RANKING_LAST_INTERVAL)
                  ->setTotalVisits($visitRanking['total_visits'])
                  ->setNumVisitsLastInterval($visitRanking['num_visits_last_interval']);
            },
        $this->urlShortenedRepository->ranking($sortBy ?? self::DEFAULT_RANKING_SORT_BY , $sortDir ?? self::DEFAULT_RANKING_SORT_DIR, self::RANKING_LAST_INTERVAL));
    }
}