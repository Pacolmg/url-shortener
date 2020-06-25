<?php

namespace App\Controller\API\Site;

use App\Service\UrlShortenedService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * @Route("/url-shortened", name="url_shortened_")
 */
class UrlShortenedController extends AbstractController
{

    /**
     * @var SerializerInterface
     */
    private $serializer;

    /**
     * @var UrlShortenedService
     */
    private $urlShortenedService;

    /**
     * VisitController constructor.
     * @param UrlShortenedService $urlShortenedService
     * @param SerializerInterface $serializer
     */
    public function __construct(UrlShortenedService $urlShortenedService, SerializerInterface $serializer)
    {
        $this->serializer = $serializer;
        $this->urlShortenedService = $urlShortenedService;
    }

    /**
     * @Route("/ranking/{sortBy}/{sortDir}", name="ranking", methods={"GET"}, requirements={"sortBy"="total_visits|num_visits_last_interval", "sortDir"="asc|desc"}, defaults={"sortBy": "total_visits", "sortDir": "desc"})
     *
     * @param string $sortBy
     * @param string $sortDir
     * @return JsonResponse
     */
    public function ranking(string $sortBy, string $sortDir): JsonResponse
    {
        return new JsonResponse(
            json_decode($this->serializer->serialize(
                $this->urlShortenedService->getRanking($sortBy, $sortDir),
                'json',
                ['groups' => ['visit_ranking']]
            ), 1)
        );
    }
}