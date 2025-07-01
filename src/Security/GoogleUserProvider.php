<?php

namespace App\Security;

use League\OAuth2\Client\Provider\GoogleUser;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class GoogleUserProvider
{
    public function __construct(
        private EntityManagerInterface $em,
        private UserPasswordHasherInterface $passwordHasher
    ) {}

    public function loadUserByGoogleUser(GoogleUser $googleUser): User
    {
        $googleId = $googleUser->getId();
        $email = $googleUser->getEmail();

        $user = $this->em->getRepository(User::class)->findOneBy(['googleId' => $googleId]);

        if (!$user) {
            // Recherche par email pour lier un compte existant
            $user = $this->em->getRepository(User::class)->findOneBy(['email' => $email]);

            if ($user) {
                $user->setGoogleId($googleId);
            } else {
                $user = new User();
                $user->setGoogleId($googleId);
                $user->setEmail($email);
                $user->setRoles(['ROLE_USER']);

                // Générer un mot de passe aléatoire encodé, car il est obligatoire (même si non utilisé)
                $randomPassword = bin2hex(random_bytes(10));
                $encodedPassword = $this->passwordHasher->hashPassword($user, $randomPassword);
                $user->setPassword($encodedPassword);

                // Init autres champs si tu veux éviter erreurs (optionnel)
                $user->setCreateAt(new \DateTime());
                $user->setIsVerified(true);

                // Optionnel : récupérer nom et prénom depuis Google si dispo
                if ($googleUser->getName()) {
                    $user->setName($googleUser->getName());
                }
                if ($googleUser->getFirstName()) {
                    $user->setFirstname($googleUser->getFirstName());
                }
            }

            $this->em->persist($user);
            $this->em->flush();
        }

        return $user;
    }
}
