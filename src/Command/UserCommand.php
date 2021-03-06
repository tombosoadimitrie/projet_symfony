<?php
namespace App\Command;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputDefinition;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class UserCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this->setName("app:create:user")
            ->setHelp("Create new user")
            ->setDescription("Create new user")
            ->setDefinition(
                new InputDefinition([
                    new InputOption('username', 'u', InputOption::VALUE_REQUIRED, "username"),
                    new InputOption('password', 'p', InputOption::VALUE_REQUIRED, "password"),
                    new InputOption("email", "m", InputOption::VALUE_REQUIRED, "email"),
                ])
            );
        parent::configure(); // TODO: Change the autogenerated stub
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);

        $output->writeln(["Let's create a new user",]);
        $username = $input->getOption("username");
        $password = $input->getOption("password");
        $email = $input->getOption("email");

        $em = $this->getContainer()->get("doctrine")->getManager();
        
        $userByUsername = $em->getRepository(User::class)->findOneByUsername($username);
        $userByEmail = $em->getRepository(User::class)->findOneByUsername($email);

        if ($userByUsername == null AND $userByEmail == null) {
            $user = new User();
            $passwordHashed = $this->getContainer()->get('security.password_encoder')->encodePassword($user, $password);
            $user->setUsername($username)
                ->setEmail($email)
                ->setPassword($passwordHashed);
            $em->persist($user);
            $em->flush();
            
            $io->success("Great!!! Utilisateur créé avec succès");
            $io->table(['id', 'username', 'password', 'email'], [0=>[$user->getId(), $username, $password, $email]]);
        }
        else {
            $io->warning("Désolé!!! L'username ou l'email a déjà été prise par un autre utilisateur");
        }
        // parent::execute($input, $output); // TODO: Change the autogenerated stub
    }
}
