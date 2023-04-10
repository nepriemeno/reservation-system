<?php

declare(strict_types=1);

namespace App\Tests\Unit\Authentication\Domain;

use App\Authentication\Domain\User;
use DateTimeImmutable;
use PHPUnit\Framework\TestCase;

final class UserTest extends TestCase
{
    /**
     * @param string $uuid
     * @param string $email
     * @param string $password
     * @param string[] $roles
     * @param string $emailVerificationSlug
     * @param DateTimeImmutable $emailVerificationSlugExpiresAt
     * @param bool $isActive
     *
     * @return void
     *
     * @dataProvider classConstructorProvider
     */
    public function testClassConstructor(
        string $uuid,
        string $email,
        string $password,
        array $roles,
        string $emailVerificationSlug,
        DateTimeImmutable $emailVerificationSlugExpiresAt,
        bool $isActive,
    ): void {
        $user = new User(
            uuid: $uuid,
            email: $email,
            password: $password,
            emailVerificationSlug: $emailVerificationSlug,
            emailVerificationSlugExpiresAt: $emailVerificationSlugExpiresAt
        );
        $this->assertSame($uuid, $user->getUuid());
        $this->assertSame($email, $user->getEmail());
        $this->assertSame($password, $user->getPassword());
        $this->assertSame($roles, $user->getRoles());
        $this->assertSame($emailVerificationSlug, $user->getEmailVerificationSlug());
        $this->assertSame($emailVerificationSlugExpiresAt, $user->getEmailVerificationSlugExpiresAt());
        $this->assertSame($isActive, $user->isActive());
        $this->assertNotNull($user->getCreatedAt());
        $this->assertNotNull($user->getUpdatedAt());
    }

    /**
     * @return array{
     *      uuid: string,
     *      email: string,
     *      password: string,
     *      roles: string[],
     *      emailVerificationSlug: string,
     *      emailVerificationSlugExpiresAt: DateTimeImmutable,
     *      isActive: bool
     * }[]
     */
    public static function classConstructorProvider(): array
    {
        return [
            [
                'uuid' => '1',
                'email' => 'email@test.com',
                'password' => '123',
                'roles' => ['ROLE_USER'],
                'emailVerificationSlug' => 'test',
                'emailVerificationSlugExpiresAt' => new DateTimeImmutable(),
                'isActive' => true,
            ],
        ];
    }
}
