<?php

declare(strict_types=1);

namespace App\Authentication\Infrastructure\Doctrine;

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

    public function findOneByUuidActive(string $uuid): ?User
    {
        return $this->findOneBy(['uuid' => $uuid, 'isActive' => true]);
    }

    public function findOneByEmail(string $email): ?User
    {
        return $this->findOneBy(['email' => $email]);
    }

    public function findOneByEmailActive(string $email): ?User
    {
        return $this->findOneBy(['email' => $email, 'isActive' => true]);
    }

    public function findOneByEmailVerificationSlug(string $emailVerificationSlug): ?User
    {
        return $this->findOneBy(['emailVerificationSlug' => $emailVerificationSlug]);
    }

    public function findOneByEmailVerificationSlugActive(string $emailVerificationSlug): ?User
    {
        return $this->findOneBy(['emailVerificationSlug' => $emailVerificationSlug, 'isActive' => true]);
    }
}
