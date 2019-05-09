<?php

namespace App\Services;


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

    const SENDER_EMAIL = 'etienne.cotin.pro@gmail.com';

    public function __construct(\Swift_Mailer $mailer, Environment $twig, RouterInterface $router)
    {
        $this->mailer = $mailer;
        $this->twig = $twig;
        $this->router = $router;
    }

    /**
     * @param string $receiver Email of receiver
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

        } catch (RuntimeError $e) {
        } catch (SyntaxError $e) {
        }
    }
}
