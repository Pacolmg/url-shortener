<?php

namespace App\Adapter\UrlShortened;

use App\Entity\UrlShortened;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Request\ParamConverter\ParamConverterInterface;
use Symfony\Component\HttpFoundation\Request;

class NewUrlShortenedConverter extends BaseUrlShortenedConverter implements ParamConverterInterface
{

    /**
     * @param Request $request
     * @param ParamConverter $configuration
     * @return bool
     */
    public function apply(Request $request, ParamConverter $configuration)
    {
        $data = json_decode($request->getContent(), 1);

        $this->validateMandatory($data, ['original_url', 'short_method']);
        $this->validateParam('original_url', $data['original_url'], $this->urlConstraint);
        $this->validateParam('short_method', $data['short_method'], $this->methodConstraint);

        $newUrlShortened = (new UrlShortened())->setOriginalUrl($data['original_url'])
            ->setShortMethod($data['short_method']);

        $request->attributes->set('newUrlShortened', $newUrlShortened);

        return true;
    }

    /**
     * @inheritdoc
     */
    public function supports(ParamConverter $configuration)
    {
        return UrlShortened::class === $configuration->getClass()
            && 'newUrlShortened' === $configuration->getName();
    }
}
