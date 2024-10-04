<?php

namespace App\Entity;

use App\Entity\Trait\SoftDeletableTrait;
use App\Entity\Trait\TimestampableTrait;
use App\Repository\NoticeRepository;
use DateTimeImmutable;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: NoticeRepository::class)]
class Notice
{
    use TimestampableTrait;
    use SoftDeletableTrait;
    
    CONST STATUT = [
        'En attente',
        'Validé',
        'Refusé'
    ];

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 50)]
    private ?string $nickname = null;

    #[ORM\Column(length: 50)]
    private ?string $comment = null;

    #[ORM\Column]
    private ?int $status = null;

    #[ORM\ManyToOne(inversedBy: 'notices')]
    #[ORM\JoinColumn(nullable: true)]
    private ?User $user = null;

    public function __construct()
    {
        $this->status = 0;
        $this->createdAt = new DateTimeImmutable();
        $this->updatedAt = new DateTimeImmutable();
        $this->setDeletedAt(null);
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNickname(): ?string
    {
        return $this->nickname;
    }

    public function setNickname(string $nickname): static
    {
        $this->nickname = $nickname;

        return $this;
    }

    public function getComment(): ?string
    {
        return $this->comment;
    }

    public function setComment(string $comment): static
    {
        $this->comment = $comment;

        return $this;
    }

    public function status(): ?int
    {
        return $this->status;
    }

    public function setVisible(int $status): static
    {
        $this->status = $status;

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
}
