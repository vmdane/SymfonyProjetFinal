<?php

namespace App\Security;

use App\Entity\User;
use App\Repository\UserRepository;
use KnpU\OAuth2ClientBundle\Security\User\OAuthUserProviderInterface;
use League\OAuth2\Client\Provider\GoogleUser;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\User\UserProviderInterface;

class GoogleUserProvider implements OAuthUserProviderInterface
{
    public function __construct(private UserRepository $userRepository)
    {
    }

    public function loadUserByOAuthUserResponse(\League\OAuth2\Client\Provider\ResourceOwnerInterface $resourceOwner): UserInterface
    {
        /** @var GoogleUser $resourceOwner */
        $email = $resourceOwner->getEmail();

        $user = $this->userRepository->findOneBy(['email' => $email]);

        if (!$user) {
            $user = new User();
            $user->setEmail($email);
            $user->setFirstname($resourceOwner->getFirstName());
            $user->setLastname($resourceOwner->getLastName());

        }

        return $user;
    }

    public function supportsClass(string $class): bool
    {
        return User::class === $class;
    }

    public function refreshUser(UserInterface $user): UserInterface
    {
        if (!$user instanceof User) {
            throw new UnsupportedUserException();
        }

        return $this->userRepository->find($user->getId());
    }

    public function loadUserByIdentifier(string $identifier): UserInterface
    {
        return $this->userRepository->findOneBy(['email' => $identifier]);
    }
}
