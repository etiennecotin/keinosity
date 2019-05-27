<?php

namespace App\Controller;

use App\Entity\User;
use App\Utils\TokenModel;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class AuthController extends AbstractController
{

    /**
     * @param Request $request
     * @param UserPasswordEncoderInterface $encoder
     * @param TokenModel $tokenModel
     * @param ValidatorInterface $validator
     * @return Response
     * @throws \Exception
     */
    public function register(Request $request, UserPasswordEncoderInterface $encoder, TokenModel $tokenModel, ValidatorInterface $validator): Response
    {
        $em = $this->getDoctrine()->getManager();

        $email = $request->request->get('_username');
        $password = $request->request->get('_password');

        $user = new User();

        $username = explode('@', $user->getEmail());
        $user->setUsername($username[0]);
        $user->setEmail($email);
        $user->setPassword($encoder->encodePassword($user, $password));
        $user->setToken($tokenModel->generateToken());
        $errors = $validator->validate($user);
        if (count($errors) > 0) {
            /*
             * Uses a __toString method on the $errors variable which is a
             * ConstraintViolationList object. This gives us a nice string
             * for debugging.
             */
            $errorsString = (string) $errors;

            return new Response($errorsString);
        }
        $em->persist($user);
        $em->flush();
        return new Response(sprintf('User %s successfully created', $user->getEmail()));
    }

    /**
     * @return Response
     */
    public function api(): Response
    {
        return new Response(sprintf('Logged in as %s', $this->getUser()->getUsername()));
    }
}
