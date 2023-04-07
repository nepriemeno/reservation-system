<?php

declare(strict_types=1);

namespace App\Authentication\Infrastructure\Doctrine;

use App\Authentication\Domain\Exception\UserNotFoundException;
use App\Authentication\Domain\User;
use App\Authentication\Domain\UserRepositoryInterface;
use DateTimeImmutable;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/** @template-extends ServiceEntityRepository<User> */
final class UserRepository extends ServiceEntityRepository implements UserRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }

    public function save(User $user): void
    {
        $user->setUpdatedAt(new DateTimeImmutable());
        $this->_em->persist($user);
        $this->_em->flush();
    }

    public function findOneByUuid(string $uuid): ?User
    {
        return $this->findOneBy(['uuid' => $uuid]);
    }

    /**
     * @param string $uuid
     *
     * @return User
     *
     * @throws UserNotFoundException
     */
    public function getOneByUuid(string $uuid): User
    {
        $user = $this->findOneBy(['uuid' => $uuid]);

        if ($user === null) {
            throw new UserNotFoundException();
        }

        return $user;
    }

    public function findOneByUuidActive(string $uuid): ?User
    {
        return $this->findOneBy(['uuid' => $uuid, 'isActive' => true]);
    }

    /**
     * @param string $uuid
     *
     * @return User
     *
     * @throws UserNotFoundException
     */
    public function getOneByUuidActive(string $uuid): User
    {
        $user = $this->findOneBy(['uuid' => $uuid, 'isActive' => true]);

        if ($user === null) {
            throw new UserNotFoundException();
        }

        return $user;
    }

    public function findOneByEmail(string $email): ?User
    {
        return $this->findOneBy(['email' => $email]);
    }

    /**
     * @param string $email
     *
     * @return User
     *
     * @throws UserNotFoundException
     */
    public function getOneByEmail(string $email): User
    {
        $user = $this->findOneBy(['email' => $email]);

        if ($user === null) {
            throw new UserNotFoundException();
        }

        return $user;
    }

    public function findOneByEmailActive(string $email): ?User
    {
        return $this->findOneBy(['email' => $email, 'isActive' => true]);
    }

    /**
     * @param string $email
     *
     * @return User
     *
     * @throws UserNotFoundException
     */
    public function getOneByEmailActive(string $email): User
    {
        $user = $this->findOneBy(['email' => $email, 'isActive' => true]);

        if ($user === null) {
            throw new UserNotFoundException();
        }

        return $user;
    }

    public function findOneByEmailVerificationSlug(string $emailVerificationSlug): ?User
    {
        return $this->findOneBy(['emailVerificationSlug' => $emailVerificationSlug]);
    }

    /**
     * @param string $emailVerificationSlug
     *
     * @return User
     *
     * @throws UserNotFoundException
     */
    public function getOneByEmailVerificationSlug(string $emailVerificationSlug): User
    {
        $user = $this->findOneBy(['emailVerificationSlug' => $emailVerificationSlug]);

        if ($user === null) {
            throw new UserNotFoundException();
        }

        return $user;
    }

    public function findOneByEmailVerificationSlugActive(string $emailVerificationSlug): ?User
    {
        return $this->findOneBy(['emailVerificationSlug' => $emailVerificationSlug, 'isActive' => true]);
    }

    /**
     * @param string $emailVerificationSlug
     *
     * @return User
     *
     * @throws UserNotFoundException
     */
    public function getOneByEmailVerificationSlugActive(string $emailVerificationSlug): User
    {
        $user = $this->findOneBy(['emailVerificationSlug' => $emailVerificationSlug, 'isActive' => true]);

        if ($user === null) {
            throw new UserNotFoundException();
        }

        return $user;
    }
}