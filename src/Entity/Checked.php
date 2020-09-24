<?php

namespace App\Entity;

use App\Repository\CheckedRepository;
use Doctrine\ORM\Mapping as ORM;
use JsonSerializable;

/**
 * @ORM\Entity(repositoryClass=CheckedRepository::class)
 */
class Checked implements JsonSerializable
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;
    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="checkeds");
     */
    private $user;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Idea");
     */
    private $idea;

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id): void
    {
        $this->id = $id;
    }

    /**
     * @return mixed
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @param mixed $user
     */
    public function setUser($user): void
    {
        $this->user = $user;
    }

    /**
     * @return mixed
     */
    public function getIdea()
    {
        return $this->idea;
    }

    /**
     * @param mixed $idea
     */
    public function setIdea($idea): void
    {
        $this->idea = $idea;
    }

    public function jsonSerialize()
    {

        return array(
            'id' =>	$this->getId(),
            'user_id' =>	$this->getUser()->getId(),
            'idea_id' =>	$this->getIdea()->getId(),
        );
    }


}
