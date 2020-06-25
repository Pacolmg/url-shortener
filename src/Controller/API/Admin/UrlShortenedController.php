<?php

namespace App\Controller\API\Admin;

use App\Entity\UrlShortened;
use App\Service\UrlShortenedService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * @Route("/url-shortened", name="url_shortened_")
 */
class UrlShortenedController extends AbstractController
{

    /**
     * @var UrlShortenedService
     */
    private $urlShortenedService;

    /**
     * @var SerializerInterface
     */
    private $serializer;

    /**
     * UrlShortenedController constructor.
     * @param UrlShortenedService $urlShortenedService
     * @param SerializerInterface $serializer
     */
    public function __construct(UrlShortenedService $urlShortenedService, SerializerInterface $serializer)
    {
        $this->urlShortenedService = $urlShortenedService;

        $this->serializer = $serializer;
    }

    /**
     * @Route("/", name="index", methods={"GET"})
     * @Security("is_granted('ROLE_URL_SHORTENED_LIST')", message="error.not_allowed_to_enter_here")
     */
    public function index(): JsonResponse
    {
        return new JsonResponse(
            json_decode($this->serializer->serialize(
                $this->urlShortenedService->obtainAll(),
                'json',
                ['groups' => ['url_shortener_owner']]
            ), 1)
        );
    }

    /**
     * @Route("/new", name="new", methods={"POST"})
     * @Security("is_granted('ROLE_URL_SHORTENED_CREATE')", message="error.not_allowed_to_enter_here")
     *
     * @param UrlShortened $newUrlShortened
     * @return JsonResponse
     */
    public function new(UrlShortened $newUrlShortened): JsonResponse
    {
        $newUrlShortened = $this->urlShortenedService->create($newUrlShortened);
        return new JsonResponse(
            json_decode($this->serializer->serialize(
                $newUrlShortened,
                'json',
                ['groups' => ['url_shortener_owner']]
            ), 1)
        );
    }

    /**
     * @Route("/{id}", name="show", methods={"GET"}, requirements={"id"="\d+"})
     * @Security("is_granted('ROLE_URL_SHORTENED_READ')", message="error.not_allowed_to_enter_here")
     *
     * @param UrlShortened $urlShortened
     * @return JsonResponse
     */
    public function show(UrlShortened $urlShortened): JsonResponse
    {
        $this->denyAccessUnlessGranted('read', $urlShortened);

        return new JsonResponse(
            json_decode($this->serializer->serialize(
                $urlShortened,
                'json',
                ['groups' => ['url_shortener_owner']]
            ), 1)
        );
    }


    /**
     * @Route("/edit", name="edit", methods={"PUT"})
     * @Security("is_granted('ROLE_URL_SHORTENED_UPDATE')", message="error.not_allowed_to_enter_here")
     *
     * @param UrlShortened $updateUrlShortened
     * @return JsonResponse
     */
    public function edit(UrlShortened $updateUrlShortened): JsonResponse
    {
        $this->denyAccessUnlessGranted('update', $updateUrlShortened);
        $updateUrlShortened = $this->urlShortenedService->update($updateUrlShortened);
        return new JsonResponse(
            json_decode($this->serializer->serialize(
                $updateUrlShortened,
                'json',
                ['groups' => ['url_shortener_owner']]
            ), 1)
        );
    }

    /**
     * @Route("/{id}", name="delete", methods={"DELETE"}, requirements={"id"="\d+"})
     * @Security("is_granted('ROLE_URL_SHORTENED_DELETE')", message="error.not_allowed_to_enter_here")
     *
     * @param UrlShortened $urlShortened
     * @return JsonResponse
     */
    public function delete(UrlShortened $urlShortened): JsonResponse
    {
        $this->denyAccessUnlessGranted('delete', $urlShortened);
        $this->urlShortenedService->delete($urlShortened);

        return new JsonResponse([], JsonResponse::HTTP_NO_CONTENT);
    }
}