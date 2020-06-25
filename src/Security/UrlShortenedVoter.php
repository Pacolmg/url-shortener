<?php

namespace App\Security;

use App\Entity\UrlShortened;
use App\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\Security;

/**
 * Class UrlShortenedVoter
 * @package App\Security
 */
class UrlShortenedVoter extends Voter
{
    // There are perms for read, update and delete
    const READ = 'read';
    const UPDATE = 'update';
    const DELETE = 'delete';
    /**
     * @var Security
     */
    private $security;

    /**
     * UrlShortenedVoter constructor.
     * @param Security $security
     */
    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    /**
     * @param string $attribute
     * @param mixed $subject
     * @return bool
     */
    protected function supports($attribute, $subject)
    {
        // if the attribute isn't one we support, return false
        if (!in_array($attribute, [self::READ, self::UPDATE, self::DELETE])) {
            return false;
        }

        // only vote on `UrlShortened` objects
        if (!$subject instanceof UrlShortened) {
            return false;
        }

        return true;
    }

    /**
     * @param string $attribute
     * @param mixed $subject
     * @param TokenInterface $token
     * @return bool
     */
    protected function voteOnAttribute($attribute, $subject, TokenInterface $token)
    {
        $user = $token->getUser();

        if (!$user instanceof User) {
            // the user must be logged in; if not, deny access
            return false;
        }

        /** @var UrlShortened $urlShortened */
        $urlShortened = $subject;

        switch ($attribute) {
            case self::READ:
                return $this->canRead($urlShortened, $user);
            case self::UPDATE:
                return $this->canUpdate($urlShortened, $user);
            case self::DELETE:
                return $this->canDelete($urlShortened, $user);
        }

        throw new \LogicException('This code should not be reached!');
    }

    /**
     * @param UrlShortened $urlShortened
     * @param User $user
     * @return bool
     */
    private function canRead(UrlShortened $urlShortened, User $user)
    {
        // if the user can update, can read it too
        if ($this->canUpdate($urlShortened, $user)) {
            return true;
        }

        return false;
    }

    /**
     * @param UrlShortened $urlShortened
     * @param User $user
     * @return bool
     */
    private function canUpdate(UrlShortened $urlShortened, User $user)
    {
        // it the user can delete, can update it too
        return $this->canDelete($urlShortened, $user);
    }

    /**
     * @param UrlShortened $urlShortened
     * @param User $user
     * @return bool
     */
    private function canDelete(UrlShortened $urlShortened, User $user)
    {
        // The user can delete the url only if it's the owner
        return $urlShortened->getOwner() === $this->security->getUser();
    }
}
