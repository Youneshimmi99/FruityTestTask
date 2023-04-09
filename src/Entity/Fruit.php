<?php

namespace App\Entity;

use App\Repository\FruitRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=FruitRepository::class)
 * @ORM\Table(name="fruit")
 */
class Fruit
{

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string")
     */
    private $name;

    /**
     * @ORM\Column(type="string")
     */
    private $genus;

    /**
     * @ORM\Column(type="string")
     */
    private $family;

    /**
     * @ORM\Column(name="fruit_order", type="string")
     */
    private $order;

    /**
     * @ORM\Column(type="boolean", options={"default" : false}) 
     */ 
    private $favorite = false;

    /**
     * @ORM\OneToOne(targetEntity="Nutrition", mappedBy="fruit", cascade={"persist"})
     */
    private $nutrition;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getGenus(): ?string
    {
        return $this->genus;
    }

    public function setGenus(string $genus): self
    {
        $this->genus = $genus;

        return $this;
    }

    public function getFamily(): ?string
    {
        return $this->family;
    }

    public function setFamily(string $family): self
    {
        $this->family = $family;

        return $this;
    }

    public function getOrder(): ?string
    {
        return $this->order;
    }

    public function setOrder(string $order): self
    {
        $this->order = $order;

        return $this;
    }

    public function getFavorite(): bool
    {
        return $this->favorite;
    } 
    
    public function setFavorite(bool $favorite): self
    {
        $this->favorite = $favorite; 
        
        return $this;
    } 

    public function getNutrition(): ?Nutrition
    {
        return $this->nutrition;
    }

    public function setNutrition(Nutrition $nutrition): self
    {
        $this->nutrition = $nutrition;

        // set the owning side of the relation if necessary
        if ($nutrition->getFruit() !== $this) {
            $nutrition->setFruit($this);
        }

        return $this;
    }
}
