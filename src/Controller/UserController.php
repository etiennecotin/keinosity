<?php

namespace App\Controller;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * Class UserController
 * @package App\Controller
 * @Route("/user")
 */
class UserController extends AbstractController
{
    /**
     * @Route("/my-profil", name="my_profil")
     * @param UserInterface $user
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function myProfil(UserInterface $user)
    {
        /** @var User $user */

        return $this->render('user/index.html.twig', [
            'controller_name' => 'UserController',
        ]);
    }

    /**
     * @Route("/{id}", name="user_profil")
     * @param User $id
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function userProfil(User $id)
    {
        return $this->render('user/index.html.twig', [
            'controller_name' => 'UserController',
        ]);
    }
}
