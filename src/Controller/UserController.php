<?php

namespace App\Controller;

use LogicException;
use App\Entity\User;
use App\Form\RegistrationFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserController extends AbstractController
{
    #[Route('/user/{username}', name: 'app_profile')]
    public function index(?User $user): Response
    {   
        if (!$user) {
            return $this->redirectToRoute('app_home');
        }

        return $this->render('user/index.html.twig',[
            'user' => $user
        ]);
    }

    





}
