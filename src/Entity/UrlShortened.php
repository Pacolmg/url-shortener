<?php

namespace App\Entity;

use App\Repository\UrlShortenedRepository;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\UniqueConstraint;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=UrlShortenedRepository::class)
 * @ORM\Table(uniqueConstraints={
 *     @UniqueConstraint(name="original_url_owner", columns={"original_url", "owner_id"}),
 *     @UniqueConstraint(name="urlShortened", columns={"url_shortened"})
 *  }
 * )
 */
class UrlShortened
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Groups({"url_shortener_owner", "visit_ranking"})
     */
    private $id;

    /**
     * @Assert\Url
     * @ORM\Column(type="string", length=255)
     * @Groups({"url_shortener_owner"})
     */
    private $originalUrl;

    /**
     * @ORM\Column(type="string", length=127)
     * @Groups({"url_shortener_owner", "visit_ranking"})
     */
    private $urlShortened;

    /**
     * @ORM\ManyToOne(targetEntity=User::class)
     * @ORM\JoinColumn(nullable=false)
     * @Groups({"visit_ranking"})
     */
    private $owner;


    /**
     * @Assert\Choice(callback={"App\Service\UrlShortenerService", "getMethods"})
     * @ORM\Column(type="integer")
     * @Groups({"url_shortener_owner"})
     */
    private $shortMethod;

    /**
     * @Groups({"visit_ranking"})
     */
    private $total_visits;

    /**
     * @Groups({"visit_ranking"})
     */
    private $num_visits_last_interval;

    /**
     * @Groups({"visit_ranking"})
     */
    private $last_interval_text;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getOriginalUrl(): ?string
    {
        return $this->originalUrl;
    }

    public function setOriginalUrl(string $originalUrl): self
    {
        $this->originalUrl = $originalUrl;

        return $this;
    }

    public function getUrlShortened(): ?string
    {
        return $this->urlShortened;
    }

    public function setUrlShortened(string $urlShortened): self
    {
        $this->urlShortened = $urlShortened;

        return $this;
    }

    public function getOwner(): ?User
    {
        return $this->owner;
    }

    public function setOwner(?User $owner): self
    {
        $this->owner = $owner;

        return $this;
    }

    public function getShortMethod(): ?int
    {
        return $this->shortMethod;
    }

    public function setShortMethod(int $shortMethod): self
    {
        $this->shortMethod = $shortMethod;

        return $this;
    }

    public function getTotalVisits(): ?int
    {
        return $this->total_visits;
    }

    public function setTotalVisits(?int $total_visits): self
    {
        $this->total_visits = $total_visits;

        return $this;
    }

    public function getNumVisitsLastInterval(): ?int
    {
        return $this->num_visits_last_interval;
    }

    public function setNumVisitsLastInterval(?int $num_visits_last_interval): self
    {
        $this->num_visits_last_interval = $num_visits_last_interval;

        return $this;
    }

    public function getLastIntervalText(): ?string
    {
        return $this->last_interval_text;
    }

    public function setLastIntervalText(?string $last_interval_text): self
    {
        $this->last_interval_text = $last_interval_text;

        return $this;
    }
}
