<?php

namespace App\Adapter\UrlShortened;

use App\Adapter\BaseConverter;
use App\Service\UrlShortenerService;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class BaseUrlShortenedConverter extends BaseConverter
{

    /**
     * @var Assert\Url
     */
    protected $urlConstraint;

    /**
     * @var Assert\Choice
     */
    protected $methodConstraint;

    /**
     * BaseUrlShortenedConverter constructor.
     * @param ValidatorInterface $validator
     */
    public function __construct(ValidatorInterface $validator)
    {
        parent::__construct($validator);

        $this->urlConstraint = new Assert\Url();
        $this->methodConstraint = new Assert\Choice(UrlShortenerService::getMethods());
    }


}
