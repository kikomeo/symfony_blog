<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Entity\Article;
use App\Repository\UserRepository;
use App\Repository\ArticleRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class FrontController extends AbstractController
{
    #[Route('/', name: 'app_front')]
    public function index(): Response
    {
        return $this->render('front/index.html.twig', [
            'controller_name' => 'FrontController',
        ]);
    }

    #[Route('/inscription', name: 'app_subscribe')]
    public function subscribe(Request $request, UserRepository $userRepository, UserPasswordHasherInterface $hasher)
    {
        $user = new User();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if( $form->isSubmitted() && $form->isValid() ){

            $password = $user->getPassword();
            $passwordHashed = $hasher->hashPassword($user, $password);
            $user->setPassword($passwordHashed);

            $userRepository->save($user, TRUE);

            $this->addFlash("success", "Votre inscription a bien été prise en compte");

            return $this->redirectToRoute('app_index');

        }

        return $this->render('front/subscribe.html.twig');
    }
}
