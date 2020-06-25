<?php

namespace App\Library\UrlShortener;

/**
 * Class UrlShortenerMethod1
 * @package App\Library\UrlShortener
 */
final class UrlShortenerMethod1 extends BaseUrlShortener implements UrlShortener
{
    /**
     * @inheritDoc
     */
    public function shortenUrl(string $url): string
    {
        return $this->parameterBag->get('url_shortener_host') . hash('md5', $url);
    }
}