<?php

namespace App\Entity;

use App\Repository\TodoRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: TodoRepository::class)]
class Todo
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['todo:read'])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: 'Le titre est requis.')]
    #[Assert\Length(
        max: 255,
        maxMessage: 'La longueur maximale du titre est de 255 caractères.'
    )]
    #[Groups(['todo:read', 'todo:write'])]
    private ?string $title = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    #[Groups(['todo:read', 'todo:write'])]
    private ?string $descriptionLongue = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['todo:read'])]
    private ?string $resume = null;

    #[ORM\Column]
    #[Groups(['todo:read', 'todo:write'])]
    private ?bool $done = null;

    #[ORM\Column(nullable: true)]
    #[Groups(['todo:read', 'todo:write'])]
    private ?\DateTimeImmutable $dueAt = null;

    #[ORM\Column]
    #[Groups(['todo:read'])]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column]
    #[Groups(['todo:read'])]
    private ?\DateTimeImmutable $updatedAt = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): static
    {
        $this->title = $title;

        return $this;
    }

    public function getDescriptionLongue(): ?string
    {
        return $this->descriptionLongue;
    }

    public function setDescriptionLongue(?string $descriptionLongue): static
    {
        $this->descriptionLongue = $descriptionLongue;

        return $this;
    }

    public function getResume(): ?string
    {
        return $this->resume;
    }

    public function setResume(?string $resume): static
    {
        $this->resume = $resume;

        return $this;
    }

    public function isDone(): ?bool
    {
        return $this->done;
    }

    public function setDone(bool $done): static
    {
        $this->done = $done;

        return $this;
    }

    public function getDueAt(): ?\DateTimeImmutable
    {
        return $this->dueAt;
    }

    public function setDueAt(?\DateTimeImmutable $dueAt): static
    {
        $this->dueAt = $dueAt;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): static
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(\DateTimeImmutable $updatedAt): static
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }
}
