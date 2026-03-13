<?php

namespace App\Tests\Entity;

use PHPUnit\Framework\TestCase;
use App\Entity\User;

class UserTest extends TestCase
{
    public function testTheAutoApiTokenSettingWhenUserIsCreated(): void
    {
        $user = new User();
        $this->assertNotNull($user->getApiToken());
    }

    // public function testThanAUserAsAtLeastOneRoleUser(): void
    // {
    //     $user = new User();
    //     $this->assertContains(needle. 'ROLE_USER', $user->getRoles());
    // }
}