<?php

namespace App\Controller;

use App\Entity\Idea;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
/**
 * @Route("/api/v1")
 */
class RestController extends AbstractController
{
    private $notFound = ["error"=>"not found"];
    private $needAuth = ["error"=>"forbidden, need to authenticate"];
    private $wrongFormat = ["error"=>"Wrong data format"];
    /**
     * @Route("/idea/random", name="api_random_post", methods={"GET"})
     * @param EntityManagerInterface $em
     * @return RedirectResponse|Response
     * @throws Exception
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
     * @Route("/idea/{id}", requirements={"id"="\d+"}, name="api_idea_id", methods={"GET"})
     * @param $id
     * @return RedirectResponse|Response
     */
    public function idea($id) {
        $ideaRepo = $this->getDoctrine()->getRepository(Idea::class);
        $idea = $ideaRepo->find($id);

        if($idea){
            return  $this->json($idea);
        }
        return $this->json($this->notFound);
    }

    /**
     * @Route("/idea/all", name="api_idea_all", methods={"GET"})
     * @return RedirectResponse|Response
     */
    public function ideas() {
        if($this->isGranted('ROLE_USER')){
            $jSonIdeas = ['user'=>$this->getUser()->getUsername()];
        }else {
            $jSonIdeas = [];
        }
        $ideaRepo = $this->getDoctrine()->getRepository(Idea::class);
        $ideas = $ideaRepo->findAll();

        if($ideas){

            foreach ($ideas as $idea){
                array_push($jSonIdeas, $idea->jsonSerialize());
            }
            return  $this->json($jSonIdeas);
        }
        return $this->json($this->notFound);
    }

    /**
     * @Route("/idea/all/{limit}/{offset}", requirements={"limit"="\d+", "offset"="\d*"}, name="api_idea_all_limit", methods={"GET"})
     * @param EntityManagerInterface $em
     * @param $limit
     * @param $offset
     * @return RedirectResponse|Response
     * @throws Exception
     */
    public function ideasLimit(EntityManagerInterface $em, $limit = 25, $offset = 0) {

        if($this->isGranted('ROLE_USER')){
            $jSonIdeas = ['user'=>$this->getUser()->getUsername()];
        }else {
            $jSonIdeas = [];
        }

        if($limit > 100 || $limit == ''){
            $limit = 100;
        }
        if($offset == '' || $offset == null){
            $offset = 0;
        }
        $ideaRepo = $this->getDoctrine()->getRepository(Idea::class);
        $ideas = $ideaRepo->all($limit, $offset, $em);

        if($ideas){
//            $jSonIdeas = [];
            foreach ($ideas as $idea){
                array_push($jSonIdeas, $idea->jsonSerialize());
            }
            return  $this->json($jSonIdeas);
        }
        return $this->json($this->notFound);
    }
    /**
     * @Route("/idea/insert", name="api_idea_insert",methods={"PUT"})
     */
    public function insert(Request $request, EntityManagerInterface $em){
        if($this->isGranted('ROLE_USER')){
            $params = $request->query->all();
            if($params['title'] == null || $params['author'] == null || $params['description'] == null){
                return $this->json($this->wrongFormat);
            } else {
                $idea = new Idea();
                $idea->setTitle($params['title']);
                $idea->setAuthor($params['author']);
                $idea->setDescription($params['description']);
                $idea->setIsPublished(true);
                $idea->setDateCreated(new \DateTime());

                $em->persist($idea);
                $em->flush();
            }
            return $this->json(['user'=>$this->getUser()->getUsername(), 'data' => $idea->jsonSerialize()]);
        }else {
            return $this->json($this->needAuth);
        }
    }
}
