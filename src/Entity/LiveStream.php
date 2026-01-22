<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Post;
use App\Controller\LiveStream\CreateLiveStreamAction;
use App\Controller\LiveStream\GetMyStreamsAction;
use App\Controller\LiveStream\LiveStreamActiveAction;
use App\Controller\LiveStream\StartLiveStreamAction;
use App\Controller\LiveStream\StopLiveStreamAction;
use App\Repository\LiveStreamRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: LiveStreamRepository::class)]
#[ApiResource(
    operations: [
        new GetCollection(),
        new GetCollection(
            uriTemplate: '/live/active',
            controller: LiveStreamActiveAction::class,
        ),
        new GetCollection(
            uriTemplate: '/live/my-streams',
            controller: GetMyStreamsAction::class,
        ),
        new Post(
            uriTemplate: '/live/create',
            controller: CreateLiveStreamAction::class,
        ),
        new Post(
            uriTemplate: '/live/start/{id}',
            controller: StartLiveStreamAction::class,

        ),
        new Post(
            uriTemplate: '/live/stop/{id}',
            controller: StopLiveStreamAction::class,
        ),
        new Get(),
        new Patch(),
        new Delete()
    ],
    normalizationContext: ['groups' => ['live_stream:read', 'live_streams:read']],
    denormalizationContext: ['groups' => ['live_stream:write']],
)]
class LiveStream
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['live_streams:read', 'live_stream:write'])]

    private ?string $title = null;

    #[ORM\ManyToOne(inversedBy: 'liveStreams')]
    #[Groups(['live_streams:read'])]
    private ?User $user = null;

    #[ORM\Column(length: 500)]
    #[Groups(['live_streams:read'])]
    private ?string $channelArn = null;

    #[ORM\Column(length: 500, nullable: true)]
    #[Groups(['live_streams:read'])]
    private ?string $ingestEndpoint = null;

    #[ORM\Column(length: 500, nullable: true)]
    #[Groups(['live_streams:read'])]
    private ?string $playbackUrl = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['live_streams:read'])]
    private ?string $streamKey = null;

    #[ORM\Column(nullable: true)]
    #[Groups(['live_streams:read'])]
    private ?bool $isActive = null;

    #[ORM\Column(nullable: true)]
    #[Groups(['live_streams:read'])]
    private ?\DateTimeImmutable $createdAt;

    public function __construct()
    {
        $this->createdAt = new \DateTimeImmutable();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(?string $title): static
    {
        $this->title = $title;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): static
    {
        $this->user = $user;

        return $this;
    }

    public function getChannelArn(): ?string
    {
        return $this->channelArn;
    }

    public function setChannelArn(string $channelArn): static
    {
        $this->channelArn = $channelArn;

        return $this;
    }

    public function getIngestEndpoint(): ?string
    {
        return $this->ingestEndpoint;
    }

    public function setIngestEndpoint(?string $ingestEndpoint): static
    {
        $this->ingestEndpoint = $ingestEndpoint;

        return $this;
    }

    public function getPlaybackUrl(): ?string
    {
        return $this->playbackUrl;
    }

    public function setPlaybackUrl(?string $playbackUrl): static
    {
        $this->playbackUrl = $playbackUrl;

        return $this;
    }

    public function getStreamKey(): ?string
    {
        return $this->streamKey;
    }

    public function setStreamKey(?string $streamKey): static
    {
        $this->streamKey = $streamKey;

        return $this;
    }

    public function isActive(): ?bool
    {
        return $this->isActive;
    }

    public function setIsActive(?bool $isActive): static
    {
        $this->isActive = $isActive;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(?\DateTimeImmutable $createdAt): static
    {
        $this->createdAt = $createdAt;

        return $this;
    }
}
