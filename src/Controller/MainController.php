<?php
namespace  App\Controller;

use App\Form\SearchType;
use http\Env\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MainController extends CommonController
{
    /**
     * @Route("/", name="home")
     * @return Response
     */
    public function home()
    {
        $this->searchForm = $this->createForm(SearchType::class);

        if($this->session->get('first',null) == null){
            $this->session->set('first', 'no');
            if($this->isGranted('IS_AUTHENTICATED_REMEMBERED')){
                $this->addFlash('success', 'Bienvenue dans Bucket-list '.$this->getUser()->getUsername());
            } else {
                $this->addFlash('infos', 'Bienvenue Utilisateur Anonyme');
            }
        }
        return $this-> render("default/home.html.twig", [
            'controller_name' => 'home',
            'search_form' => $this->searchForm->createView(),
        ]);
    }
    /**
     * @Route("/about", name="about")
     * @return Response
     */
    public function about()
    {
        $this->searchForm = $this->createForm(SearchType::class);
        $this->addFlash('warning', 'Attention !');
        $this->addFlash('infos', 'ceci est la page la plus importante!');
        $this->addFlash('success', 'N\'hésitez surtout pas a nous contacter');
        return $this-> render("default/about.html.twig", [
            'controller_name' => 'about',
            'search_form' => $this->searchForm->createView(),
        ]);
    }
}

