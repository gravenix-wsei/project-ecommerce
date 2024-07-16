<?php

namespace App\Entity;

use App\Repository\CategoryRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Types\UuidType;
use Symfony\Component\Uid\Uuid;

#[ORM\Entity(repositoryClass: CategoryRepository::class)]
#[ORM\UniqueConstraint(fields: ['parentCategory', 'name'])]
class Category
{
    #[ORM\Id]
    #[ORM\Column(type: UuidType::NAME, unique: true)]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator(class: 'doctrine.uuid_generator')]
    private ?Uuid $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $description = null;

    #[ORM\Column]
    private ?bool $visible = null;

    #[ORM\ManyToOne(targetEntity: self::class, inversedBy: 'childCategories')]
    private ?self $parentCategory = null;

    /**
     * @var Collection<int, self>
     */
    #[ORM\OneToMany(targetEntity: self::class, mappedBy: 'parentCategory')]
    private Collection $childCategories;

    public function __construct()
    {
        $this->childCategories = new ArrayCollection();
    }

    public function getId(): ?Uuid
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function isVisible(): ?bool
    {
        return $this->visible;
    }

    public function setVisible(bool $visible): static
    {
        $this->visible = $visible;

        return $this;
    }

    public function getParentCategory(): ?self
    {
        return $this->parentCategory;
    }

    public function setParentCategory(?self $parentCategory): static
    {
        $this->parentCategory = $parentCategory;

        return $this;
    }

    /**
     * @return Collection<int, self>
     */
    public function getChildCategories(): Collection
    {
        return $this->childCategories;
    }

    public function addParentCategory(self $parentCategory): static
    {
        if (!$this->childCategories->contains($parentCategory)) {
            $this->childCategories->add($parentCategory);
            $parentCategory->setParentCategory($this);
        }

        return $this;
    }

    public function removeParentCategory(self $parentCategory): static
    {
        if ($this->childCategories->removeElement($parentCategory)) {
            // set the owning side to null (unless already changed)
            if ($parentCategory->getParentCategory() === $this) {
                $parentCategory->setParentCategory(null);
            }
        }

        return $this;
    }
}
