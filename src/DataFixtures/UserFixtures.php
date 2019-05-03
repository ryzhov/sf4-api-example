<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use App\Entity\User;

class UserFixtures extends Fixture
{
    private $encoder;

    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }

    public function load(ObjectManager $manager)
    {
        $fixtures = [
            ['email' => 'anryzhov@gmail.com', 'password' => '27alex27'],
            ['email' => 'admin@uiptel.com', 'password' => 'pass4admin'],
        ];

        foreach ($fixtures as $fixture) {
            $user = (new User())->setEmail($fixture['email']);
            $user->setPassword($this->encoder->encodePassword(
                $user,
                $fixture['password']
            ));
            $manager->persist($user);
        }

        $manager->flush();
    }
}
