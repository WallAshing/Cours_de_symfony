<?php

namespace App\DataFixtures;

use App\Entity\Pet;
use App\Entity\PetCategory;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Command\UserPasswordHashCommand;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{

    public function __construct(private UserPasswordHasherInterface $hasher){}

    public function load(ObjectManager $manager): void
    {
        $users = [];
        $list = ['Cat', 'Dog', 'Mosquito'];

        for($i = 1; $i <= 10; $i++){
            $user = new User;
            $user->setEmail('test'. $i . '@gmail.com');
            $user->setFirstName($i);
            $user->setLastName(($i * 10));

            $password = $this->hasher->hashPassword($user, 'password');
            $user->setPassword($password);

            if ($i === 1){
                $user->setRoles([
                    'ROLE_ADMIN'
                ]);
            }

            $users[] = $user;
            $manager->persist($user);
        }

        $manager->flush();

        foreach($list as $pet){
            $petCategory = new PetCategory;
            $petCategory->setName($pet);
            $manager->persist($petCategory);

            $manager->flush();

            for($i = 1; $i <= 10; $i++){

                $pet = new Pet;

//                $contentType = isset($_SERVER["CONTENT_TYPE"]) ? trim($_SERVER["CONTENT_TYPE"]) : '';
//                if ($contentType === "application/json") {
//                    $content = trim(file_get_contents("php://input"));
//
//                    $decoded = json_decode($content, true);
//
//                    if(! is_array($decoded)) {
//
//                    } else {
//                        throw new Exception("Didn't get json from API.");
//                    }
//                }
                $pet->setName($petCategory->getName() . ' ' . $i);
                $pet->setPetCategory($petCategory);
                if (rand(0, 1) === 0){
                    $pet->setUser($users[rand(0, count($users) - 1)]);
                }

                $manager->persist($pet);
            }
            $manager->flush();
        }
    }
}
