<?php

namespace App\EventListener;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\Security\Core\Security;

/**
 * Class OnRequestListener
 */
class OnRequestListener
{
    /**
     * @var EntityManagerInterface
     */
    private $em;
    /**
     * @var Security
     */
    private $security;

    /**
     * OnRequestListener constructor.
     * @param EntityManagerInterface $em
     * @param Security $security
     */
    public function __construct(EntityManagerInterface $em, Security $security)
    {
        $this->em = $em;
        $this->security = $security;
    }

    /**
     * @param RequestEvent $event
     */
    public function onKernelRequest(RequestEvent $event)
    {
        if ($this->security->getUser() instanceof User) {
            // Enable the filter
            $filter = $this->em->getFilters()->enable('owner');
            $filter->setParameter('owner', $this->security->getUser()->getId());
        }
    }
}