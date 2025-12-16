<?php

namespace App\Entity;

use App\Repository\ImageRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ImageRepository::class)]
class Image
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $file = null;

    #[ORM\Column(length: 255)]
    private ?string $numLikes = null;

    #[ORM\Column(length: 255)]
    private ?string $numViews = null;

    #[ORM\Column]
    private ?int $numDownloads = null;

    #[ORM\ManyToOne(inversedBy: 'images')]
    private ?Category $category = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFile(): ?string
    {
        return $this->file;
    }

    public function setFile(string $file): static
    {
        $this->file = $file;

        return $this;
    }

    public function getNumLikes(): ?string
    {
        return $this->numLikes;
    }

    public function setNumLikes(string $numLikes): static
    {
        $this->numLikes = $numLikes;

        return $this;
    }

    public function getNumViews(): ?string
    {
        return $this->numViews;
    }

    public function setNumViews(string $numViews): static
    {
        $this->numViews = $numViews;

        return $this;
    }

    public function getNumDownloads(): ?int
    {
        return $this->numDownloads;
    }

    public function setNumDownloads(int $numDownloads): static
    {
        $this->numDownloads = $numDownloads;

        return $this;
    }

    public function getCategory(): ?Category
    {
        return $this->category;
    }

    public function setCategory(?Category $category): static
    {
        $this->category = $category;

        return $this;
    }
}
