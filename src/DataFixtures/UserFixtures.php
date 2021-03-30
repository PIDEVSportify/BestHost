<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserFixtures extends BaseFixtures
{
    private UserPasswordEncoderInterface $encoder;

    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }

    protected function loadData(ObjectManager $manager): void
    {
        $demoUser = new User();
        $demoUser->setFirstName('demo')
            ->setLastName('demo')
            ->setAvatar("avatar")
            ->setPassword($this->encoder->encodePassword($demoUser, 'demo'))
            ->setEmail('demo@demo.com');
        $adminUser = new User();
        $adminUser->setFirstName('admin')
        ->setLastName('demo')
            ->setPassword($this->encoder->encodePassword($demoUser, 'admin'))
            ->setEmail('admin@admin.com')
            ->setAvatar("avatar")
            ->setEmail('admin@admin.com')
            ->setRoles(['ROLE_ADMIN']);

        $manager->persist($demoUser);
        $manager->persist($adminUser);

        $this->createMany(User::class, FixturesSettings::USERS_COUNT, function (User $user) {
            $user->setFirstName($this->faker->userName)
            ->setLastName('demo')
                ->setPassword($this->encoder->encodePassword($user, 'password'))
                ->setEmail($this->faker->email)
                ->setCreatedAt($this->faker->dateTimeBetween('-1 years'))
                ->setAvatar("avatar")
                ->setLastActivityAt($this->faker->dateTimeBetween($user->getCreatedAt()));
                
            if ($this->faker->boolean(7)) {
                $user->setRoles(['ROLE_MODERATOR']);
            }
        });

        $manager->flush();
    }
}
