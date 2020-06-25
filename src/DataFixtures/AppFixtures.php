<?php

namespace App\DataFixtures;

use App\Entity\Author;
use App\Entity\UrlShortened;
use App\Entity\User;
use App\Entity\Visit;
use App\Service\UrlShortenerService;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AppFixtures extends Fixture
{
    private const NUM_USERS = 10;
    private const NUM_URLS = 100;
    private const NUM_VISITS = 10000;

    /**
     * @var UserPasswordEncoderInterface
     */
    private $encoder;
    /**
     * @var UrlShortenerService
     */
    private $urlShortenerService;

    /**
     * AppFixtures constructor.
     * @param UserPasswordEncoderInterface $encoder
     * @param UrlShortenerService $urlShortenerService
     */
    public function __construct(UserPasswordEncoderInterface $encoder, UrlShortenerService $urlShortenerService)
    {
        $this->encoder = $encoder;

        $this->urlShortenerService = $urlShortenerService;
    }

    /**
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager): void
    {

        //Load users
        $arrayOwners = [];
        for ($i = 0; $i < self::NUM_USERS; $i++) {
            $user = (new User())->setRoles(['ROLE_ADMIN'])->setUsername('user' . ($i + 1));
            $password = $this->encoder->encodePassword($user, '123456');
            $user->setPassword($password);

            $arrayOwners[] = $user;

            $manager->persist($user);
        }

        // Load urls
        $methods = UrlShortenerService::getMethods();
        $arrayUrls = [];
        for ($i = 0; $i < self::NUM_URLS; $i++) {
            $method = $methods[rand(0, count($methods) - 1)];
            $originalUrl = 'http://url' . $i . '.com';

            $urlShortened = (new UrlShortened())
                ->setOriginalUrl($originalUrl)
                ->setOwner($arrayOwners[rand(0, self::NUM_USERS - 1)])
                ->setShortMethod($method)
                ->setUrlShortened($this->urlShortenerService->shortenUrl($method, $originalUrl));


            $arrayUrls[] = $urlShortened;
            $manager->persist($urlShortened);
        }

        // Load visits
        $devices = ['Chrome', 'Firefox', 'Safari', 'Android', 'Iphone'];

        for ($i = 0; $i < self::NUM_VISITS; $i++) {
            $visit = (new Visit())
                ->setDevice($devices[rand(0, count($devices) - 1)])
                ->setUrl($arrayUrls[rand(0, count($arrayUrls) - 1)])
                ->setVisitedAt((new \DateTime())->setTimestamp(rand( strtotime("Jan 01 2020"), time())));

            $manager->persist($visit);
        }

        $manager->flush();
    }
}
