<?php

namespace App\Library\UrlShortener;

use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

/**
 * Class BaseUrlShortener
 * @package App\Library\UrlShortener
 */
class BaseUrlShortener
{
    /**
     * @var ParameterBagInterface
     */
    protected $parameterBag;

    /**
     * BaseUrlShortener constructor.
     * @param ParameterBagInterface $parameterBag
     */
    public function __construct(ParameterBagInterface $parameterBag)
    {
        $this->parameterBag = $parameterBag;
    }
}