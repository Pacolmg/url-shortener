<?php

namespace App\Entity;

use App\Repository\VisitRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=VisitRepository::class)
 * @ORM\Table(indexes={
 *     @ORM\Index(name="device", columns={"device"}),
 *     @ORM\Index(name="visited_at", columns={"visited_at"}),
 *     @ORM\Index(name="url_device", columns={"url_id", "device"}),
 *     @ORM\Index(name="url_visited", columns={"url_id", "visited_at"}),
 *     @ORM\Index(name="url_visited_device", columns={"url_id", "visited_at", "device"}),
 * })
 */
class Visit
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=UrlShortened::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private $url;

    /**
     * @ORM\Column(type="datetime")
     */
    private $visitedAt;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $device;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUrl(): ?UrlShortened
    {
        return $this->url;
    }

    public function setUrl(?UrlShortened $url): self
    {
        $this->url = $url;

        return $this;
    }

    public function getVisitedAt(): ?\DateTimeInterface
    {
        return $this->visitedAt;
    }

    public function setVisitedAt(\DateTimeInterface $visitedAt): self
    {
        $this->visitedAt = $visitedAt;

        return $this;
    }

    public function getDevice(): ?string
    {
        return $this->device;
    }

    public function setDevice(string $device): self
    {
        $this->device = $device;

        return $this;
    }
}
