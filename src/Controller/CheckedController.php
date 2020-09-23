<?php

namespace App\Controller;

use App\Entity\Checked;

use App\Entity\Idea;
use Doctrine\ORM\EntityManagerInterface;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class CheckedController extends AbstractController
{
    /**
     * @Route("/check/{id}", name="check")
     */
    public function check($id,  EntityManagerInterface  $em)
    {

        $this->denyAccessUnlessGranted(['ROLE_USER']);

        $ideaRepo = $this->getDoctrine()->getRepository(Idea::class);

        $ckd = new Checked();
        $ckd->setIdea($ideaRepo->find($id));
        $ckd->setUser($this->getUser());
        $em->persist($ckd);
        $em->flush();
        return $this->redirectToRoute('idea_list', [ '_fragment'=> 'idea_'.$id ]);
    }
    /**
     * @Route("/uncheck/{id}", name="uncheck")
     */
    public function uncheck($id,  EntityManagerInterface  $em)
    {
        $this->denyAccessUnlessGranted(['ROLE_USER']);
        $ideaRepo = $this->getDoctrine()->getRepository(Idea::class);
        $checkRepo = $this->getDoctrine()->getRepository(Checked::class);
        $idea = $ideaRepo->find($id);
        $ckd = $checkRepo->findOneBy(['idea' => $idea, 'user'=> $this->getUser()]);
        $em->remove($ckd);
        $em->flush();
        return $this->redirectToRoute('idea_list');
    }
}
