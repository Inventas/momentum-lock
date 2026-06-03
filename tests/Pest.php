<?php

declare(strict_types=1);

use Momentum\Lock\Tests\Stubs\User;
use Momentum\Lock\Tests\TestCase;

uses(TestCase::class)->in('Pest');

function user(): User
{
    return User::create(['username' => 'test-user']);
}
