<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegisterType;
use App\Form\SearchType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserController extends CommonController
{
    /**
     * @Route("/register", name="register")
     */
    public function register(Request $request, EntityManagerInterface $em, UserPasswordEncoderInterface $encoder)
    {
        $this->searchForm = $this->createForm(SearchType::class);
        $user = new User();
        $registerForm = $this->createForm(RegisterType::class,$user);
        $registerForm->handleRequest($request);
        if($registerForm->isSubmitted() && $registerForm->isValid()){
            $user->setDateCreated(new \DateTime());
            $d = new \DateTime();
            $basic = base64_encode($user->getUsername().":".$user->getPassword());
            $user->setBasicEnc($basic);
            $hash = $encoder->encodePassword($user, $user->getPassword());
            $user->setPassword($hash);
            $em->persist($user);
            $em->flush();
            return $this->redirectToRoute('login');
        }
        return $this->render('user/register.html.twig', [
            'controller_name' => 'UserController',
            'register_form' => $registerForm->createView(),
            'search_form' => $this->searchForm->createView(),
        ]);
    }

    /**
     * @Route("/login", name="login")
     * @param Request $request
     * @param EntityManagerInterface $em
     * @param UserPasswordEncoderInterface $encoder
     * @return Response
     */
    public function login(Request $request, EntityManagerInterface $em, UserPasswordEncoderInterface $encoder){
        $this->searchForm = $this->createForm(SearchType::class);
        return $this->render("user/login.html.twig", [
            'controller_name' => 'Login',
            'search_form' => $this->searchForm->createView(),
        ]);
    }
    /**
     * @Route("/logout", name="logout")
     */
    public function logout(){

    }
    /**
     * @Route("/profile", name="profile")
     */
    public function profile(){
        $this->searchForm = $this->createForm(SearchType::class);
        return $this->render("user/profile.html.twig", [
            'controller_name' => 'Profile',
            'search_form' => $this->searchForm->createView(),
            'user' => $this->getUser(),
        ]);
    }
    /**
     * @Route("/delete", name="delete")
     */
    public function delete(EntityManagerInterface $em , Request $request){
        if($this->isGranted('ROLE_USER')){
            $user = $this->getUser();
            //$eUser = $em->getRepository(User::class)->findOneBy(['username'=> $user->getUsername()]);
            $em->remove($user);
            $em->flush();
            $this->get('security.token_storage')->setToken(null);
            $request->getSession()->invalidate();

        }
        return $this->redirectToRoute('home');
    }
}
