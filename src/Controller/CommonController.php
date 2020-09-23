<?php

namespace App\Controller;

use App\Form\SearchType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

class CommonController extends AbstractController
{
    public $searchForm = null;
    public $session;
    public function __construct(SessionInterface $session)
    {
        $this->session = $session;
    }

}
