<?php

namespace App\Controller;

use App\Entity\Idea;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
/**
 * @Route("/api")
 */
class RestController extends AbstractController
{
    private $notFound = ["error"=>"not found"];
    /**
     * @Route("/random", name="api_random_post", methods={"GET", "POST"})
     * @param EntityManagerInterface $em
     * @return RedirectResponse|Response
     * @throws \Exception
     */
    public function random(EntityManagerInterface $em) {
        $ideaRepo = $this->getDoctrine()->getRepository(Idea::class);
        $ideaZ = $ideaRepo->random($em);

        if($ideaZ){
            return  $this->json($ideaZ);
        }
        return $this->json($this->notFound);

    }

    /**
     * @Route("/get/{id}", requirements={"id"="\d+"}, name="api_idea_id", methods={"GET", "POST"})
     * @param EntityManagerInterface $em
     * @param Request $request
     * @param $id
     * @return RedirectResponse|Response
     * @throws \Exception
     */
    public function idea($id, EntityManagerInterface $em, Request $request) {
        $ideaRepo = $this->getDoctrine()->getRepository(Idea::class);
        $idea = $ideaRepo->find($id);

        if($idea){
            return  $this->json($idea);
        }
        return $this->json($this->notFound);
    }

    /**
     * @Route("/get/all", name="api_idea_all", methods={"GET", "POST"})
     * @param EntityManagerInterface $em
     * @param Request $request
     * @return RedirectResponse|Response
     * @throws \Exception
     */
    public function ideas(EntityManagerInterface $em, Request $request) {
        $ideaRepo = $this->getDoctrine()->getRepository(Idea::class);
        $ideas = $ideaRepo->findAll();

        if($ideas){
            $jSonIdeas = [];
            foreach ($ideas as $idea){
                array_push($jSonIdeas, $idea->jsonSerialize());
            }
            return  $this->json($jSonIdeas);
        }
        return $this->json($this->notFound);
    }

    /**
     * @Route("/get/all/{limit}/{offset}", requirements={"limit"="\d+", "offset"="\d*"}, name="api_idea_all_limit", methods={"GET", "POST"})
     * @param EntityManagerInterface $em
     * @param $limit
     * @param $offset
     * @return RedirectResponse|Response
     * @throws \Exception
     */
    public function ideasLimit(EntityManagerInterface $em, $limit = 25, $offset = 0) {
        if($limit > 100 || $limit == ''){
            $limit = 100;
        }
        if($offset == '' || $offset == null){
            $offset = 0;
        }
        $ideaRepo = $this->getDoctrine()->getRepository(Idea::class);
        $ideas = $ideaRepo->all($limit, $offset, $em);

        if($ideas){
            $jSonIdeas = [];
            foreach ($ideas as $idea){
                array_push($jSonIdeas, $idea->jsonSerialize());
            }
            return  $this->json($jSonIdeas);
        }
        return $this->json($this->notFound);
    }
}
