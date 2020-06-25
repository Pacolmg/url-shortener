<?php

namespace App\Library\UrlShortener;

use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

/**
 * Interface UrlShortener
 * @package App\Library\UrlShortener
 */
interface UrlShortener {

    const METHOD_1 = 1;
    const METHOD_2 = 2;

    const METHODS_NAMES = [
        self::METHOD_1 => 'Method 1',
        self::METHOD_2 => 'Method 2'
    ];

    /**
     * @param string $url
     * @return string
     */
    public function shortenUrl(string $url): string;
}
