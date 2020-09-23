<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 * @ORM\Table(name="app_user")
 */
class User implements UserInterface
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;
    /**
     * @Assert\NotBlank()
     * @Assert\Length(min=3, minMessage="le pseudo doit faire au moins 3 carateres", max=50, maxMessage="le pseudo doit faire 50 carateres maximum")
     * @ORM\Column(type="string", length=50, unique=true);
     */
    private $username;
    /**
     * @Assert\NotBlank()
     * @Assert\Length(min=3, minMessage="le mot de masse doit faire au moins 6 carateres", max=255, maxMessage="le mot de passe doit faire 255 carateres maximum")
     * @ORM\Column(type="string", length=255);
     */
    private $password;
    /**
     * @Assert\NotBlank()
     * @Assert\Length(min=3, minMessage="le mail doit faire au moins 8 carateres", max=255, maxMessage="le mail doit faire 255 carateres maximum")
     * @ORM\Column(type="string", length=255, unique=true);
     */
    private $email;
    /**
     * @ORM\Column(type="datetime");
     */
    private $dateCreated;

    // pas sauvegarde en base
    private $roles;

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return mixed
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * @param mixed $username
     */
    public function setUsername($username): void
    {
        $this->username = $username;
    }

    /**
     * @return mixed
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * @param mixed $password
     */
    public function setPassword($password): void
    {
        $this->password = $password;
    }

    /**
     * @return mixed
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param mixed $email
     */
    public function setEmail($email): void
    {
        $this->email = $email;
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

    public function getRoles()
    {
        return ['ROLE_USER'];
    }
    // symfony gere ca
    public function getSalt(){ return null; }
    public function eraseCredentials(){ return null; }



}
