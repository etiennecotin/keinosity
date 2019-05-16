<?php

namespace App\Services;


use Psr\Log\LoggerInterface;
use Symfony\Component\Routing\RouterInterface;
use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

class EmailService
{
    /** @var \Swift_Mailer */
    private $mailer;
    /** @var Environment  */
    private $twig;
    /** @var RouterInterface  */
    private $router;
    /** @var LoggerInterface */
    private $logger;

    /**
     * Automatic inject in all service with content of variable sender_email from parameters.yaml
     * @var string
     */
    private $senderEmail;

    public function __construct(\Swift_Mailer $mailer, Environment $twig, RouterInterface $router, LoggerInterface $logger, $senderEmail)
    {
        $this->mailer = $mailer;
        $this->twig = $twig;
        $this->router = $router;
        $this->logger = $logger;
        $this->senderEmail = $senderEmail;
    }

    /**
     * @param string $receiver Email of receiver
     * @throws \Exception
     */
    public function sendRegistration(string $receiver): void
    {
        try {
            $message = (new \Swift_Message('Hello from keinosity'))
                ->setFrom($this->senderEmail)
                ->setTo($receiver)
                ->setBody(
                    $this->twig->render(
                        'emails/welcome.html.twig', [
                            'receiver' => $receiver
                        ]
                    ),
                    'text/html'
                );
            $this->mailer->send($message);
        } catch (\Exception $e) {
            $logDate = new \DateTime();
            $this->logger->error('['.$logDate->format('d/m/Y').'] Email was not send');
        }
    }
}
