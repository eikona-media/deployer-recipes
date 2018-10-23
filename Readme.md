# Deployer Recipes

This repository contains third party recipes to integrate with [deployer](https://github.com/deployphp/deployer).

## Installing

~~~sh
composer require eikona/deployer-recipes --dev
~~~

Include recipes in `deploy.php` file.

```php
require 'recipe/contao.php';
```

## Recipes

| Recipe     | Docs                        |
| ---------- |:---------------------------:|
| akeneo 2.* | [read](docs/akeneo2.md)     |
| contao     | [read](docs/contao.md)      |
| nextcloud  | [read](docs/nextcloud.md)   |
| roundcube  | [read](docs/roundcube.md)   |

## Deploy Helper

| Recipe         | Docs                                 |
|----------------|:------------------------------------:|
| tar            | [read](docs/deploy/tar.md)           |
| scp            | [read](docs/deploy/scp.md)           |
| gitlab_ci      | [read](docs/deploy/gitlab_ci.md)     |
| update_shared  | [read](docs/deploy/update_shared.md) |


## More Doc + Recipes...

* https://deployer.org/docs

* https://github.com/deployphp/recipes/
