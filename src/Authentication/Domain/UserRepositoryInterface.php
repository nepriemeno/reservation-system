<?php

declare(strict_types=1);

namespace App\Authentication\Domain;

use App\Authentication\Domain\Exception\UserNotFoundException;
use Doctrine\DBAL\Exception;

interface UserRepositoryInterface
{
    /** @throws Exception */
    public function save(User $user): void;
    public function findOneByUuid(string $uuid): ?User;
    /**
     * @param string $uuid
     *
     * @return User
     *
     * @throws UserNotFoundException
     */
    public function getOneByUuid(string $uuid): User;
    public function findOneByUuidActive(string $uuid): ?User;
    /**
     * @param string $uuid
     *
     * @return User
     *
     * @throws UserNotFoundException
     */
    public function getOneByUuidActive(string $uuid): User;
    public function findOneByEmail(string $email): ?User;
    /**
     * @param string $email
     *
     * @return User
     *
     * @throws UserNotFoundException
     */
    public function getOneByEmail(string $email): User;
    public function findOneByEmailActive(string $email): ?User;
    /**
     * @param string $email
     *
     * @return User
     *
     * @throws UserNotFoundException
     */
    public function getOneByEmailActive(string $email): User;
    public function findOneByEmailVerificationSlug(string $emailVerificationSlug): ?User;
    /**
     * @param string $emailVerificationSlug
     *
     * @return User
     *
     * @throws UserNotFoundException
     */
    public function getOneByEmailVerificationSlug(string $emailVerificationSlug): User;
    public function findOneByEmailVerificationSlugActive(string $emailVerificationSlug): ?User;
    /**
     * @param string $emailVerificationSlug
     *
     * @return User
     *
     * @throws UserNotFoundException
     */
    public function getOneByEmailVerificationSlugActive(string $emailVerificationSlug): User;
}
