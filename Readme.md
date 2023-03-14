# Deployer Recipes

This repository contains third party recipes to integrate with [deployer](https://github.com/deployphp/deployer).

## Installing

~~~sh
composer require eikona-media/deployer-recipes --dev
~~~

Include recipes in `deploy.php` file.

```php
require 'recipe/contao.php';
```

## Recipes

| Recipe     | Docs                        |
| ---------- |:---------------------------:|
| akeneo 2.* | [read](docs/akeneo2.md)     |
| akeneo 4.* | [read](docs/akeneo4.md)     |
| akeneo 5.* | [read](docs/akeneo5.md)     |
| contao     | [read](docs/contao.md)      |
| cycon      | [read](docs/cycon.md)       |
| shopware6  | [read](docs/shopware6.md)     |

## Build Helper

| Recipe         | Docs                                 |
|----------------|:------------------------------------:|
| composer       | [read](docs/build/composer.md)       |
| npm            | [read](docs/build/npm.md)            |
| yarn           | [read](docs/build/yarn.md)           |

## Deploy Helper

| Recipe         | Docs                                 |
|----------------|:------------------------------------:|
| cache          | [read](docs/deploy/cache.md)         |
| cleanup        | [read](docs/deploy/cleanup.md)       |
| gitlab_ci      | [read](docs/deploy/gitlab_ci.md)     |
| maintenance    | [read](docs/deploy/maintenance.md)   |
| no_releases    | [read](docs/deploy/no_releases.md)   |
| scp            | [read](docs/deploy/scp.md)           |
| supervisor     | [read](docs/deploy/supervisor.md)    |
| tar            | [read](docs/deploy/tar.md)           |
| update_shared  | [read](docs/deploy/update_shared.md) |


## More Doc + Recipes...

* https://deployer.org/docs

* https://github.com/deployphp/recipes/
