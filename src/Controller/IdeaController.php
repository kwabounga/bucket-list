<?php

namespace App\Controller;

use App\Entity\Category;
use App\Entity\Checked;
use App\Entity\Idea;
use App\Form\IdeaType;
use App\Form\SearchType;
use App\Repository\IdeaRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Query\Expr\Math;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
/**
 * @Route("/idea")
 */
class IdeaController extends CommonController
{
    /**
     * @Route("/list", name="idea_list")
     * @return Response
     */
    public function list(Request $request, EntityManagerInterface $em)
    {

        $checkedJson = null;
        if($this->isGranted('ROLE_USER')){

            $checkedRepo = $this->getDoctrine()->getRepository(Checked::class);
            $checkeds = $checkedRepo->getChecked($this->getUser()->getId(), $em);
            $checkedJson = array();
            forEach ($checkeds as $checked){
               array_push($checkedJson,$checked->jsonSerialize());
            }

        }
        $this->searchForm = $this->createForm(SearchType::class);
        $this->searchForm->handleRequest($request);

        /*categories*/
        $catRepo = $this->getDoctrine()->getRepository(Category::class);
        $cats = $catRepo->findAll();

        /*ideas*/
        $ideaRepo = $this->getDoctrine()->getRepository(Idea::class);
        $ideas = $ideaRepo->findAll();

        $searchTag = '';
        if($this->searchForm->isSubmitted()){
            $title = $this->searchForm->getData()->getTitle();

            $datas = $ideaRepo->search($title);
            if($datas == null){
                $datas = $ideaRepo->search($title);
                if($datas == null){
                    $this->addFlash('warning', 'Aucune idée de type '.$title.' n\'a été trouvé');
                } else {
                    $ideas = $datas;
                }
            } else {
                $ideas = $datas;
                $searchTag = $title;
            }
        }


        dump($checkedJson);
        $arg = [
            'controller_name' => 'list',
            'ideas' => $ideas,
            'search_form' => $this->searchForm->createView(),
            'checked_json' => $checkedJson,
            'cats' => $cats,
            'search_tag' => $searchTag,
        ];
        return $this->render('idea/list.html.twig', $arg);
    }

    /**
     * @Route("/detail/{id}", name="idea_detail", requirements={"id"="\d+"})
     * @param $id - the detail id
     * @return Response
     */
    public function detail($id=0)
    {


        $this->searchForm = $this->createForm(SearchType::class);
        dump($id);
        $ideaRepo = $this->getDoctrine()->getRepository(Idea::class);
        $idea = $ideaRepo->find($id);
        $checkedRepo = $this->getDoctrine()->getRepository(Checked::class);
        $checked = $checkedRepo->findOneBy(['idea' => $idea, 'user' => $this->getUser()]);
        return $this->render('idea/detail.html.twig', [
            'controller_name' => 'detail',
            'id' => $id,
            'idea' => $idea,
            'search_form' => $this->searchForm->createView(),
            'checked' => $checked,
        ]);
    }



    /**
     * @Route("/add", name="idea_add")
     * @param EntityManagerInterface $em
     * @param Request $request
     * @return Response
     */
    public function add(EntityManagerInterface $em, Request $request)
    {
        if($this->isGranted('ROLE_USER')){

            $this->searchForm = $this->createForm(SearchType::class);
            $idea = new Idea();
            $ideaForm = $this->createForm(IdeaType::class, $idea);
            $ideaForm->handleRequest($request);
            if ($ideaForm->isSubmitted() ){
                if($ideaForm->isValid()){
                    $idea->setIsPublished(true);
                    $idea->setDateCreated(new \DateTime());

                    $em->persist($idea);
                    $em->flush();

                    $this->addFlash('success', 'Idée Crée! il n\'y a plus qu\'à s\'y mettre !!');

                    return $this->redirectToRoute('idea_detail',[
                        'id'=>$idea->getId(),
                    ]);
                } else {
                    $this->addFlash('warning', 'Un problème est survenu');
                }

            }



            return $this->render('idea/add.html.twig', [
                'controller_name' => 'add',
                'idea_form' => $ideaForm->createView(),
                'search_form' => $this->searchForm->createView(),
            ]);
        } else {
            $this->addFlash('warning', 'Vous ne pouvez pas ajouter d\'idées sans etre enregistré');
            return $this->redirectToRoute('home');
        }


    }
    /**
     * @Route("*", name="idea_default")
     * @return Response
     */
    public function default()
    {
        // @todo: recuperer les series en bases donnée
        return $this->redirectToRoute('home');
    }
}
