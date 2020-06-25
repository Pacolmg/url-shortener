<?php

namespace App\Command;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * Class CreateUpdateUser
 * @package App\Command
 */
class CreateUpdateUser extends Command
{
    protected static $defaultName = 'app:create-update:user';

    /**
     * @var EntityManagerInterface
     */
    private $em;
    /**
     * @var UserPasswordEncoderInterface
     */
    private $encoder;

    /**
     * CreateUpdateUser constructor.
     * @param string|null $name
     * @param EntityManagerInterface $em
     * @param UserPasswordEncoderInterface $encoder
     */
    public function __construct(string $name = null, EntityManagerInterface $em, UserPasswordEncoderInterface $encoder)
    {
        parent::__construct($name);
        $this->em = $em;
        $this->encoder = $encoder;
    }

    protected function configure()
    {
        $this
            ->addArgument('username', InputArgument::REQUIRED, 'Username')
            ->addArgument('password', InputArgument::REQUIRED, 'Password')
            ->addArgument('roles', InputArgument::OPTIONAL, 'Roles separated by comma')
            ->setDescription('Create a new user or update an existing one');
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $user = $this->em->getRepository('App:User')->findOneBy(['username' => $input->getArgument('username')]);
        if (!$user instanceof User) {
            $user = new User();
            $user->setUsername($input->getArgument('username'));
            $this->em->persist($user);
            $created = true;
        }
        if (!empty($input->getArgument('roles'))) {
            $user->setRoles(explode(',', $input->getArgument('roles')));
        }
        $user->setPassword($this->encoder->encodePassword($user, $input->getArgument('password')));

        $this->em->flush();
        $output->writeln('User ' . $input->getArgument('username') . ' ' . ((isset($created)) ? 'created' : 'updated'));

        return 0;
    }
}
