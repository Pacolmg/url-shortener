<?php

namespace App\Library\UrlShortener;

/**
 * Class UrlShortenerMethod2
 * @package App\Library\UrlShortener
 */
class UrlShortenerMethod2 extends BaseUrlShortener implements UrlShortener
{

    /**
     * @inheritDoc
     */
    public function shortenUrl(string $url): string
    {
        return $this->parameterBag->get('url_shortener_host') . hash('sha256', $url);
    }
}