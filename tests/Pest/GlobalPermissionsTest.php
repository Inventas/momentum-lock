<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Gate;
use Momentum\Lock\Lock;

use function Pest\Laravel\actingAs;
use function PHPUnit\Framework\assertArrayHasKey;
use function PHPUnit\Framework\assertArrayNotHasKey;
use function PHPUnit\Framework\assertFalse;
use function PHPUnit\Framework\assertTrue;

test('global gates can be handled', function () {
    $user = user();

    actingAs($user);

    $assertionMap = [
        'true' => assertTrue(...),
        'false' => assertFalse(...),
        'conditionable-true' => assertTrue(...),
        'conditionable-false' => assertFalse(...),
    ];

    $permissions = Lock::getGlobalPermissions();

    foreach ($assertionMap as $permission => $assert) {
        assertArrayHasKey($permission, $permissions);

        $assert($permissions[$permission]);
        $assert(Gate::check($permission));
    }

    assertArrayNotHasKey('with-argument-true', $permissions);
    assertArrayNotHasKey('with-argument-false', $permissions);
});
