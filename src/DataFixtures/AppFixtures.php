<?php

namespace App\DataFixtures;

use App\Entity\Activity;
use App\Entity\ActLike;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Faker\Factory;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AppFixtures extends Fixture
{
    /**
     * Encodeur de mot de passe
     *
     * @var UserPasswordEncoderInterface
     */
    private $encoder;

    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }

    public function load(ObjectManager $manager)
    {
        $faker = Factory::create();
        $users=[];
        $user = new User();
        $user->setEmail('user@symfony.com')
            ->setPassword($this->encoder->encodePassword($user, 'password'));

        $manager->persist($user);
        $users[] = $user;
        for($i = 0; $i<20;$i++)
        {
            $user = new User();
            $user->setEmail($faker->email)
                ->setPassword($this->encoder->encodePassword($user,'password'));
            $manager->persist($user);
            $users[] = $user;
        }
        for ($i = 0; $i < 20; $i++) {
            $post = new Activity();
            $post->setIdAct($faker->word)
                ->setDescription($faker->paragraph())
                ->setCategorie($faker->sentence(1))->setType($faker->word);

            $manager->persist($post);
            for($j=0; $j<10; $j++)
            {
                $like = new ActLike();
                $like->setPost($post)
                    ->setUser($faker->randomElement($users));
                $manager->persist($like);
            }
        }

        $manager->flush();
    }
}