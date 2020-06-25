<?php

namespace App\Service;

use App\Entity\UrlShortened;
use App\Library\UrlShortener\UrlShortener;
use App\Library\UrlShortener\UrlShortenerMethod1;
use App\Library\UrlShortener\UrlShortenerMethod2;

/**
 * Class LoadUrlShortener
 * @package App\Service
 */
class UrlShortenerService
{
    /**
     * @var UrlShortenerMethod1
     */
    private $urlShortenerMethod1;
    /**
     * @var UrlShortenerMethod2
     */
    private $urlShortenerMethod2;

    public function __construct(UrlShortenerMethod1 $urlShortenerMethod1, UrlShortenerMethod2 $urlShortenerMethod2)
    {
        $this->urlShortenerMethod1 = $urlShortenerMethod1;
        $this->urlShortenerMethod2 = $urlShortenerMethod2;
    }

    /**
     * @return array
     */
    public static function getMethods(): array
    {
        return array_keys(UrlShortener::METHODS_NAMES);
    }

    /**
     * @return array
     */
    public static function getMethodsNames(): array
    {
        return UrlShortener::METHODS_NAMES;
    }

    /**
     * @param int $method
     * @param string $url
     * @return string
     */
    public function shortenUrl(int $method, string $url): string
    {
        // default method is method 1
        $service = $this->urlShortenerMethod1;

        switch ($method) {
            case UrlShortener::METHOD_2:
                $service = $this->urlShortenerMethod2;
        }

        return $service->shortenUrl($url);
    }

}