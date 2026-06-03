<?php

declare(strict_types=1);

namespace Momentum\Lock;

use Closure;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Gate;
use ReflectionClass;
use ReflectionFunction;
use ReflectionMethod;

class Lock
{
    /**
     * @param  list<string>|null  $abilities
     * @return array<string, bool>
     */
    public static function getPermissions(mixed $model, ?array $abilities = null): array
    {
        $abilities ??= static::getAbilitiesFromPolicy($model);

        return collect($abilities)
            ->mapWithKeys(fn ($ability) => [$ability => Gate::allows($ability, $model)])
            ->toArray();
    }

    /**
     * @param  list<string>|null  $abilities
     * @return array<string, bool>
     */
    public static function getGlobalPermissions(?array $abilities = null): array
    {
        return collect(Gate::abilities())
            ->filter(function (Closure $closure, $ability) use ($abilities) {
                if ($abilities && ! in_array($ability, $abilities)) {
                    return false;
                }

                $reflection = new ReflectionFunction($closure);

                return $reflection->getNumberOfParameters() === 1;
            })
            ->mapWithKeys(fn (Closure $closure, $ability) => [$ability => Gate::check($ability)])
            ->toArray();
    }

    /**
     * @return list<string>
     */
    public static function getAbilitiesFromPolicy(Model $model): array
    {
        $policy = Gate::getPolicyFor($model);

        $reflection = new ReflectionClass($policy);

        $abilities = array_map(
            static fn (ReflectionMethod $method): string => $method->getName(),
            $reflection->getMethods(ReflectionMethod::IS_PUBLIC),
        );

        return $abilities;
    }
}
