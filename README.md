# Momentum Lock

Momentum Lock is a Laravel package that lets you handle Laravel authorizations on the frontend level.

The package is only intended to work with [Laravel Data](https://github.com/spatie/laravel-data) objects and [TypeScript Transformer](https://github.com/spatie/laravel-typescript-transformer).

> [!NOTE]
> Inventas maintains this repository as a fork of [`lepikhinb/momentum-lock`](https://github.com/lepikhinb/momentum-lock). We adopted the package because the upstream project appears inactive and we need ongoing Laravel support, including Laravel 13.

- [**Installation**](#installation)
  - [**Laravel**](#laravel)
  - [**Frontend**](#frontend)
- [**Usage**](#usage)
- [**Credits**](#credits)

## Installation

### Laravel

Install the package into your Laravel app.

```bash
composer require inventas/momentum-lock
```

This fork is tested against Laravel 12 and Laravel 13.

### Frontend

The frontend package is framework-agnostic and will work great within any TypeScript-powered workflow.

Install the upstream [frontend package](https://github.com/lepikhinb/momentum-lock-helper).

```bash
npm i momentum-lock
# or
yarn add momentum-lock
```

## Usage

Extend your data classes from `DataResource` instead of `Data` provided by [Laravel Data](https://github.com/spatie/laravel-data).

```php
use Momentum\Lock\Data\DataResource;

class UserData extends DataResource
{
    public function __construct(
        public int $id,
        public string $username
    ) {
    }
}
```

You can either specify the list of abilities manually, or let the package resolve them from the corresponding policy class.

```php
class UserData extends DataResource
{
    protected $permissions = ['update', 'delete'];
}
```

Register `DataResourceCollector` in the TypeScript Transformer configuration file — `typescript-transformer.php`. This class helps TypeScript Transformer handle `DataResource` classes and append permissions to generated TypeScript definitions.

```diff
return [
    'collectors' => [
+       Momentum\Lock\TypeScript\DataResourceCollector::class,
        Spatie\TypeScriptTransformer\Collectors\DefaultCollector::class,
        Spatie\LaravelData\Support\TypeScriptTransformer\DataTypeScriptCollector::class,
    ],
]
```

On the frontend, you can use the helper `can`. This function checks whether the required permission is set to true on the passed object, and can be used in both scripts or templates.

```vue
<script lang="ts" setup>
import { can } from "momentum-lock"

const props = defineProps<{
  users: UserData[]
}>()
</script>

<template>
  <div v-for="user in users" :key="user.id">
    <a v-if="can(user, 'update')" :href="route('users.edit', user)"> Edit </a>
  </div>
</template>
```

## Credits

- Original package by [Boris Lepikhin](https://github.com/lepikhinb)
- Maintained fork by [Inventas](https://github.com/Inventas)

## License

The MIT License (MIT). Please see [LICENSE](LICENSE) for more information.
