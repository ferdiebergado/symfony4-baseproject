<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Entity\User;
use DateTime;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AppFixtures extends Fixture
{
    /**
     * The password encoder
     *
     * @var UserPasswordEncoderInterface
     */
    private $userPasswordEncoderInterface;

    public function __construct(UserPasswordEncoderInterface $userPasswordEncoderInterface)
    {
        $this->userPasswordEncoderInterface = $userPasswordEncoderInterface;
    }

    /**
     * Load the database fixtures
     */
    public function load(ObjectManager $manager): void
    {
        $user = new User();
        $user->setEmail('abc@123.com');
        $user->setPassword($this->userPasswordEncoderInterface->encodePassword($user, 'abc@1234'));
        $user->setVerifiedAt(new DateTime());
        $user->setIsActive(true);
        $manager->persist($user);

        $manager->flush();
    }
}
