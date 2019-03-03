<?php

namespace App\Controller;

use App\Utils\TokenModel;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class ApiController
 * @package App\Controller
 * @Route("/api")
 */
class ApiController extends AbstractController
{
    /**
     * @Route("/generate-token", name="api-token")
     * @param TokenModel $tokenModel
     * @return Response
     * @throws \Exception
     */
    public function index(TokenModel $tokenModel)
    {
        $token = $tokenModel->generateToken();

        $response = new Response();
        $response->setContent(json_encode([
            'token' => $token,
        ]));
        $response->headers->set('Content-Type', 'application/json');

        return $response;
    }
}
