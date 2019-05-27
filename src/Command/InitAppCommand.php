<?php

namespace App\Command;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class InitAppCommand extends Command
{
    /** @var EntityManagerInterface */
    private $entityManager;
    /** @var UserPasswordEncoderInterface  */
    private $passwordEncoder;
    /** @var LoggerInterface */
    private $logger;

    protected static $defaultName = 'app:initApp';

    public function __construct(EntityManagerInterface $entityManager, UserPasswordEncoderInterface $passwordEncoder, LoggerInterface $logger)
    {
        parent::__construct(self::$defaultName);
        $this->entityManager = $entityManager;
        $this->passwordEncoder = $passwordEncoder;
        $this->logger = $logger;
    }

    protected function configure()
    {
        $this
            ->setDescription('Initialize the first admin user.')
            ->addArgument('isForTesting', InputArgument::OPTIONAL, 'Execute command on test env')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);
        $isForTesting = $input->getArgument('isForTesting');

        $io->writeln(['Create the fist user admin of your application',
                    '============',
                    '']);

        if (!$isForTesting) {
            $helper = $this->getHelper('question');
            $emailQuestion = new Question('Define email [default is admin@admin.com] ', 'admin@admin.com');
            $email = $helper->ask($input, $output, $emailQuestion);
            $io->writeln('');

            $userNameQuestion = new Question('Define userName [default is admin] ', 'admin');
            $userName = $helper->ask($input, $output, $userNameQuestion);
            $io->writeln('');

            $passwordQuestion = new Question('Define password [default is admin] ', 'admin');
            $password = $helper->ask($input, $output, $passwordQuestion);
            $io->writeln('');
        } else {
            $email = 'admin@admin.com';
            $userName = 'admin';
            $password = 'admin';
        }


        if ($email && $userName) {
            $io->note(sprintf('Email: %s', $email));
            $io->note(sprintf('User name: %s', $userName));

            $existingUser = $this->entityManager->getRepository(User::class)->findOneBy(['email' => $email]);
            if (!$existingUser) {
                $user = new User();
                $encodedPassword = $this->passwordEncoder->encodePassword($user, $password);
                $user->create($email, $userName, new \DateTime(), 'admin', 'admin', 'Nantes', $encodedPassword);
                $this->entityManager->persist($user);
                $this->entityManager->flush();
                if ($password !== 'admin') {
                    $io->note(sprintf('Password: %s', $password));
                    $io->success('Admin was created with email: '. $email .' and your custom password.');
                    $this->logger->info('Admin was created with email: '. $email .' and your custom password.');
                } else {
                    $io->success('Admin was created with email: '. $email .' and default password: '. $password .'.');
                    $this->logger->info('Admin was created with email: '. $email .' and default password: '. $password .'.');
                }
            } else {
                $io->error('Admin already exist in database with email: '. $email .'.');
                $this->logger->error('Admin already exist in database with email: '. $email .'.');
            }
        }
    }
}
