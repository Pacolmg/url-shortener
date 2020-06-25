<?php

namespace App\Adapter\UrlShortened;

use App\Entity\UrlShortened;
use App\Repository\UrlShortenedRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Request\ParamConverter\ParamConverterInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class UpdateUrlShortenedConverter extends BaseUrlShortenedConverter implements ParamConverterInterface
{
    /**
     * @var UrlShortenedRepository
     */
    private $urlShortenedRepository;

    /**
     * UpdateUrlShortenedConverter constructor.
     * @param ValidatorInterface $validator
     * @param UrlShortenedRepository $urlShortenedRepository
     */
    public function __construct(ValidatorInterface $validator, UrlShortenedRepository $urlShortenedRepository)
    {
        parent::__construct($validator);
        $this->urlShortenedRepository = $urlShortenedRepository;
    }

    /**
     * @param Request $request
     * @param ParamConverter $configuration
     * @return bool
     */
    public function apply(Request $request, ParamConverter $configuration)
    {
        $data = json_decode($request->getContent(), 1);

        $updateUrlShortened = $this->urlShortenedRepository->find($data['id']);
        if (!$updateUrlShortened instanceof UrlShortened) {
            throw new NotFoundHttpException('Can\'t find the element to edit');
        }

        $this->validateMandatory($data, ['original_url']);
        $this->validateParam('original_url', $data['original_url'], $this->urlConstraint);

        $updateUrlShortened->setOriginalUrl($data['original_url']);

        $request->attributes->set('updateUrlShortened', $updateUrlShortened);

        return true;
    }

    /**
     * @inheritdoc
     */
    public function supports(ParamConverter $configuration)
    {

        return UrlShortened::class === $configuration->getClass()
            && 'updateUrlShortened' === $configuration->getName();
    }
}
