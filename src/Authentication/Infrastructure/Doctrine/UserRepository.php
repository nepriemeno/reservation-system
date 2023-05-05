<?php

declare(strict_types=1);

namespace App\Authentication\Infrastructure\Doctrine;

use App\Authentication\Domain\Exception\UserNotFoundException;
use App\Authentication\Domain\User;
use App\Authentication\Domain\UserRepositoryInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\DBAL\Exception;
use Doctrine\Persistence\ManagerRegistry;

/** @template-extends ServiceEntityRepository<User> */
final class UserRepository extends ServiceEntityRepository implements UserRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }

    /** @throws Exception */
    public function save(User $user): void
    {
        $em = $this->getEntityManager();
        $em->getConnection()->beginTransaction();

        try {
            $em->persist($user);

            foreach ($user->getEvents() as $event) {
                $em->persist($event->getOutBoxMessage());
            }

            $em->flush();
            $em->getConnection()->commit();
        } catch (\Exception $e) {
            $em->getConnection()->rollBack();

            throw $e;
        }
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

    public function getOneByUuidActiveAdmin(string $uuid): User
    {
        $user = $this->findOneBy(['uuid' => $uuid, 'isActive' => true, 'roles' => ["ROLE_ADMIN"]]);

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
