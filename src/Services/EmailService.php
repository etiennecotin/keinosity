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

    const SENDER_EMAIL = 'etienne.cotin.pro@gmail.com';

    public function __construct(\Swift_Mailer $mailer, Environment $twig, RouterInterface $router, LoggerInterface $logger)
    {
        $this->mailer = $mailer;
        $this->twig = $twig;
        $this->router = $router;
        $this->logger = $logger;
    }

    /**
     * @param string $receiver Email of receiver
     * @throws \Exception
     */
    public function sendRegistration(string $receiver): void
    {
        try {
            $message = (new \Swift_Message('Hello from keinosity'))
                ->setFrom(self::SENDER_EMAIL)
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
        } catch (LoaderError $e) {
            $logDate = new \DateTime();
            $this->logger->error('['.$logDate->format('d/m/Y').'] Email was not send');
        } catch (RuntimeError $e) {
            $logDate = new \DateTime();
            $this->logger->error('['.$logDate->format('d/m/Y').'] Email was not send');
        } catch (SyntaxError $e) {
            $logDate = new \DateTime();
            $this->logger->error('['.$logDate->format('d/m/Y').'] Email was not send');
        }
    }
}
