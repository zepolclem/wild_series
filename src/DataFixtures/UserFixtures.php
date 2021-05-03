<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserFixtures extends Fixture
{
    private $passwordEncoder;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }

    public function load(ObjectManager $manager)
    {
        $userAdmin = new User();
        $userAdmin->setEmail('admin@wcs.com');
        $userAdmin->setRoles(['ROLE_ADMIN']);
        $userAdmin->setPassword($this->passwordEncoder->encodePassword(
            $userAdmin,
            'admin'
        ));
        $manager->persist($userAdmin);

        $userContributor = new User();
        $userContributor->setEmail('contributor@wcs.com');
        $userContributor->setRoles(['ROLE_CONTRIBUTOR']);
        $userContributor->setPassword($this->passwordEncoder->encodePassword(
            $userContributor,
            'contributor'
        ));
        $this->setReference('user_contrib', $userContributor);
        $manager->persist($userContributor);

        $user = new User();
        $user->setEmail('user@wcs.com');
        $user->setRoles(['ROLE_USER']);
        $user->setPassword($this->passwordEncoder->encodePassword(
            $user,
            'user'
        ));
        $manager->persist($user);

        $manager->flush();
    }
}
