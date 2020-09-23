<?php

namespace App\Entity;
use JsonSerializable;
use App\Repository\IdeaRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
/**
 * @ORM\Entity(repositoryClass=IdeaRepository::class)
 */
class Idea implements JsonSerializable
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
     * @Assert\NotBlank()
     * @Assert\Length(min=2, minMessage="La longueur doit faire au moins 2 caracteres",max=250, maxMessage="le titre ne doit pas dépasser 250 caracteres")
     * @ORM\Column(type="string", length=250)
     */
    private $title;

    /**
     * @Assert\NotBlank()
     * @Assert\Length(min=30, minMessage="La description doit faire au moins 30 caracteres",max=400, maxMessage="la descrition ne  doit pas dépasser 400 caracteres")
     * @ORM\Column(type="text")
     */
    private $description;

    /**
     * @Assert\NotBlank()
     * @Assert\Length(min=3, minMessage="La longueur doit faire au moins 3 caracteres",max=250, maxMessage="le nom ne doit pas dépasser 250 caracteres")
     * @ORM\Column(type="string", length=250)
     */
    private $author;

    /**
     * @ORM\Column(type="datetime")
     */
    private $dateCreated;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isPublished;

    /**
     * @return mixed
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param mixed $title
     */
    public function setTitle($title): void
    {
        $this->title = $title;
    }

    /**
     * @return mixed
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param mixed $description
     */
    public function setDescription($description): void
    {
        $this->description = $description;
    }

    /**
     * @return mixed
     */
    public function getAuthor()
    {
        return $this->author;
    }

    /**
     * @param mixed $author
     */
    public function setAuthor($author): void
    {
        $this->author = $author;
    }

    /**
     * @return mixed
     */
    public function getDateCreated()
    {
        return $this->dateCreated;
    }

    /**
     * @param mixed $dateCreated
     */
    public function setDateCreated($dateCreated): void
    {
        $this->dateCreated = $dateCreated;
    }

    /**
     * @return mixed
     */
    public function getIsPublished()
    {
        return $this->isPublished;
    }

    /**
     * @param mixed $isPublished
     */
    public function setIsPublished($isPublished): void
    {
        $this->isPublished = $isPublished;
    }

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Category", inversedBy="ideas", cascade={"persist"});
     */
    private $category;

    /**
     * @return mixed
     */
    public function getCategory()
    {
        return $this->category;
    }

    /**
     * @param mixed $category
     */
    public function setCategory($category): void
    {
        $this->category = $category;
    }

    public function jsonSerialize()
    {
        return array(
            'id'=> $this->getId(),
            'title' => $this->getTitle(),
            'author'=> $this->getAuthor(),
            'description'=> $this->getDescription(),
            'dateCreated'=> $this->getDateCreated(),
        );
    }

}
