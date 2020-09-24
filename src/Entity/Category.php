<?php

namespace App\Entity;

use App\Repository\CategoryRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\JoinColumn;
use JsonSerializable;

/**
 * @ORM\Entity(repositoryClass=CategoryRepository::class)
 */
class Category implements  JsonSerializable
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    public function getId(): ?int
    {
        return $this->id;
    }
    /**
     * @ORM\Column(type="string", length=250)
     */
    private $name;

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param mixed $name
     */
    public function setName($name): void
    {
        $this->name = $name;
    }

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Idea", mappedBy="category", cascade={"persist"})
     */
    private $ideas;

    /**
     * @return mixed
     */
    public function getIdeas()
    {
        return $this->ideas;
    }

    /**
     * @param mixed $ideas
     */
    public function setIdeas(Idea $ideas = null): void
    {
        $this->ideas = $ideas;
    }


    public function __construct(){
        $this->ideas = new ArrayCollection();
    }
    public function jsonSerialize()
    {
        return array(
            'id'=> $this->getId(),
            'name' => $this->getName(),
        );
    }
}
